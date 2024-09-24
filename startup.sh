#!/bin/bash
dockup="docker compose -f docker-compose-local.yml up -d"
dockdown="docker compose -f docker-compose-local.yml down"
dockbuild="docker compose -f docker-compose-local.yml build"
queues="docker exec -it pcloud_web php console pcloud:performand"
delcon="docker rm $(docker ps -aq)"
stopcon="docker stop $(docker ps -aq)"

echo

while [ -n "$1" ]; do # while loop starts
        case "$1" in
        up) echo "Iniciando contenedores..." && $dockup ;;
        down) $dockdown ;;
        build) $dockbuild ;;
    update) $dockdown && $dockbuild && echo "Iniciando contenedores..." && $dockup ;;
        *) echo 'Solo puedes usar las opciones "up" "down" "build" y "update"' ;;
        esac
        shift
done
