# [getrector.org](https://getrector.org) Website

## Run via docker

```bash
docker-compose up
```

If you haven't checked out this repository in `/var/www/getrector.org`
then you will need to overwrite the `HOST_DEMO_DIR` in `.env.local` with `$PWD/var/demo`:
```dotenv
HOST_DEMO_DIR="/Users/username/getrector.org/var/demo"
```

Voilá!

Web: [localhost:8080](http://localhost:8080)
Adminer: [localhost:8081](http://localhost:8081) (host: `mysql`, user: `root`, pass: `root`)
Mailhog: [localhost:8025](http://localhost:8025)


MySQL is published to localhost on port 3307.

### Customization

- Use `.env.local` to customize environment variables.
- Use `docker-compose.override.yml` to customize Docker setup.

To change published MySQL port use:
```bash
GETRECTOR_ORG_MYSQL_PORT=33306 docker-compose up
```

### Running with Xdebug
If you want to use xdebug, here is example `docker-compose.override.yml` that will enable xdebug (do not forget to change ip address):
```
version: "3.7"
services:
    web:
        build:
            context: .
            target: xdebug
            dockerfile: Dockerfile
        environment:
            XDEBUG_CONFIG: "client_host=172.16.165.1"
            PHP_IDE_CONFIG: "serverName=getrector_org"

```

Then re-build your image so it contains xdebug extension:
```
docker-compose down
docker-compose up --build
```

### Troubleshooting

Sometimes, you might have outdated Docker images locally, to update, please run:

```bash
bin/pull-docker-images.sh
```
