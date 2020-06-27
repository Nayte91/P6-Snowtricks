How to install :
* Create a carbon copy of ".env" named ".env.local".
* Modify your new ".env.local" in order to set your DB & SMTP.
    * DB : I suggest you to uncomment the sqlite line.
    * SMTP : If you don't have a proper one, you can't use the "retrieve password" functions, that's all.
* Run a "php bin/console doctrine:database:create" command.
* Run a "php bin/console doctrine:schema:create" command.
* Run a "php bin/console doctrine:fixtures:load" command.

Enjoy by launching your PHP server !

Few choices for newbies :
* Have an installed server on your computer (try laragon)
* with symfony CLI, run a "symfony serve -d" command.
* With only PHP, run a "php -S 127.0.0.1:8000 -t public/" command.

Julien "Nayte" Robic.