#!/bin/bash

cd "$(dirname $0)"

task=$1 # More descriptive name
arg=$2
args=${*:2}

case $task in
    build)
        docker build -t zircon .
        ;;
    start)
        if ! docker inspect zircon_db > /dev/null 2> /dev/null; then
            mkdir -p data/mysql
            docker run -d \
                --name zircon_db \
                -e MYSQL_ROOT_PASSWORD=root \
                -e MYSQL_DATABASE=zircon \
                -e MYSQL_USER=zircon \
                -e MYSQL_PASSWORD=zircon \
                -v $PWD/data/mysql:/var/lib/mysql \
                mysql:5.7
        fi
        docker start zircon_db
        sleep 2

        docker run \
            -it --rm \
            --name zircon \
            -p 0.0.0.0:80:80 \
            --link zircon_db:db \
            -e ZIRCON_MYSQL_HOST=zircon_db \
            -e ZIRCON_MYSQL_USER=zircon \
            -e ZIRCON_MYSQL_PASSWORD=zircon \
            -e ZIRCON_MYSQL_DATABASE=zircon \
            -e ZIRCON_SALT=utm0cq92u3t0q923uq \
            -v $PWD:/app \
            -v $PWD/data:/data \
            zircon
        ;;
    stop)
        docker stop zircon_db
        docker stop zircon
        ;;
    shell)
        docker exec -i -t zircon bash
        ;;
    dbshell)
        docker exec -ti zircon_db mysql -u zircon --password=zircon zircon
        ;;
    loaddb)
        docker exec -i zircon_db mysql -u zircon --password=zircon zircon < $arg
        ;;
    dumpdb)
        docker exec -i zircon_db mysqldump --password=root zircon > $arg
        ;;
    upgrade)
        docker exec -i zircon sh -c 'echo UPGRADING DB... && php webroot/upgrade.php && echo RECALCULATING STATISTICS... && php webroot/index.php /recalc'
        ;;
    '')
        echo 'Usage: ./d action [params].'
        ;;
    *)
        echo 'Unknown action '$task'. For a list of the available actions, please use "help" action'
        ;;
esac
