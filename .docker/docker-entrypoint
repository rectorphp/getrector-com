#!/bin/bash
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- apache2-foreground "$@"
fi

if [ "$1" = 'apache2-foreground' ] || [ "$1" = 'bin/console' ] || [ "$1" = 'php' ] || [ "$1" = 'composer' ]; then
	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-interaction -o --ignore-platform-req php
	fi

    php bin/console assets:install
    php bin/console cache:clear

    ## Check if variable DATABASE_HOST is set, if yes, we have database
    if [[ -v DATABASE_HOST ]]; then
        ## Wait until database connection is ready
        until mysql -u $DATABASE_USER -h $DATABASE_HOST --password="$DATABASE_PASSWORD" -e "" ; do
            >&2 echo "Waiting for database service to start."
            sleep 3
        done

        ## Update DB
        php bin/console doctrine:schema:update --dump-sql --force
    fi

	# Permissions hack because setfacl does not work on Mac and Windows
	chown -R www-data var
fi

exec "$@"
