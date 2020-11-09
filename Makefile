test:
	./vendor/bin/phpunit

deploy:
	git pull origin master
	composer install
	php artisan migrate
