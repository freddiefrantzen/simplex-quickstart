<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Test\Functional;

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Simplex\Quickstart\Module\Demo\DataFixture\PersonLoader;
use Simplex\Quickstart\Shared\Testing\FunctionalTest;

class PersonApiTest extends FunctionalTest
{
    public function test_it_can_be_queried_for_a_list_of_people()
    {
        $response = $this->sendGet('/');

        $body = $this->getBody($response);

        self::assertCount(1, $body);
        self::assertJsonDocumentMatchesSchema(json_encode($body[0]),
            [
                '$.name' => 'Joe Smith',
                '$.email' => 'joe@example.com',
            ]
        );
    }

    public function test_it_can_be_queried_for_a_specific_person()
    {
        $response = $this->sendGet('/person/' . PersonLoader::PERSON_1_ID);

        self::assertMessageBodyMatchesJson($response,
            [
                '$.name' => 'Joe Smith',
                '$.email' => 'joe@example.com'
            ]
        );

        self::assertJsonDocumentMatchesSchema($this->getRawBody($response),
            [
                'type'       => 'object',
                'required'   => ['name', 'email', '_links'],
                'properties' => [
                    'name' => ['type' => 'string'],
                    'email' => ['type' => 'string'],
                    'links' => ['type' => 'array'],
                ]
            ]
        );
    }

    public function test_it_can_create_a_new_person()
    {
        $response = $this->sendPost('/person',
            [
                'name' => 'Will',
                'email' => 'will@example.com',
                'password' => 'foobar'
            ]
        );

        self::assertResponseHasStatus($response, Httpstatuscodes::HTTP_CREATED);
        self::assertMessageHasHeader($response, 'Location');
    }
}
