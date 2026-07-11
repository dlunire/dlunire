.PHONY: server test

server:
	php -S localhost:3000 -t public/

test:
	composer test
