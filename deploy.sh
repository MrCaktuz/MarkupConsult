#!/bin/bash

set -euo pipefail

APP_NAME="markupconsult"
TEMP_NAME="${APP_NAME}_temp"
PORT_NEW=3002
PORT_LIVE=3001
TIMESTAMP=$(date +%Y%m%d%H%M%S)
IMAGE_NAME=${APP_NAME}
IMAGE_TAG="${IMAGE_NAME}:${TIMESTAMP}"
IMAGE_LATEST="${IMAGE_NAME}:latest"
KEEP_IMAGES=3   # nombre d'images horodatées conservées (pour rollback)

# Ports liés à localhost : seul nginx peut joindre l'app (Docker contourne UFW sinon)
RUN_OPTS=(-d --restart unless-stopped --memory 1g)

cd ~/MarkupConsult || exit 1

echo "📥 Pulling latest changes..."
git pull origin main

echo "🔨 Building new Docker image (${IMAGE_TAG})..."
docker build -t "${IMAGE_TAG}" .

# Nettoie un éventuel conteneur temporaire laissé par un déploiement précédent raté
docker rm -f "${TEMP_NAME}" 2>/dev/null || true

echo "🚀 Starting temp container..."
docker run -d -p 127.0.0.1:${PORT_NEW}:3001 --name "${TEMP_NAME}" "${IMAGE_TAG}"

echo "⏳ Healthcheck..."
HEALTHY=false
for i in {1..15}; do
  if curl -fsSL -o /dev/null "http://localhost:${PORT_NEW}/en"; then
    HEALTHY=true
    break
  fi
  sleep 2
done

if [ "${HEALTHY}" != true ]; then
  echo "❌ Healthcheck failed. Rollback (le conteneur live n'a pas été touché)."
  docker logs "${TEMP_NAME}" || true
  docker rm -f "${TEMP_NAME}" || true
  exit 1
fi
echo "✅ Healthcheck passed."

# docker tag écrase l'ancien :latest, pas besoin de rmi
docker tag "${IMAGE_TAG}" "${IMAGE_LATEST}"
echo "✅ image:latest updated."

docker rm -f "${APP_NAME}" 2>/dev/null || true
docker run "${RUN_OPTS[@]}" -p 127.0.0.1:${PORT_LIVE}:3001 --name "${APP_NAME}" "${IMAGE_LATEST}"
echo "✅ New container running."

docker rm -f "${TEMP_NAME}" || true
echo "✅ Temp container deleted."

echo "🧹 Cleaning old images (keeping ${KEEP_IMAGES})..."
docker images "${IMAGE_NAME}" --format '{{.Tag}}' \
  | grep -E '^[0-9]{14}$' | sort -r | tail -n +$((KEEP_IMAGES + 1)) \
  | xargs -r -I{} docker rmi "${IMAGE_NAME}:{}" || true
docker image prune -f > /dev/null || true

echo "🎉 Deploy complete with image: ${IMAGE_TAG}"