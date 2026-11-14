FROM php:8.3-apache
RUN apt-get update && apt-get install -y --no-install-recommends libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
ENV APACHE_DOCUMENT_ROOT=/var/www/html
ENV PORT=10000
WORKDIR /var/www/html
COPY . /var/www/html
RUN sed -i 's/^Listen 80$/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's#<VirtualHost \\*:80>#<VirtualHost *:${PORT}>#' /etc/apache2/sites-available/000-default.conf \
    && mkdir -p storage && chown -R www-data:www-data storage \
    && printf '<Directory /var/www/html>\nAllowOverride All\nRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/minishop.conf \
    && a2enconf minishop
EXPOSE 10000
