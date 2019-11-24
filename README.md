# Lykkejul

## Development

```sh
docker-compose up --detach
docker-compose exec phpfpm composer install
docker-compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction

open http://$(docker-compose port nginx 80)
```

## Building assets

```sh
docker run -v ${PWD}:/app itkdev/yarn:latest install
docker run -v ${PWD}:/app itkdev/yarn:latest build
```

## Administration

Run

```sh
docker-compose exec phpfpm bin/console security:encode-password
```

to encode the admin password and set it in `.env.local`:

```
ADMIN_PASSWORD='$argon2id…'
```

Open the admin interface at
[http://lykkejul.local.itkdev.dk/admin/](http://lykkejul.local.itkdev.dk/admin/).
