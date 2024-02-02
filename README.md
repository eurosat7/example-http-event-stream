# Example Http Event Stream

An example on how to use a http event stream.

## Pre requisites

Have `make`, `docker` and `docker-compose` installed.

### Build and start the docker instance

```sh
    make docker-start
```

### Enter the docker instance

If you want to add or build packages with `composer` or `npm` or others 
you must do it from __inside__ the docker instance.

(But all `make docker-` commands must be run from outside the docker instance.)

To enter the docker instance and get a command prompt (aka terminal, shell, bash):

```sh
    make docker-enter
```

### Exiting the docker instance

```sh
    exit
```

That's it. :)

### Build all packages and run the code

To install required packages, download additional developer tools and
get the autoloader build you have to run this once:

```sh
    make docker-make-init
```

### stopping the docker instance

When you do not need the docker instance to be running you can stop it with: 

```sh
    make docker-stop
```

### Check Code Functionality

```sh
    make docker-php-test
```

It just runs `php test/test.php` in a docker instance.

### Check Code Quality

```sh
    make docker-make-qa
```

Will run `rector`, `phpcpd`, `phpmd`, `phpstan`,  `psalm` and `phan`.

#### Do more with the code

##### pdepend

Run some metrics and find too complex code.
Good to find places you should refactor.

```sh
    make docker-pdepend
```

##### phpinsights

phpinsights will do similar things than pdepend but gives you scores.
This helps you to fine tune your code.

```sh
    make docker-phpinsights
```

##### phpdoc

Some people like to look at the code with a browser. 
phpdoc will create some nice pages to browse through.

```sh
    make docker-phpdoc
```
