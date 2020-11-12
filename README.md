# Lykkejul

## Development

```sh
docker-compose up --detach
symfony composer install
symfony console doctrine:migrations:migrate --no-interaction

symfony local:server:start
```

## Building assets

```sh
yarn install
yarn build
```

## Administration

Run

```sh
symfony console security:encode-password
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
