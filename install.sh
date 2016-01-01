#!/bin/bash

clear
echo "Выберите дествие :"
echo "1 - install"
echo "2 - update"
echo "3 - quit"

read Keypress

case "$Keypress" in
1) echo "install start..."
    npm install
    composer install
    ./node_modules/.bin/bower install
    ./node_modules/.bin/gulp
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load -n
;;
2) echo "update start..."
    composer update
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load -n
;;
3) exit 0
;;
esac

exit 0
