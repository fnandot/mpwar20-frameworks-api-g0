all: help

##    --------------------------------------------
##    ||          LaSalle MPWAR 2021            ||
##    --------------------------------------------


.PHONY : help
help : Makefile
	@sed -n 's/^##\s//p' $<
	
SHELL := /bin/bash
ROOT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
UID=$(shell id -u)

define docker_phpcli_run
	docker-compose -f docker-compose.cli.yml run \
		--rm \
		--no-deps \
		--entrypoint=/bin/bash \
		-e HOST_USER=${UID} \
		-e TERM=xterm-256color \
		php-cli -c "$1"
endef

##    start:			(alias of start@webserver)
.PHONY : start
start:
	@docker-compose up -d

##    stop:			stops web server containers
.PHONY : stop
stop:
	@docker-compose stop

##    start@all:			starts all containers
.PHONY : start@all
start@all:
	-@docker-compose -f docker-compose.mercure.yml -f docker-compose.db.yml -f docker-compose.yml up -d

##    stop@all:			stops all containers
.PHONY : stop@all
stop@all:
	-@docker-compose -f docker-compose.db.yml -f docker-compose.yml stop

##    start@elk:			starts ELK containers
.PHONY : start@elk
start@elk:
	-@docker-compose -f docker-compose.elk.yml start

##    stop@elk:			stops ELK containers
.PHONY : start@elk
stop@elk:
	-@docker-compose -f docker-compose.elk.yml stop

##    logs:			shows all containers logs
.PHONY : logs
logs:
	@docker-compose -f docker-compose.yml logs -f -t php-fpm

##    remove:			stops all containers and delete them
.PHONY : remove
remove:
	@docker-compose -f docker-compose.db.yml -f docker-compose.yml rm -s -f

##    build:			builds all Docker images
.PHONY : build
build:
	@docker-compose -f docker-compose.yml -f docker-compose.cli.yml build


##    push:			pushes all Docker images
.PHONY : push
push:
	@docker-compose -f docker-compose.yml -f docker-compose.cli.yml push

##    cli:			        runs a container with an interactive shell
.PHONY : cli
cli:
	-@docker-compose -f docker-compose.cli.yml run \
		--rm \
		--no-deps \
		-e HOST_USER=${UID} \
		-e TERM=xterm-256color \
		php-cli /bin/zsh -l

##    install:			installs dependencies and some other configuration tasks
.PHONY : install
install:
	-@$(call docker_phpfpm_run,composer install --no-interaction);


