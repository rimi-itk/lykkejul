# Lykkejul

## Development

```sh
docker compose up --detach
docker compose exec phpfpm composer install
docker compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
open "http://$(docker compose port nginx 8080)"
```

## Production

```sh
# Build assets
docker compose --env-file .env.docker.local --file docker-compose.server.yml run node yarn --cwd /app install
docker compose --env-file .env.docker.local --file docker-compose.server.yml run node yarn --cwd /app build
# Start the show
docker compose --env-file .env.docker.local --file docker-compose.server.yml up --detach
docker compose --env-file .env.docker.local --file docker-compose.server.yml exec --user deploy phpfpm composer install --no-dev --classmap-authoritative
docker compose --env-file .env.docker.local --file docker-compose.server.yml exec --user deploy phpfpm bin/console doctrine:migrations:migrate --no-interaction
# Edit .env.local
docker compose --env-file .env.docker.local --file docker-compose.server.yml exec --user deploy phpfpm composer dump-env
```

## Building assets

```sh
docker compose run node yarn --cwd /app install
docker compose run node yarn --cwd /app build
```

## Administration

Run

```sh
docker compose exec phpfpm bin/console security:hash-password
```

to encode the admin password and set it in `.env.local`:

```dotenv
ADMIN_PASSWORD='$2y$1…'
```

Open the admin interface:

```sh
open "http://$(docker compose port nginx 8080)/admin"
```

## Create players

```sh
docker compose exec phpfpm bin/console app:player:create --help
```

```sh
docker compose exec phpfpm bin/console app:player:create {1..90}
```
