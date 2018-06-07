FROM lsiobase/alpine.nginx:3.7
MAINTAINER Monitorr

# Install packages
RUN \
 apk add --no-cache \
         curl \
         php7-curl \
         php7-zip \
         php7-sqlite3 \
         php7-pdo_sqlite \
         git

# Add local files
COPY root/ /

# Port and volumes
VOLUME /app /config
EXPOSE 80
