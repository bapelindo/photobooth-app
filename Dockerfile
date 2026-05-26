FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libmagickwand-dev \
    imagemagick \
    mariadb-client \
    supervisor \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip

# Install Imagick manually from Github (Bypassing PECL)
RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/refs/tags/3.7.0.tar.gz \
    && tar -xvzf /tmp/imagick.tar.gz -C /tmp \
    && cd /tmp/imagick-3.7.0 \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable imagick \
    && rm -rf /tmp/imagick*
# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to use the PORT environment variable
ENV PORT=8080
RUN sed -i "s/Listen 80/Listen \${PORT}/g" /etc/apache2/ports.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache
COPY docker/apache/photobooth.conf /etc/apache2/sites-available/000-default.conf

# Configure Supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure PHP
COPY docker/php.ini /usr/local/etc/php/conf.d/photobooth.ini

# Expose port
EXPOSE 8080

# Entrypoint script (will be created next)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
