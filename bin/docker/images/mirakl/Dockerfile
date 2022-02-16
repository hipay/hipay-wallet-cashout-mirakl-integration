FROM php:7.2-apache


COPY . /var/www/html

RUN apt-get update && apt-get install -y \
	vim \
	libssl-dev \
	default-mysql-client \
    git

# pdo_mysql
RUN docker-php-ext-install pdo_mysql

# zip
RUN buildRequirements="zlib1g-dev" \
	&& apt-get update && apt-get install -y ${buildRequirements} \
	&& docker-php-ext-install zip \
	&& apt-get purge -y ${buildRequirements} \
	&& rm -rf /var/lib/apt/lists/*

# soap
RUN buildRequirements="libxml2-dev" \
	&& apt-get update && apt-get install -y ${buildRequirements} \
	&& docker-php-ext-install soap \
	&& apt-get purge -y ${buildRequirements} \
	&& rm -rf /var/lib/apt/lists/*

# Date
RUN echo "date.timezone = Europe/Paris" > /usr/local/etc/php/conf.d/date.ini

# Apache
RUN echo "DocumentRoot /var/www/html/web" >> /etc/apache2/sites-available/100-default.conf \
    && ln /etc/apache2/sites-available/100-default.conf /etc/apache2/sites-enabled/

RUN chmod 777 -Rf /var/www/html/bin/docker

ENTRYPOINT ["./bin/docker/images/mirakl/entrypoint.sh"]
