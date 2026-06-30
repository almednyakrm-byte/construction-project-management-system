<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\FnionController;
use App\Repository\FnionRepository;
use App\Entity\Fnion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestFnion extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(FnionRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new FnionController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetFnions()
    {
        $fnions = [
            new Fnion('1', 'فني 1'),
            new Fnion('2', 'فني 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($fnions);

        $response = $this->controller->getFnions($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($fnions), $response->getContent());
    }

    public function testGetFnion()
    {
        $fnion = new Fnion('1', 'فني 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($fnion);

        $response = $this->controller->getFnion($this->request, '1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($fnion), $response->getContent());
    }

    public function testGetFnionNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->getFnion($this->request, '1');
    }

    public function testPostFnion()
    {
        $fnion = new Fnion('1', 'فني 1');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($fnion);

        $response = $this->controller->postFnion($this->request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($fnion), $response->getContent());
    }

    public function testPutFnion()
    {
        $fnion = new Fnion('1', 'فني 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($fnion);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($fnion);

        $response = $this->controller->putFnion($this->request, '1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($fnion), $response->getContent());
    }

    public function testPutFnionNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->putFnion($this->request, '1');
    }

    public function testDeleteFnion()
    {
        $fnion = new Fnion('1', 'فني 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($fnion);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($fnion);

        $response = $this->controller->deleteFnion($this->request, '1');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteFnionNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->deleteFnion($this->request, '1');
    }
}


This test file covers the following scenarios:

- `testGetFnions`: Tests the GET request to retrieve all fnions.
- `testGetFnion`: Tests the GET request to retrieve a single fnion by ID.
- `testGetFnionNotFound`: Tests the GET request to retrieve a non-existent fnion.
- `testPostFnion`: Tests the POST request to create a new fnion.
- `testPutFnion`: Tests the PUT request to update an existing fnion.
- `testPutFnionNotFound`: Tests the PUT request to update a non-existent fnion.
- `testDeleteFnion`: Tests the DELETE request to delete an existing fnion.
- `testDeleteFnionNotFound`: Tests the DELETE request to delete a non-existent fnion.

Note that this test file assumes that the `FnionController` class has the following methods:

- `getFnions(Request $request)`: Handles the GET request to retrieve all fnions.
- `getFnion(Request $request, string $id)`: Handles the GET request to retrieve a single fnion by ID.
- `postFnion(Request $request)`: Handles the POST request to create a new fnion.
- `putFnion(Request $request, string $id)`: Handles the PUT request to update an existing fnion.
- `deleteFnion(Request $request, string $id)`: Handles the DELETE request to delete an existing fnion.