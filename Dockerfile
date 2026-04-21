# Đổi cái này để lấy PHP 8.3 (bản mới nhất của image này)
FROM richarvey/nginx-php-fpm:php83-latest

# Cho phép chạy Composer với quyền root để tránh cái cảnh báo lúc nãy
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

# Cấu hình cho Laravel
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV run_scripts 1

# Cài đặt các thư viện (đã thêm lệnh bỏ qua kiểm tra platform cho chắc)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Cấp quyền
RUN chmod -R 775 storage bootstrap/cache