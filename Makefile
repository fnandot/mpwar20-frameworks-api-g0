all: help

##  _            ____        _ _
## | |    __ _  / ___|  __ _| | | ___
## | |   / _` | \___ \ / _` | | |/ _ \
## | |__| (_| |  ___) | (_| | | |  __/
## |_____\__,_| |____/ \__,_|_|_|\___|

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
	-@docker-compose -f docker-compose.db.yml -f docker-compose.yml start

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
	@docker-compose -f docker-compose.yml -f docker-compose.yml logs -f -t

##    logs@php-fpm:		just shows PHP fpm logs
.PHONY : logs@php-fpm
logs@php-fpm:
	@docker-compose -f docker-compose.yml logs -f -t php-fpm

##    deploy:			starts web server containers (nginx + PHP fpm) in production environment
.PHONY : deploy
deploy:
	@docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
	-@$(call docker_phpcli_run,/app/bin/console cache:clear -e prod);
	-@$(call docker_phpcli_run,/app/bin/console cache:warmup -e prod);
	-@$(call docker_phpcli_run,yarn encore production);
	-@$(call docker_phpcli_run,chown -R www-data.www-data);

##    remove:			stops all containers and delete them
.PHONY : remove
remove:
	@docker-compose -f docker-compose.db.yml -f docker-compose.yml rm -s -f

##    build:			builds all Docker images
.PHONY : build
build:
	@docker-compose -f docker-compose.cli.yml -f docker-compose.yml build


##    push:			pushes all Docker images
.PHONY : push
push:
	@docker-compose -f docker-compose.cli.yml -f docker-compose.yml push

##    interactive:			runs a container with an interactive shell
.PHONY : interactive
interactive:
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


# Tools

##    tools@generate-logs:		Generates random logs
tools@generate-logs:
	-@$(call docker_phpfpm_run,/app/bin/console app:log:generate --iterations 1000 --delay 0);


