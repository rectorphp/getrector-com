#!/usr/bin/env bash

while getopts ":v:r:i:d:" opt
do
  case ${opt} in
    v) VOLUME_SOURCE=${OPTARG};;
    r) RUN_ID=${OPTARG};;
    i) DOCKER_IMAGE=${OPTARG};;
    d) RUN_DIRECTORY=${OPTARG};;
    h)
        echo "Usage: run-demo [-h] -v <VOLUME_SOURCE> -r <RUN_ID> -i <DOCKER_IMAGE>"
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
    --name ${RUN_ID} \
    --volume ${VOLUME_SOURCE}:/project \
    ${DOCKER_IMAGE} \
    process /project/rector_analyzed_file.php \
    --output-format json \
    --output-file /project/output.json \
    --config /project/rector.yaml

RECTOR_RUN_EXIT_CODE=$?

if [[ ${RECTOR_RUN_EXIT_CODE} -eq 0 ]]
then
    cat ${RUN_DIRECTORY}/output.json
else
    docker logs ${RUN_ID}
fi

docker rm ${RUN_ID} > /dev/null
rm -rf ${RUN_DIRECTORY}

exit ${RECTOR_RUN_EXIT_CODE}
