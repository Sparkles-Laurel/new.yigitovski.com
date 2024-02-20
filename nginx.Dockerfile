FROM docker.io/nginx:alpine

COPY public /var/www/html/public
COPY conf/nginx.conf /etc/nginx/nginx.conf
