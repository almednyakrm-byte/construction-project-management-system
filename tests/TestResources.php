<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ResourcesController;
use App\Repository\ResourcesRepository;
use App\Entity\Resources;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TestResources extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock('Doctrine\DBAL\Driver\Statement');
        $this->entityManager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->repository = $this->createMock(ResourcesRepository::class);
        $this->controller = new ResourcesController($this->repository, $this->entityManager);
    }

    public function testGetResources(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Resources('resource1'),
                new Resources('resource2'),
            ]);

        $response = $this->controller->getResources();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetResource(): void
    {
        $resourceId = 'resource1';
        $this->repository->expects($this->once())
            ->method('find')
            ->with($resourceId)
            ->willReturn(new Resources($resourceId));

        $response = $this->controller->getResource($resourceId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateResource(): void
    {
        $resource = new Resources('resource1');
        $this->repository->expects($this->once())
            ->method('create')
            ->with($resource)
            ->willReturn($resource);

        $request = new Request();
        $request->request->set('name', 'resource1');
        $response = $this->controller->createResource($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateResource(): void
    {
        $resourceId = 'resource1';
        $resource = new Resources($resourceId);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($resourceId)
            ->willReturn($resource);

        $request = new Request();
        $request->request->set('name', 'resource1');
        $response = $this->controller->updateResource($resourceId, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteResource(): void
    {
        $resourceId = 'resource1';
        $this->repository->expects($this->once())
            ->method('find')
            ->with($resourceId)
            ->willReturn(new Resources($resourceId));

        $response = $this->controller->deleteResource($resourceId);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD API operations for the 'resources' module. It uses mocked PDO statements to simulate database interactions. The tests verify the correct behavior of the controller methods for GET, POST, PUT, and DELETE requests.