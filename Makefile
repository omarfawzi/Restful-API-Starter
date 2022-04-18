up:
        if [ -a .env ]; then cp .env.example .env; fi;
	@docker-compose up -d

stop:
	@docker-compose down

ps:
	@docker-compose ps

install:
	@docker-compose up -d
	@docker-compose run --rm app composer install

bash:
	docker-compose run --rm app bash

db-bash:
	docker exec -it db bash

migrate:
	@docker-compose run --rm app bash -c "./artisan migrate; exit $?"

rebuild:
	@docker-compose down -t 0 -v --remove-orphans
	@docker-compose build --pull

install-package:
	@docker-compose up -d
	@docker-compose run --rm app composer require $(name)

swagger-ui:
	docker run -d --rm \
		--platform linux/amd64 \
		--name swagger-ui \
		-p 8081:8080 \
		-v `pwd`/storage/app/schema:/mnt \
		-e SWAGGER_JSON=/mnt/openapi.yaml \
		swaggerapi/swagger-ui

stop-swagger-ui:
	docker stop swagger-ui
