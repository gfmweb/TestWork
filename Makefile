DC := docker compose

.PHONY: help up restart up-dev connect-app connect-front connect-db build-front test-front analyse pint-test pint-fix qa init

help:
	@echo "Available commands:"
	@echo "  make up            - Запустить все контейнеры"
	@echo "  make restart       - Перезапустить все контейнеры"
	@echo "  make up-dev        - Запустить контейнеры и запустить сервер разработки фронтенда"
	@echo "  make connect-app   - Открыть shell в контейнере app"
	@echo "  make connect-front - Открыть shell в контейнере front"
	@echo "  make connect-db    - Открыть shell в контейнере db"
	@echo "  make build-front   - Собрать frontend assets в контейнере front"
	@echo "  make test-front    - Запустить юнит тесты в контейнере front"
	@echo "  make analyse       - Запустить статический анализ кода в контейнере app"
	@echo "  make pint-test     - Запустить проверку стиля кода с Pint в контейнере app"
	@echo "  make pint-fix      - Автоматически исправить проблемы стиля кода с Pint в контейнере app"
	@echo "  make qa            - Запустить frontend тесты, статический анализ и проверку стиля кода"
	@echo "  make init          - Создать контейнеры без кэша, подготовить окружение, мигрировать и заполнить базу данных"

up:
	$(DC) up -d

restart:
	$(DC) restart

up-dev:
	$(DC) up -d
	$(DC) exec front npm run dev -- --host 0.0.0.0

connect-app:
	$(DC) exec app bash

connect-front:
	$(DC) exec front sh

connect-db:
	$(DC) exec db psql -U $${DB_USERNAME:-root} -d $${DB_DATABASE:-testwork}

build-front:
	$(DC) exec front npm run build

test-front:
	$(DC) exec front npm run test

analyse:
	$(DC) exec app php vendor/bin/phpstan analyse --memory-limit=1G

pint-test:
	$(DC) exec app php vendor/bin/pint --test

pint-fix:
	$(DC) exec app php vendor/bin/pint

qa: test-front analyse pint-test

init:
	$(DC) build --no-cache
	@test -f .env || cp .env.example .env
	$(DC) up -d
	$(DC) exec app composer install
	$(DC) exec front npm install
	$(DC) exec app php artisan key:generate
	$(DC) exec app php artisan migrate --seed --force
