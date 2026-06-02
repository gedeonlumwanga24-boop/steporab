FROM php:8.2-cli

# Installer les dépendances système nécessaires pour Laravel, composer et npm
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zip \
    curl \
    wget \
    gnupg \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd xml zip

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader --prefer-dist
RUN npm ci
RUN npm run build
RUN php artisan key:generate --force
RUN php artisan optimize

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
