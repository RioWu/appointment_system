FROM registry.stuhome.com/devops/dockerepo/php-fpm:7.2-1.0.1

COPY . /app

RUN set -xe;\
    sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories;\
    apk update;\
    apk add git nodejs-npm --no-cache;\
    cd /app;\
    composer update nothing;\
    rm -rf vendor;\
    composer clear-cache;\
    composer install;\
    mkdir /app/static;\
    mkdir /build;\
    cd /build;\
    git clone https://gitlab+deploy-token-1:9nMjeDn6S5CyhQJ-zuxg@git.uestc.cn/Rocinate/appointment.git --depth 1;\
    cd appointment;\
    npm install --registry=https://registry.npm.taobao.org;\
    npm run build;\
    cp -r dist/* /app/static/;\
    cp /app/appointment.conf /etc/nginx/conf.d/appointment.conf;\
    cd /;\
    rm -rf /build;\
    apk del nodejs-npm git;


EXPOSE 80

