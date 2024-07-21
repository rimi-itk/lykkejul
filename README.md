# Lykkejul

## Development

``` shell name=development
docker compose pull
docker compose up --detach
docker compose exec phpfpm composer install
docker compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
open "http://$(docker compose port nginx 8080)"
```

## Production

``` shell
docker compose --env-file .env.docker.local --file docker-compose.server.yml pull

# Build assets
docker compose run --rm node npm install
docker compose run --rm node npm run build

# Start the show
docker compose --env-file .env.docker.local --file docker-compose.server.yml up --detach
docker compose --env-file .env.docker.local --file docker-compose.server.yml exec phpfpm composer install --no-dev
docker compose --env-file .env.docker.local --file docker-compose.server.yml exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
```

## Building assets

``` shell name=assets-build
docker compose run --rm node npm install
docker compose run --rm node npm run build
```

## Administration

Run

``` shell
docker compose exec phpfpm bin/console security:hash-password
```

to encode the admin password and set it in `.env.local`:

```dotenv
ADMIN_PASSWORD='$2y$1…'
```

Open the admin interface:

``` shell
open "http://$(docker compose port nginx 8080)/admin"
```

## Create players

``` shell
docker compose exec phpfpm bin/console app:player:create --help
```

``` shell
docker compose exec phpfpm bin/console app:player:create {1..90}
```

## Coding standards

``` shell
task dev:coding-standards
```

## Code analysis

``` shell
task dev:code-analysis:php
```
