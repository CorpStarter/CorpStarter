# Stage 1: Build dependencies
FROM php:8.4-apache AS builder

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . /var/www/html/

# Install PHP dependencies
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ==========================================
# Stage 2: Runtime (Image finale allégée)
# ==========================================
FROM php:8.4-apache

# Install runtime dependencies (CORRIGÉ : on retire apt-get install vide)
# OPTIMISATION : J'ai ajouté 'opcache' qui est indispensable pour les perfs PHP en prod
RUN apt-get update \
    && docker-php-ext-install pdo pdo_mysql opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set Apache document root (Très bonne pratique pour les frameworks comme Symfony/Laravel)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache modules
RUN a2enmod rewrite
# Note : a2enmod ssl est utile uniquement si vous copiez aussi vos certificats SSL dans l'image. 
# Sinon, c'est généralement Google Cloud (le Load Balancer) qui gérera le HTTPS.
RUN a2enmod ssl 

# Copy built application from builder stage
COPY --from=builder /var/www/html /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

WORKDIR /var/www/html

# On expose uniquement le port 80 (Apache par défaut). 
# Le 8080 n'est pas nécessaire à moins d'avoir modifié les ports d'Apache.
EXPOSE 80

CMD ["apache2-foreground"]