<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiSermonsV1RestControllerTest extends WebTestCase
{
    public function testSomething()
    {
        // submits a form directly (but using the Crawler is easier!)
        $client->request('POST', '/submit', ['name' => 'Fabien']);

        // submits a raw JSON string in the request body
        $client->request(
            'POST',
            '/api/v2/sermons',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"name":"Fabien"}'
        );
    }
}
