.PHONY: dev-up dev-build dev-down dev-logs

DOCKER_COMPOSE_DEV_FILE := docker-compose.dev.yaml

dev-up:
	@echo "🚀 Starting dev environment..."
	docker compose -f $(DOCKER_COMPOSE_DEV_FILE) up --build -d

dev-build:
	@echo "🏗️ Building dev images..."
	docker compose -f $(DOCKER_COMPOSE_DEV_FILE) build

dev-down:
	@echo "🛑 Stopping dev environment..."
	docker compose -f $(DOCKER_COMPOSE_DEV_FILE) down -v --remove-orphans

dev-logs:
	docker compose -f $(DOCKER_COMPOSE_DEV_FILE) logs -f