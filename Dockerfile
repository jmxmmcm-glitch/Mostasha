# Emergency Medical System — Production image for Render
# Base: PHP 8.2 + Apache
FROM php:8.2-apache

# --- System dependencies for PostgreSQL ---
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# --- PHP extensions: PostgreSQL (pdo_pgsql, pgsql) ---
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# --- Enable Apache mod_rewrite (required by /public/.htaccess) ---
RUN a2enmod rewrite

# --- Point DocumentRoot at /public ---
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --- Allow .htaccess overrides under /var/www/html ---
RUN printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
    > /etc/apache2/conf-available/zz-app.conf \
    && a2enconf zz-app

# --- Copy the application ---
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# --- Render injects the listening port via $PORT (default 10000) ---
# Apache listens on 80 by default; we rewrite ports.conf and the vhost
# so that Apache binds to whatever $PORT Render gives us.
ENV PORT=10000
EXPOSE 10000

# Entry script: substitute $PORT into Apache config, then start in foreground
RUN printf '#!/bin/bash\nset -e\n: "${PORT:=10000}"\nsed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf\nsed -ri "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/*.conf\nexec apache2-foreground\n' > /usr/local/bin/start-apache.sh \
    && chmod +x /usr/local/bin/start-apache.sh

CMD ["/usr/local/bin/start-apache.sh"]
