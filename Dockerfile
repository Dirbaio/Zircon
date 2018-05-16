FROM node:9.6 AS jsbuild

RUN mkdir /build
WORKDIR /build
COPY js/package.json js/package-lock.json /build/
RUN npm install
COPY js /build/
RUN npm run build

FROM ubuntu:16.04

RUN apt-get update && apt-get install -y nano nginx php-mysql php-fpm php-curl php-xml supervisor git curl php-gd php-xdebug

RUN ln -sf /app/conf/nginx.conf /etc/nginx/nginx.conf && \
    ln -sf /app/conf/php-fpm.conf /etc/php/7.0/fpm/php-fpm.conf && \
    ln -sf /app/conf/php.ini /etc/php/7.0/fpm/php.ini

WORKDIR /app

# Install app
COPY conf /app/conf
COPY webroot /app/webroot
COPY --from=jsbuild /build/dist/static/js/*.js /app/webroot/modules/main/js/

RUN groupadd -r app -g 1000 && \
    useradd -u 1000 -r -g app -d /app -s /bin/bash -c "Docker image user" app

EXPOSE 80
CMD ["/app/conf/launch.sh"]
