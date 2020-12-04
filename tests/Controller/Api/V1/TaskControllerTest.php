<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api\V1;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;

class TaskControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\Persistence\ObjectManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndex(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/v1/tasks', [], [], $this->getAuthHeader());
        $response = $this->client->getResponse();
        $responseBody = \json_decode($response->getContent(), true);

        self::assertTrue($response->isOk());
        self::assertIsArray($responseBody['data']);
        self::assertNotEmpty($responseBody['data']);
    }

    public function testCreate(): void
    {
        $title = 'someTestTitle';
        $desc = 'someDescription';
        $this->client->request(Request::METHOD_POST, '/api/v1/tasks', [], [], $this->getAuthHeader(), \json_encode([
            'title' => $title,
            'description' => $desc,
        ]));
        $response = $this->client->getResponse();
        $responseBody = \json_decode($response->getContent(), true);

        self::assertTrue($response->isOk());
        self::assertIsArray($responseBody['data']);
        self::assertSame($title, $responseBody['data']['title']);
        self::assertSame($desc, $responseBody['data']['description']);
    }

    public function testSetStatus(): void
    {
        $status = Task::STATUS_DONE;
        /** @var Task $task */
        $task = $this->entityManager->getRepository(Task::class)->findOneBy([]);
        $this->client->request(Request::METHOD_GET, "/api/v1/tasks/{$task->getId()}/set-status/{$status}", [], [], $this->getAuthHeader());
        $response = $this->client->getResponse();
        $responseBody = \json_decode($response->getContent(), true);

        self::assertTrue($response->isOk());
        self::assertIsArray($responseBody['data']);
        self::assertSame($status, $responseBody['data']['status']);
    }
}
