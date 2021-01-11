help: ## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: ## Install composer and yarn dependencies, build frontend assets
	composer install
	composer --working-dir=tools/php-cs-fixer install

update: ## Update composer and yarn dependencies
	composer update
	composer --working-dir=tools/php-cs-fixer update

cs-fix: ## Run php-cs-fixer
	PHP_CS_FIXER_IGNORE_ENV=1 php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix

phpstan: ## Run phpstan
	php vendor/bin/phpstan analyze

phpunit: ## Run phpunit with coverage
	php bin/phpunit --coverage-html=var/phpunit-coverage
