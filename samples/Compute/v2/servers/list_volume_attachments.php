<?php

require 'vendor/autoload.php';

$openstack = new OpenStack\OpenStack([
    'authUrl' => '{authUrl}',
    'region'  => '{region}',
    'user'    => [
        'id'       => '{userId}',
        'password' => '{password}',
    ],
    'scope'   => ['project' => ['id' => '{projectId}']],
]);

$compute = $openstack->computeV2(['region' => '{region}']);

$server = $compute->getServer(['id' => '{serverId}']);

foreach ($server->listVolumeAttachments() as $volumeAttachment) {
    /** @var \OpenStack\BlockStorage\v2\Models\VolumeAttachment $volumeAttachment */
}
