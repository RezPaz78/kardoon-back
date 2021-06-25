# Kardoon Backend

## How to run

1. Install composer on your system.
2. Go to project directory.
3. Run `composer install` to install packages.

The project runs on docker, so you need docker installed on your os. After that, just run `./vendor/bin/sail up -d` to run containers. Now the backend ready on **localhost** port **80**.

### Sail Commands

| Command                 | Description                                            |
| ----------------------- | ------------------------------------------------------ |
| sail up -d              | Run server and containers                              |
| sail migrate:refresh    | Refreshing Database tables                             |
| sail artisan route:list | Show route table                                       |
| sail down               | Shutdown server and containers                         |
| sail down -v            | Shutdown server and containers and also remove volumes |

> All `sail` keyword in the table actually is `./vendor/bin/sail`.

The project uses postgres as database engine and can manage it with pgadmin. The pgadmin runs on `localhost:5051`. For credential enter `pgadmin@pgadmin.org` as email and `admin` as password.
