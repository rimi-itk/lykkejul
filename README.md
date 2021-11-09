# Lykkejul

## Development

```sh
docker-compose up --detach
composer install
bin/console doctrine:migrations:migrate --no-interaction

symfony local:server:start
```

## Production

```sh
docker-compose --env-file .env.docker.local -f docker-compose.server.yml up --detach
docker-compose --env-file .env.docker.local -f docker-compose.server.yml exec phpfpm composer install --no-dev --classmap-authoritative
docker-compose --env-file .env.docker.local -f docker-compose.server.yml exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
```

## Building assets

```sh
docker run --volume ${PWD}:/app --workdir /app node:16 yarn install
docker run --volume ${PWD}:/app --workdir /app node:16 yarn build
```

## Administration

Run

```sh
bin/console security:hash-password
```

to encode the admin password and set it in `.env.local`:

```
ADMIN_PASSWORD='$argon2id…'
```

Open the admin interface at
[https://127.0.0.1:8000/admin](https://127.0.0.1:8000/admin).

## Create players

```sh
symfony console app:player:create --help
```

```sh
symfony console app:player:create {1..90}
```
