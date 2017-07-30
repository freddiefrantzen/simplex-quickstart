<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Test\Functional;

use Simplex\Quickstart\Module\Demo\DataFixture\PersonLoader;
use Simplex\Quickstart\Shared\Testing\FunctionalTest;
use Simplex\Quickstart\Shared\Testing\HttpRequest;

class PersonApiTest extends FunctionalTest
{
    public function test_it_can_be_queried_for_a_list_of_people()
    {
        $request = new HttpRequest($this->getContainer());

        $response = $request->sendGet('/');

        $body = $this->getBody($response);

        self::assertCount(1, $body);
        self::assertJsonDocumentMatchesSchema(json_encode($body[0]), [
            '$.name' => 'Joe Smith',
            '$.email' => 'joe@example.com',
        ]);
    }

    public function test_it_can_be_queried_for_a_specific_person()
    {
        $request = new HttpRequest($this->getContainer());

        $response = $request
            ->sendGet('/person/' . PersonLoader::STATIC_ID);

        self::assertMessageBodyMatchesJson($response, [
            '$.name' => 'Joe Smith',
            '$.email' => 'joe@example.com'
        ]);

        self::assertJsonDocumentMatchesSchema($this->getRawBody($response), [
            'type'       => 'object',
            'required'   => ['name', 'email', '_links'],
            'properties' => [
                'name' => ['type' => 'string'],
                'email' => ['type' => 'string'],
                'links' => ['type' => 'array'],
            ]
        ]);
    }

    public function test_it_can_create_a_new_person()
    {
        $request = new HttpRequest($this->getContainer());

        $response = $request
            ->sendPost('/person', [
            'name' => 'Will',
            'email' => 'will@example.com',
            'password' => 'foobar'
        ]);

        self::assertResponseHasStatus($response, 201);
        self::assertMessageHasHeader($response, 'Location');
    }
}
