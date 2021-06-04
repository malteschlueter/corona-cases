.PHONY: help
help: ## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: init
init: ## Install composer dependencies
	composer install
	composer install --working-dir=dev-ops/ci

.PHONY: update
update: ## Update composer dependencies
	composer update
	composer update --working-dir=dev-ops/ci

.PHONY: cs-fixer
cs-fixer: ## Run php-cs-fixer
	php dev-ops/ci/vendor/bin/php-cs-fixer fix  --config=dev-ops/ci/config/.php-cs-fixer.dist.php

.PHONY: stan
stan: ## Run phpstan
	php dev-ops/ci/vendor/bin/phpstan analyse --configuration=dev-ops/ci/config/phpstan.neon.dist

.PHONY: unit
unit: ## Run phpunit
	php bin/phpunit --configuration dev-ops/ci/config/phpunit.xml.dist
