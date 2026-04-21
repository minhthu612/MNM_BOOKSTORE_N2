FROM richarvey/nginx-php-fpm:latest

# Copy toàn bộ code vào server
COPY . .

# Cấu hình cho Laravel
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV run_scripts 1

# Cài đặt các thư viện cần thiết
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho các folder storage và bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache