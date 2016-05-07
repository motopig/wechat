#!/bin/bash


star=(bat-man spider-man super-man hulk member iot iron-man thor)

if [ $1 ]
then
     if [[ "${star[@]/$1/}" != "${star[@]}" ]]
     then
        php artisan atlas:asset hell/"$1"
        php artisan atlas:dump hell/"$1"
        php artisan dump-autoload
     else
        echo 'missing star name, use correct star name in first params!'
     fi
else
    php artisan atlas:asset hell/bat-man
    php artisan atlas:asset hell/spider-man
    php artisan atlas:asset hell/super-man
    php artisan atlas:asset hell/hulk
    php artisan atlas:asset hell/member
    php artisan atlas:asset hell/iot
    php artisan atlas:asset hell/iron-man
    php artisan atlas:asset hell/thor

    php artisan atlas:dump hell/bat-man
    php artisan atlas:dump hell/spider-man
    php artisan atlas:dump hell/super-man
    php artisan atlas:dump hell/hulk
    php artisan atlas:dump hell/member
    php artisan atlas:dump hell/iot
    php artisan atlas:dump hell/iron-man
    php artisan atlas:dump hell/thor

    php artisan dump-autoload

fi
