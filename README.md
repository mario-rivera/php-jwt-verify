# PHP CLI verify JWT (RSA256)

## Requirements

* docker-compose (version 3.7)
* docker

## Install composer dependencies

*(user id prefix is to avoid running as root)*
```bash
$ CURRENT_UID=$(id -u):$(id -g) docker-compose run --rm composer
```

## Run the script

*(user id prefix is to avoid running as root)*
```bash
$ CURRENT_UID=$(id -u):$(id -g) docker-compose run --rm php
```

or
```bash
$ docker run --rm -it \
--volume $PWD:/app \
-w /app \
mrivera/php-jwtverify:7.2 php verify.php
```

## License
[MIT](https://choosealicense.com/licenses/mit/)