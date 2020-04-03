FROM node:alpine
EXPOSE 3000
RUN ["node", "index.js"]