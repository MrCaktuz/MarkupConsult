FROM node:18-slim AS base

# Installer Chrome stable et ses dépendances système nécessaires
RUN apt-get update && apt-get install -y \
  curl \
  gnupg \
  ca-certificates \
  fonts-liberation \
  libasound2 \
  libatk-bridge2.0-0 \
  libatk1.0-0 \
  libcups2 \
  libdrm2 \
  libgtk-3-0 \
  libnss3 \
  libx11-xcb1 \
  libxcomposite1 \
  libxdamage1 \
  libxrandr2 \
  libxss1 \
  libgbm-dev \
  libxshmfence1 \
  --no-install-recommends \
  && curl -fsSL https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
  && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
  && apt-get update \
  && apt-get install -y google-chrome-stable --no-install-recommends \
  && rm -rf /var/lib/apt/lists/*

# ---

# Étape deps : installer les dépendances npm avec pnpm
FROM base AS deps
WORKDIR /app
RUN corepack enable pnpm
COPY package.json pnpm-lock.yaml* .npmrc* ./
RUN pnpm install --frozen-lockfile

# ---

# Étape build : builder Next.js
FROM base AS builder
WORKDIR /app
RUN corepack enable pnpm
COPY --from=deps /app/node_modules ./node_modules
COPY . .
RUN pnpm run build

# ---

# Étape production : préparation de l'image finale
FROM base AS runner
WORKDIR /app

# Créer groupe et utilisateur nextjs
RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 --home /home/nextjs nextjs

# Créer un vrai home pour nextjs et définir droits
RUN mkdir -p /home/nextjs/.cache /home/nextjs/.config
RUN chown -R nextjs:nodejs /home/nextjs /home/nextjs/.cache /home/nextjs/.config

ENV NODE_ENV=production
ENV HOSTNAME="0.0.0.0"
ENV PORT=3001
ENV HOME=/home/nextjs

COPY --from=builder /app/public ./public
COPY --from=builder --chown=nextjs:nodejs /app/.next/standalone ./
COPY --from=builder --chown=nextjs:nodejs /app/.next/static ./.next/static

RUN chmod -R 755 /app/public
RUN chown -R nextjs:nodejs /app/public

USER nextjs

EXPOSE 3001

CMD ["node", "server.js"]
