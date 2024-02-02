default: docker-make-init

docker-make-init: docker-start
	docker-compose exec webserver make init

init: apt get-phpcpd get-phpdocumentor composer-update qa phpunit php-test

docker-start:
	docker-compose up -d
	@echo "you can now open the browser at: http://localhost:8189/"
	@echo "to shutdown docker run: make stop"

docker-enter:
	docker-compose exec webserver bash

docker-stop:
	docker-compose down

docker-make-qa: docker-start
	docker-compose exec webserver make qa

qa: rector phpcpd phpmd phpstan psalm phan

get-phpcpd:
	wget https://phar.phpunit.de/phpcpd.phar -nc -O ./phpcpd.phar || true
	chmod +x ./phpcpd.phar

get-phpdocumentor:
	wget https://phpdoc.org/phpDocumentor.phar -nc -O ./phpdoc.phar || true
	chmod +x ./phpdoc.phar

rector:
	./vendor/bin/rector -n

phpcpd:
	./phpcpd.phar src

phpmd:
	./vendor/bin/phpmd src text cleancode,codesize,design,unusedcode,controversial

phpstan:
	./vendor/bin/phpstan

psalm:
	./vendor/bin/psalm --config=psalm.xml

phan:
	PHAN_ALLOW_XDEBUG=1 ./vendor/bin/phan --allow-polyfill-parser -m verbose

docker-php-test: docker-start
	docker-compose exec webserver make php-test

php-test:
	php test/test.php

docker-make-composer-update: docker-start
	docker-compose exec webserver make composer

composer-update:
	composer update

normalize:
	composer normalize

docker-phpinsights: docker-start
	docker-compose exec webserver make phpinsights

phpinsights:
	./vendor/bin/phpinsights -cphpinsights.php -vvv

docker-pdepend: docker-start
	docker-compose exec webserver make pdepend

pdepend:
	./vendor/bin/pdepend \
		--dependency-xml=pdepend/dependency.xml \
		--jdepend-chart=pdepend/jdepend.svg \
		--jdepend-xml=pdepend/jdepend.xml \
        --overview-pyramid=pdepend/pyramid.svg \
		--summary-xml=pdepend/summary.xml \
		--debug \
		src
#		--coverage-report=pdepend/coverage.report \

docker-phpdoc: docker-start
	docker-compose exec webserver make phpdoc

phpdoc:
	./phpdoc.phar --target=documentation --directory=src --cache-folder=documentation/cache

docker-phpunit: docker-start
	docker-compose exec webserver make phpunit

phpunit:
	./vendor/bin/phpunit phpunit-tests

docker-apt:
	docker-compose exec webserver make apt

apt:
	apt update
	apt install unzip
	apt install wget

