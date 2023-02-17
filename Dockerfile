FROM php:7.4-cli
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && apt-get install -y git wget \
	&& apt-get clean

