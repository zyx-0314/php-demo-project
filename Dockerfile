FROM php:8.3.21-fpm-alpine3.20

ENV NODE_ENV=development

# Create a non-root user early, so extensions install as root then switch back
RUN addgroup -S developer && adduser -S yourUsernameHere -G developer

WORKDIR /var/www/html

# Install build deps for GD, libzip, Postgres, etc.
RUN apk add --no-cache \
    git \
    unzip \
    autoconf \
    make \
    g++ \
    icu-dev \
    libzip-dev \
    zlib-dev \
    postgresql-dev \
    libpq \
    # GD dependencies:
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Configure & install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && docker-php-ext-install \
    gd \
    pgsql \
    pdo_pgsql \
    intl \
    zip

# If you still need mongodb
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/local/bin/composer

# Copy and install app dependencies
COPY . /var/www/html/
USER yourUsernameHere
RUN composer install --no-interaction --prefer-dist

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "router.php"]
