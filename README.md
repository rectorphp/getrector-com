# [getrector.org](https://getrector.org) Website

## Run via docker

```bash
docker-compose up
```

Voilá!

Web: [localhost:8080](http://localhost:8080)
Adminer: [localhost:8081](http://localhost:8081) (host: `mysql`, user: `root`, pass: `root`)

MySQL is published to localhost on port 3307.

### Customization

- Use `.env.local` to customize environment variables.
- Use `docker-compose.override.yml` to customize Docker setup.

To change published MySQL port use:
```bash
GETRECTOR_ORG_MYSQL_PORT=33306 docker-compose up
```


### Troubleshooting

Sometimes, you might have outdated Docker images locally, to update, please run:

```bash
bin/pull-docker-images.sh
```
