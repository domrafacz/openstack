<?php

require 'vendor/autoload.php';

$openstack = new OpenStack\OpenStack([
    'authUrl' => '{authUrl}',
    'region'  => '{region}',
    'user'    => [
        'id'       => '{userId}',
        'password' => '{password}'
    ],
]);

$identity = $openstack->identityV3();

$project = $identity->getProject('{id}');

$result = $project->checkGroupRole([
    'groupId' => '{groupId}',
    'roleId'  => '{roleId}',
]);

if (true === $result) {
}
