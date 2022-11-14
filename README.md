## Setup
Prerequisites: git, composer, docker, docker-compose 

1. git clone github.com/ws396/reporter
2. composer install
3. cp .env.example .env
4. ./vendor/bin/sail up
5. ./vendor/bin/sail artisan key:generate
6. ./vendor/bin/sail artisan migrate --seed

After setting up you can go to http://localhost and login with these credentials as admin:
```
email: example@example.com
password: admin
```