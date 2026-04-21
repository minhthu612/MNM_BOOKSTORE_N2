# Sử dụng bản PHP 8.3 mới nhất để khớp với Laravel 13
FROM richarvey/nginx-php-fpm:php83-latest

# Cho phép chạy Composer với quyền cao nhất để không bị chặn
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

# Cấu hình đường dẫn cho Laravel
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV run_scripts 1

# Cài đặt thư viện và bỏ qua kiểm tra môi trường khắt khe
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Cấp quyền cho các folder quan trọng
RUN chmod -R 775 storage bootstrap/cache