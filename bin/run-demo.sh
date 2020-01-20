#!/usr/bin/env bash

while getopts ":v:n:i:" opt
do
  case ${opt} in
    v) VOLUME_SOURCE=${OPTARG};;
    n) NAME=${OPTARG};;
    i) DOCKER_IMAGE=${OPTARG};;
    h)
        echo "Usage: run-demo [-h] -v <VOLUME_SOURCE> -n <NAME> -i <DOCKER_IMAGE>"
        exit 0
        ;;
    \?)
        echo "Invalid option: -$OPTARG" 1>&2
        exit 1
        ;;
    : )
        echo "Invalid option: -$OPTARG requires an argument" 1>&2
        exit 1
        ;;
  esac
done

if ((OPTIND == 1))
then
    echo "No options specified"
    exit 1
fi

shift $((OPTIND - 1))

docker run \
    --name ${NAME} \
    --volume ${VOLUME_SOURCE}:/project \
    ${DOCKER_IMAGE} \
    process /project/rector_analyzed_file.php \
    --output-format json \
    --config /project/rector.yaml

RECTOR_RUN_EXIT_CODE=$?

docker rm -f ${NAME} > /dev/null

exit ${RECTOR_RUN_EXIT_CODE}
