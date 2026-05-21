FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git curl zip unzip libzip-dev libpng-dev \
  libonig-dev libxml2-dev libicu-dev nodejs npm \
  && docker-php-ext-install \
  pdo pdo_mysql mbstring zip gd intl bcmath \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apt-get clean

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install & build frontend
RUN npm install && npm run build

# Cache Laravel config
RUN php artisan config:cache \
  && php artisan route:cache \
  && php artisan view:cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]