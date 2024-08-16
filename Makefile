SHELL := /bin/bash
APP_ENV ?= dev
DOCKER_COMPOSE_CMD ?= docker compose
EXEC_COMMAND ?= ${DOCKER_COMPOSE_CMD} exec application

start: create_volumes build up
build:
	${DOCKER_COMPOSE_CMD} build
create_volumes:
	docker volume create --name=recruitment_task-db || true
up:
	${DOCKER_COMPOSE_CMD} up -d
down:
	${DOCKER_COMPOSE_CMD}  down
bash:
	${EXEC_COMMAND} bash
create_database:
	${EXEC_COMMAND} bin/console do:database:create --if-not-exists
drop_database:
	${EXEC_COMMAND} bin/console doctrine:database:drop --env=${APP_ENV} --force --if-exists
phpunit:
	${EXEC_COMMAND} vendor/bin/phpunit tests
test: phpunit