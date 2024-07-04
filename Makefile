.PHONY: controller

.DEFAULT_GOAL = help ## Permet d'afficher la commande help quand on tape uniquement make
CONTROLLER_NAME=

## —— Help ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

controller:
	php bin/console make:controller
