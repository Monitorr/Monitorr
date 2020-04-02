FROM python:2.7.17-slim

LABEL maintainer="jonfinley"

ARG VERSION
ARG BRANCH

ENV MONITORR_DOCKER=True
ENV TZ=UTC

WORKDIR /app

RUN \
apt-get -q -y update --no-install-recommends && \
apt-get install -q -y --no-install-recommends \
  curl && \
rm -rf /var/lib/apt/lists/* && \
pip install --no-cache-dir --upgrade pip && \
pip install --no-cache-dir --upgrade \
  pycryptodomex \
  pyopenssl && \
echo ${VERSION} > /app/version.txt && \
echo ${BRANCH} > /app/branch.txt

COPY . /app

CMD [ "python", "Monitorr.py", "--datadir", "/config" ]

VOLUME /config /plex_logs
EXPOSE 7769
HEALTHCHECK  --start-period=90s CMD curl -ILfSs http://localhost:7769/status > /dev/null || curl -ILfkSs https://localhost:7769/status > /dev/null || exit 1