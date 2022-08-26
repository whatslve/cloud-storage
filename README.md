<h1>Cloud Storage app</h1>
<p>This app storing users files with max disk space by an user 100mb</p>

<h2>Steps to deploy cloud-storage app</h2>
<ul>
 <li>git clone https://github.com/whatslve/cloud-storage.git --branch master</li>
<li>cd cloud-storage</li>
<li>composer update && composer install</li>
<li>Create .env file with credentials and additional constants:</li> 
    <ul>
        <li>ALLOWED_FILE_SIZE=20971520</li>
        <li>MAX_DISK_SPACE=104857600</li>
    </ul>
<li>./vendor/bin/sail up</li>
<li>./vendor/bin/sail php artisan migrate</li>
    </ul>

