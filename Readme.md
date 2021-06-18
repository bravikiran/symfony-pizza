# 🐳 Docker + PHP 7.4 + Symfony 5 + Swagger API

## Description

This is a complete stack for running Symfony 5 into Docker containers using docker-compose tool.

It is composed by 3 containers:

- `php`, the PHP-FPM container with the 7.4 PHPversion.
- `mysql` database **mysql:5.7** image.
- `phpmyadmin` **phpmyadmin/phpmyadmin** image.

## Installation

1. 😀 Clone this rep.

2. Run `docker-compose up --build`

3. The 3 containers are deployed: 

```
Creating symfony   ... done
Creating mysql ... done
Creating phpmyadmin ... done
```

4. Use this value for the Symfony_URL environment variable of Symfony:

```
localhost:8000
```
5. Use this value for the phpmyadmin environment variable of Phpmyadmin:

```
localhost:8183
```

6. After docker compose up open another terminal and enter docker container with below cmd

```
docker exec -it symfony \bin\bash
```

7. Run cmd composer update

```
composer update
```


8. Migrations

```
php bin/console doctrine:migrations:migrate
```

9. Api Doc

```
Before doing CRUD operation, run Migrations. 
http://localhost:8000/api/doc
```

10. ETC

```
composer require laminas/laminas-zendframework-bridge
```





 
