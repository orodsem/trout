php app/console doctrine:schema:drop --force
php app/console doctrine:database:create --if-not-exists
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load
Y
bin/behat features/trout.feature