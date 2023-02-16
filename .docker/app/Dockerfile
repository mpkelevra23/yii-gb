FROM php:7.4-fpm

ARG context
#RUN if [ "x$context" = "x" ] ; then echo Argument not provided ; else echo Argument is $arg ; fi

# Performance optimization - see https://gist.github.com/jpetazzo/6127116
# this forces dpkg not to call sync() after package extraction and speeds up install
RUN echo "force-unsafe-io" > /etc/dpkg/dpkg.cfg.d/02apt-speedup
# we don't need an apt cache in a container
RUN echo "Acquire::http {No-Cache=True;};" > /etc/apt/apt.conf.d/no-cache

RUN apt-get update && apt-get install -y postgresql-client libpq-dev libmcrypt-dev &&  \
    docker-php-ext-install pdo_pgsql bcmath sockets && \
    docker-php-ext-enable pdo_pgsql bcmath &&  \
    pecl install mcrypt-1.0.4 xdebug-3.1.6 &&  \
    docker-php-ext-enable mcrypt xdebug

RUN apt-get install -y \
    libpng-dev  \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev && \
    docker-php-ext-configure gd && \
    docker-php-ext-install gd

RUN apt-get update && \
    apt-get install -y --allow-unauthenticated \
        git \
        ssh \
        htop \
        mc \
        nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*; exit 0

RUN curl -o /bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
RUN chmod +x /bin/wait-for-it.sh

RUN usermod -u 1000 www-data

RUN mkdir /composer && chown -R www-data:www-data /composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer clear-cache && composer self-update

RUN chown www-data:www-data .

# Initialize application
WORKDIR .

#COPY --chown=www-data . /app

# Install composer && global asset plugin (Yii 2.0 requirement)
ENV COMPOSER_HOME /composer
ENV PATH /composer/vendor/bin:$PATH

#RUN if [ "$context" = "dev" ] ; \
#    then \
#        echo Skip composer on the dev context ; \
#    else \
#        cd /app \
#         && composer install --no-dev \
#         && chown -R www-data:www-data vendor \
#    ; fi

CMD ["php-fpm"]
