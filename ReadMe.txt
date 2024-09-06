DO THIS FIRST: https://pterodactyl.io/community/customization/panel.html


| cd /var/www/pterodactyl
| php artisan migrate
| > yes
| yarn add esbuild-loader
| yarn add monaco-editor @monaco-editor/react
| yarn add sanitize-html@2.7.3 @types/sanitize-html@2.6.2
| php artisan optimize && php artisan migrate --force && chown -R www-data:www-data *
| php artisan migrate --force --seed
| yarn build:production
| composer require laravel/socialite
| composer require martinbean/socialite-discord-provider:^1.2
| composer require austinb/gameq:~3.0
| composer require xpaw/php-source-query-class
| chmod -R 755 storage/* bootstrap/cache/
| chown -R www-data:www-data *
| composer require wohali/oauth2-discord-new
| sudo apt install rsync
| php artisan optimize
| php artisan optimize:clear
| php artisan view:clear && php artisan cache:clear && php artisan route:clear && php artisan migrate --force && chown -R www-data:www-data *
| bash ./install-ainx.sh
| ainx install versionchanger.ainx  |  ainx upgrade versionchanger.ainx  |  ainx remove versionchanger
| ainx install databaseimportexport.ainx or ainx upgrade databaseimportexport.ainx
| chmod +x ./install-*.sh
| ./install-versionchanger.sh or ./remove-versionchanger.sh



Create First User

| mysql
| USE panel;
| UPDATE `users` SET `role` = 1 WHERE `username` = 'vinnij';
| quit


Add to .env

GOOGLE_CLIENT_ID=651204603875-q0onu0c0gschnlrc5al84orfjm2vbqa7.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
DISCORD_CLIENT_ID=1257086533488349184
DISCORD_CLIENT_SECRET=xT_xJhWiYloMVngDN7k3VqG__a3pUxoJ

CURSEFORGE_API_KEY="$2a$10$J4LWOjHyGFszrc1baRf0CuY2AtB/1JKZGjcOIcQoe9t4NkobaLzS."