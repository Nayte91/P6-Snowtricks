#Project n°6 : Snowtricks
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ea93bebb89764defabd3a12993a2b1f0)](https://www.codacy.com/manual/Nayte91/P6-snowtricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Nayte91/P6-snowtricks&amp;utm_campaign=Badge_Grade)
##How to install
1. Start by composer to populate vendor/.
```bash
composer install
```
2. Create a carbon copy of ".env" named ".env.local".
```bash
cp .env .env.local
```
3. Modify your new ".env.local" in order to set your DB & SMTP.
    * DB : I suggest you to uncomment the sqlite line.
    * SMTP : If you don't have a proper one, you can't use the "retrieve password" functions, that's all.
4. Create database easily.
```bash
php bin/console doctrine:database:create
```
5. Create Structure on database easily.
```bash
php bin/console doctrine:schema:create
```
6. Import figures' dataset.
```bash
php bin/console doctrine:fixtures:load
```
7. This install CKeditor to help you format your text on edition.
```bash
php bin/console ckeditor:install
```
8. And this import the last part of js.
```bash
php bin/console assets:install public
```

Enjoy by launching your PHP server !

##Few choices for serving page
* Have an installed server on your computer (try laragon)
* with symfony CLI, run a "symfony serve -d" command.
* With raw PHP, run a "php -S 127.0.0.1:8000 -t public/" command.

##To use the website ?

Simply create an account with "sign-in" menu, or use the built-in account :
* username : usertest
* password : azerty

Julien "Nayte" Robic.