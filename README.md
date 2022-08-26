Steps to deploy cloud-storage app
1 - git clone https://github.com/whatslve/cloud-storage.git --branch master
2 - cd cloud-storage
3 - composer update && composer install
4 - Create .env file with credentials and additional constants: 
    ALLOWED_FILE_SIZE=20971520
    MAX_DISK_SPACE=104857600
5 - ./vendor/bin/sail up
6 - /vendor/bin/sail php artisan migrate

