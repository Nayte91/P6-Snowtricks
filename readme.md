#P6 : Snowtricks
##How to install
1. Create a carbon copy of ".env" named ".env.local".
2. Modify your new ".env.local" in order to set your DB & SMTP.
    * DB : I suggest you to uncomment the sqlite line.
    * SMTP : If you don't have a proper one, you can't use the "retrieve password" functions, that's all.
3. Run a "php bin/console doctrine:database:create" command.
4. Run a "php bin/console doctrine:schema:create" command.
5. Run a "php bin/console doctrine:fixtures:load" command.

Enjoy by launching your PHP server !

##Few choices for serving page
* Have an installed server on your computer (try laragon)
* with symfony CLI, run a "symfony serve -d" command.
* With only PHP, run a "php -S 127.0.0.1:8000 -t public/" command.

Julien "Nayte" Robic.