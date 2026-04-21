FROM richarvey/nginx-php-fpm:latest

# ÉP NÓ DÙNG PHP 8.3
ENV PHP_VERSION 8.3
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

# Cấu hình đường dẫn cho Laravel
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV run_scripts 1

# Cài đặt thư viện (thêm lệnh update để nó nhận diện môi trường mới)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Cấp quyền
RUN chmod -R 775 storage bootstrap/cache