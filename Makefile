# Supported PHP Versions
PHP_VERSIONS := 8.1 8.2 8.3 8.4 8.5

# Supported Symfony Versions
SF_VERSIONS := 6.4 7.4 8.0

# Versions (always use the lowest supported versions)
PHP_V ?= 8.1
SF_V  ?= 6.4

# Helper variable to use in `docker-compose.yaml`
PHP_V_ID := $(shell echo $(PHP_V) | tr -d .)

# This value is the root folder of the library.
# It has to correspond to the name of the image in each Dockerfile:
#
#    FROM php:8.3-cli as folder-name-83
PROJECT_NAME := $(notdir $(shell pwd))

# Executables (local)
DOCKER_COMP = PROJECT_ROOT=`pwd` PHP_V=$(PHP_V) PHP_V_ID=$(PHP_V_ID) PROJECT_NAME=$(PROJECT_NAME) docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
PHP_CONT_DEBUG = $(DOCKER_COMP) exec -e XDEBUG_MODE=debug -e XDEBUG_SESSION=1 php

# Executables
PHP_EX      = $(PHP_CONT) php
COMPOSER_EX = $(PHP_CONT) composer

# Misc
.DEFAULT_GOAL = help
.PHONY: help build start stax stop stop-v down sh composer initialize cov mut sf $(PHP_VERSIONS)

# Icons
ICON_THICK = \033[32m\xE2\x9C\x94\033[0m
ICON_CROSS = \033[31m\xE2\x9C\x96\033[0m

# -----------------------------------------------------------------------------
# Positional arguments parsing (target-aware)
# -----------------------------------------------------------------------------

TARGET := $(word 1,$(MAKECMDGOALS))
ARG1   := $(word 2,$(MAKECMDGOALS))
ARG2   := $(word 3,$(MAKECMDGOALS))

# --- start / stax: [PHP] [SF] ---
ifeq ($(filter $(TARGET),start stax),$(TARGET))

  ifneq ($(ARG1),)
    ifeq ($(filter $(ARG1),$(PHP_VERSIONS)),)
      $(error Unsupported PHP version "$(ARG1)". Supported versions are: $(PHP_VERSIONS))
    else
      PHP_V := $(ARG1)
      PHP_V_ID := $(shell echo $(PHP_V) | tr -d .)
    endif
  endif

  ifneq ($(ARG2),)
    ifeq ($(filter $(ARG2),$(SF_VERSIONS)),)
      $(error Unsupported Symfony version "$(ARG2)". Supported versions are: $(SF_VERSIONS))
    else
      SF_V := $(ARG2)
    endif
  endif

endif

# --- sf: [SF] ---
ifeq ($(TARGET),sf)
  ifneq ($(ARG1),)
    ifeq ($(filter $(ARG1),$(SF_VERSIONS)),)
      $(error Unsupported Symfony version "$(ARG1)". Supported versions are: $(SF_VERSIONS))
    else
      SF_V := $(ARG1)
    endif
  endif
endif

# Strip extra args, keep only the target
override MAKECMDGOALS := $(TARGET)

# -----------------------------------------------------------------------------
# Help
# -----------------------------------------------------------------------------

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/ '

cov: ## Opens the code coverage in the browser
	open var/coverage/coverage-html/index.html

mut: ## Opens the report of mutations in the browser
	open var/mutations.html

# -----------------------------------------------------------------------------
# Docker
# -----------------------------------------------------------------------------

start: ## Starts the containers. Ex: make start [php] [sf]
	$(MAKE) stop
	@echo ""
	@echo "Using PHP \033[32m$(PHP_V)\033[0m | Symfony \033[32m$(SF_V)\033[0m"
	@echo "Debug: \033[32mOff\033[0m"
	@echo ""
	$(DOCKER_COMP) up -d --build
	$(MAKE) sf PHP_V=$(PHP_V) SF_V=$(SF_V)

stax: ## Starts containers WITH XDEBUG. Ex: make stax [php] [sf]
	$(MAKE) stop
	@echo ""
	@echo "Using PHP \033[32m$(PHP_V)\033[0m | Symfony \033[32m$(SF_V)\033[0m (XDEBUG)"
	@echo "Debug: \033[32mOn\033[0m"
	@echo ""
	XDEBUG_MODE=debug $(DOCKER_COMP) up -d --build
	$(MAKE) sf PHP_V=$(PHP_V) SF_V=$(SF_V)

sync: ## Syncs branches and dependencies.
	git fetch
	gt sync
	gt s --stack --update-only

stafu: ## Starts containers and syncs branches and dependencies.
	${MAKE} sync
	${MAKE} start
	${MAKE} composer c='install'

stop: ## Stops all containers for all PHP versions
	for v in $(PHP_VERSIONS); do $(MAKE) stop-v PHP_V=$$v; done

down: ## Downs the docker hub
	$(DOCKER_COMP) down --remove-orphans -v

sh: ## Connects to the main container
	$(PHP_CONT) bash

build: ## Builds the Docker images
	$(DOCKER_COMP) build --pull

initialize: ## Builds and starts the containers
	$(MAKE) build PHP_V=$(PHP_V)
	$(MAKE) start PHP_V=$(PHP_V)

# -----------------------------------------------------------------------------
# Composer
# -----------------------------------------------------------------------------

composer: ## Run Composer. Pass c=... (default: install)
	$(eval c ?=install)
	$(COMPOSER_EX) $(c)

sf: ## Install Symfony version. Ex: make sf 6.4
	@set -e; \
	ORIGINAL_SYMFONY_REQUIRE="$$( $(COMPOSER_EX) config extra.symfony.require 2>/dev/null || true )"; \
	cleanup() { \
		if [ -z "$$ORIGINAL_SYMFONY_REQUIRE" ]; then \
			$(COMPOSER_EX) config --unset extra.symfony.require >/dev/null 2>&1 || true; \
		else \
			$(COMPOSER_EX) config extra.symfony.require "$$ORIGINAL_SYMFONY_REQUIRE" >/dev/null 2>&1 || true; \
		fi; \
	}; \
	trap cleanup EXIT INT TERM; \
	$(COMPOSER_EX) config extra.symfony.require "~$(SF_V)"; \
	if ! $(COMPOSER_EX) update "symfony/*" --with-all-dependencies; then \
		echo "[33m⚠️  Some tools are incompatible with PHP $(PHP_V) / Symfony $(SF_V). Environment started anyway.[0m"; \
	fi
