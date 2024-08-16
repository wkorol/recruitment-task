SHELL := /bin/bash
APP_ENV ?= dev
DOCKER_COMPOSE_CMD ?= docker compose
EXEC_COMMAND ?= ${DOCKER_COMPOSE_CMD} exec application

start: create_volumes build load_user up
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
phpunit:
	${EXEC_COMMAND} vendor/bin/phpunit tests
load_user:
	${DOCKER_COMPOSE_CMD} exec database psql -U main -f /db-scripts/user.sql || true
test: phpunit