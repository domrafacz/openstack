<?php

namespace OpenStack\Sample\Identity\v3;

use OpenStack\Common\Error\BadResponseError;
use OpenStack\Identity\v3\Enum;
use OpenStack\Identity\v3\Models\Endpoint;

class EndpointTest extends TestCase
{
    public function testAdd(): Endpoint
    {
        $service = $this->getService()->createService(['name' => $this->randomStr(), 'type' => 'volume', 'description' => $this->randomStr()]);

        /** @var $endpoint \OpenStack\Identity\v3\Models\Endpoint */
        require_once $this->sampleFile(
            'endpoints/add_endpoint.php',
            [
                '{endpointName}' => $this->randomStr(),
                '{serviceId}'    => $service->id,
                '{endpointUrl}'  => getenv('OS_AUTH_URL'),
                '{region}'       => 'RegionOne',
            ]
        );
        self::assertInstanceOf(Endpoint::class, $endpoint);

        return $endpoint;
    }

    /**
     * @depends testAdd
     */
    public function testList(Endpoint $createdEndpoint)
    {
        $found = false;
        require_once $this->sampleFile(
            'endpoints/list_endpoints.php',
            [
                '/** @var $endpoint \OpenStack\Identity\v3\Models\Endpoint */' => <<<'PHP'
/** @var $endpoint \OpenStack\Identity\v3\Models\Endpoint */
if ($endpoint->id === $createdEndpoint->id) {
    $found = true;
}
PHP
                ,
            ]
        );

        $this->assertTrue($found);
    }

    /**
     * @depends testAdd
     */
    public function testUpdate(Endpoint $createdEndpoint)
    {
        $this->assertEquals(Enum::INTERFACE_INTERNAL, $createdEndpoint->interface);

        require_once $this->sampleFile(
            'endpoints/update_endpoint.php',
            [
                '{endpointId}'   => $createdEndpoint->id,
            ]
        );

        $createdEndpoint->retrieve();
        $this->assertEquals(Enum::INTERFACE_PUBLIC, $createdEndpoint->interface);
    }

    /**
     * @depends testAdd
     */
    public function testDelete(Endpoint $createdEndpoint)
    {
        require_once $this->sampleFile(
            'endpoints/delete_endpoint.php',
            [
                '{endpointId}'   => $createdEndpoint->id,
            ]
        );

        $found = false;
        foreach ($this->getService()->listEndpoints() as $endpoint) {
            if ($endpoint->id === $createdEndpoint->id) {
                $found = true;
            }
        }

        $this->assertFalse($found);

        $this->expectException(BadResponseError::class);
        $createdEndpoint->retrieve();
    }
}