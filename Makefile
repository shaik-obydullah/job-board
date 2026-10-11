# Job Board - Docker commands

DOCKER_COMPOSE = docker compose
PHP = $(DOCKER_COMPOSE) exec php-fpm php
COMPOSER = $(DOCKER_COMPOSE) exec php-fpm composer
NPM = $(DOCKER_COMPOSE) exec php-fpm npm

.PHONY: help up down build start stop restart logs ps shell artisan composer npm-build npm-dev migrate seed fresh test

help:
	@echo "Available commands:"
	@echo "  make up          - Start all containers (detached)"
	@echo "  make down        - Stop all containers"
	@echo "  make build       - Rebuild images"
	@echo "  make start       - Start containers"
	@echo "  make stop        - Stop containers"
	@echo "  make restart     - Restart containers"
	@echo "  make logs        - Tail container logs"
	@echo "  make ps          - Show container status"
	@echo "  make shell       - Open bash in PHP container"
	@echo "  make artisan     - Run artisan command (make artisan COMMAND='migrate')"
	@echo "  make composer    - Run composer command"
	@echo "  make npm-build   - Build frontend assets"
	@echo "  make npm-dev     - Run Vite dev server"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Run database seeders"
	@echo "  make fresh       - Refresh migrations (drop all tables)"
	@echo "  make test        - Run PHPUnit tests"

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

build:
	$(DOCKER_COMPOSE) build

start:
	$(DOCKER_COMPOSE) start

stop:
	$(DOCKER_COMPOSE) stop

restart:
	$(DOCKER_COMPOSE) restart

logs:
	$(DOCKER_COMPOSE) logs -f

ps:
	$(DOCKER_COMPOSE) ps

shell:
	$(DOCKER_COMPOSE) exec php-fpm sh

artisan:
	$(PHP) artisan $(COMMAND)

composer:
	$(COMPOSER) $(COMMAND)

npm-build:
	$(NPM) run build

npm-dev:
	$(NPM) run dev

migrate:
	$(PHP) artisan migrate

seed:
	$(PHP) artisan db:seed

fresh:
	$(PHP) artisan migrate:fresh --seed

test:
	$(PHP) artisan test
