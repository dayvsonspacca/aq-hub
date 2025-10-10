FROM nginx:alpine

RUN rm /etc/nginx/conf.d/default.conf

RUN apt-get install -y wget

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

CMD ["nginx", "-g", "daemon off;"]
