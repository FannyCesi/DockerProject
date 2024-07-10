FROM node:20-alpine

WORKDIR /app

COPY . package.json package-lock.json /app/

RUN apk add --no-cache --virtual .build-deps alpine-sdk python3

RUN npm install -g node-sass

CMD ["npm", "run", "watch"]