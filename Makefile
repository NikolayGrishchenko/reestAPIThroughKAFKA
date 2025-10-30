run:
	docker run -d -p 80:80 -v /Users/nikolaygrishchenko/Downloads/localhost:/var/www/html 12e3a12d43d9

runOneService:
	docker compose up php_apache --build --force-recreate -d



