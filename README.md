# Lykkejul

## Development

```sh
docker-compose up --detach
docker-compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction

open http://$(docker-compose port nginx 80)
```

### Loading fixtures

```sh
docker-compose exec phpfpm bin/console hautelook:fixtures:load
```

## Building assets

```sh
docker run -v ${PWD}:/app itkdev/yarn:latest install
docker run -v ${PWD}:/app itkdev/yarn:latest build
```
