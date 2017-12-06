FROM lsiobase/alpine.nginx:3.6
MAINTAINER christronyxyocum

# Install packages
RUN \
 apk add --no-cache \
	curl \
	php7-curl \
	php7-ldap \
	php7-pdo_sqlite \
	php7-sqlite3 \
	php7-session \
	php7-zip

# Add local files
COPY root/ /

# Port and volumes
EXPOSE 80
VOLUME /config
