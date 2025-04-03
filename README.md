## Getting Started

First, run the development server:

```bash
npm run dev
# or
yarn dev
# or
pnpm dev
# or
bun dev
```

Open [http://localhost:3000](http://localhost:3000) with your browser to see the result.

You can start editing the page by modifying `app/page.tsx`. The page auto-updates as you edit the file.

This project uses [`next/font`](https://nextjs.org/docs/app/building-your-application/optimizing/fonts) to automatically optimize and load [Geist](https://vercel.com/font), a new font family for Vercel.

## Learn More

To learn more about Next.js, take a look at the following resources:

- [Next.js Documentation](https://nextjs.org/docs) - learn about Next.js features and API.
- [Learn Next.js](https://nextjs.org/learn) - an interactive Next.js tutorial.

You can check out [the Next.js GitHub repository](https://github.com/vercel/next.js) - your feedback and contributions are welcome!

## Deployement

Build docker image with :

```
docker build --secret id=env,src=$(pwd)/.env -t ghcr.io/mrcaktuz/markupconsult:latest .
```

Make sure you are auth with personal auth :

```
echo "YOUR_GITHUB_PAT" | docker login ghcr.io -u YOUR_GITHUB_USERNAME --password-stdin
```

Push docker image with :

```
docker push ghcr.io/mrcaktuz/markupconsult:latest
```

Check that it is done here : https://github.com/users/mrcaktuz/packages/container/package/markupconsult

Then go to server and run this :

```
docker stop $(docker ps -a -q)
```

```
docker rm $(docker ps -a -q)
```

```
docker rmi $(docker images -q)
```

```
docker run -d -p 3000:3000 ghcr.io/mrcaktuz/markupconsult:latest
```
