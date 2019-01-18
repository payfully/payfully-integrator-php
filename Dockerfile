FROM php:7.2-cli
COPY . /usr/src/payfully-integrator
WORKDIR /usr/src/payfully-integrator
CMD [ "php", "./payfully-integrator.php" ]