<h1>Cloud Storage app</h1>
<p>This app storing users files with max disk space by an user 100mb</p>

<h2>Steps to deploy cloud-storage app</h2>
Make sure that you have install and enabled related extensions: 
<ul>
    <li>PHP >= 8.0</li>
    <li>BCMath PHP Extension</li>
    <li>Ctype PHP Extension</li>
    <li>cURL PHP Extension</li>
    <li>DOM PHP Extension</li>
    <li>Fileinfo PHP Extension</li>
    <li>JSON PHP Extension</li>
    <li>Mbstring PHP Extension</li>
    <li>OpenSSL PHP Extension</li>
    <li>PCRE PHP Extension</li>
    <li>PDO PHP Extension</li>
    <li>Tokenizer PHP Extension</li>
    <li>XML PHP Extension</li>
    </ul>
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
<li>./vendor/bin/sail npm install</li>
<li>./vendor/bin/sail npm run build</li>
    </ul>

