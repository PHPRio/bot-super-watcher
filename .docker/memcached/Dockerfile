FROM memcached

USER root

RUN apt-get update && apt-get install -y --no-install-recommends sasl2-bin && rm -rf /var/lib/apt/lists/*

CMD echo $MEMCACHEDCLOUD_PASSWORD | saslpasswd2 -c memcached -c $MEMCHACHEDCLOUD_USRNAME -p && exec memcached -S -u $MEMCACHEDCLOUD_USERNAME

USER memcache
CMD ["--user", "memcache"]
