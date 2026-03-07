test:
	./vendor/bin/pest

test-coverage:
	./vendor/bin/pest --coverage -p

deploy:
	git pull origin master
	composer install
	php artisan migrate
