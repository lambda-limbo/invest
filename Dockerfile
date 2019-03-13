FROM    hhvm/hhvm-proxygen:latest
LABEL   maintainer="Rafael Campos Nunes <rafaelnunes@engineer.com>"

RUN     rm -rf /var/www
ADD     ./src /var/www/public
ADD     server.ini /etc/hhvm/

RUN     ls /var/www/public

EXPOSE  80
