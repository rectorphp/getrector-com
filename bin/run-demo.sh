#!/usr/bin/env bash

RUN_ID=""
VOLUME_SOURCE=""

docker run \
    --name ${RUN_ID} \
    --volume ${VOLUME_SOURCE}:/project \
    rector/rector:latest \
    process /project/rector_analyzed_file.php \
    --output-format json \
    --output-file /project/output.json \
    --config /project/rector.yaml

RECTOR_RUN_RESULT=$?

if [[ ${RECTOR_RUN_RESULT} -eq 0 ]]
then
    cat ${VOLUME_SOURCE}/output.json
else
    docker logs ${RUN_ID}
fi

docker rm ${RUN_ID}
rm -rf ${VOLUME_SOURCE}

exit ${RECTOR_RUN_RESULT}
