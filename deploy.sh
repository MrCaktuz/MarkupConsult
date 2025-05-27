#!/bin/bash

set -e

APP_NAME="markupconsult"
TEMP_NAME="${APP_NAME}_temp"
PORT_NEW=3002
PORT_LIVE=3001
TIMESTAMP=$(date +%Y%m%d%H%M%S)
IMAGE_NAME=${APP_NAME}
IMAGE_TAG="${IMAGE_NAME}:${TIMESTAMP}"
IMAGE_LATEST="${IMAGE_NAME}:latest"

cd ~/MarkupConsult || exit 1

echo "📥 Pulling latest changes..."
git pull origin main

echo "🔨 Building new Docker image (${IMAGE_TAG})..."
docker build -t ${IMAGE_TAG} .

echo "🚀 Starting temp container..."
docker run -d -p ${PORT_NEW}:3001 --name ${TEMP_NAME} ${IMAGE_TAG}

echo "⏳ Waiting..."
sleep 5

if curl -s --fail http://localhost:${PORT_NEW} > /dev/null; then
  echo "✅ Healthcheck passed."

  docker stop ${APP_NAME} 2>/dev/null || true
  docker rm ${APP_NAME} 2>/dev/null || true
  echo "✅ Previous container stopped & deleted."
  docker rmi ${IMAGE_LATEST}
  echo "✅ Latest image deleted."
  docker tag ${IMAGE_TAG} ${IMAGE_LATEST}
  echo "✅ image:latest generated."

  docker run -d -p ${PORT_LIVE}:3001 --name ${APP_NAME} ${IMAGE_LATEST}
  echo "✅ New container running."
  docker stop ${TEMP_NAME} && docker rm ${TEMP_NAME}
  echo "✅ Temp container stopped & deleted."

  echo "🎉 Deploy complete with image: ${IMAGE_TAG}"
else
  echo "❌ Healthcheck failed. Rollback."
  docker logs ${TEMP_NAME}
  docker stop ${TEMP_NAME} && docker rm ${TEMP_NAME}
  exit 1
fi
