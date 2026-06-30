<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\FinancesController;
use App\Repository\FinancesRepository;
use App\Entity\Finance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;

class TestFinances extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(FinancesRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new FinancesController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetAllFinances()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Finance()]);

        $response = $this->controller->getAllFinances($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetFinanceById()
    {
        $finance = new Finance();
        $finance->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($finance);

        $response = $this->controller->getFinanceById($this->request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateFinance()
    {
        $finance = new Finance();
        $finance->setName('Test Finance');

        $this->repository->expects($this->once())
            ->method('create')
            ->with($finance)
            ->willReturn($finance);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($finance);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->createFinance($this->request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateFinance()
    {
        $finance = new Finance();
        $finance->setId(1);
        $finance->setName('Test Finance');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($finance);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($finance)
            ->willReturn($finance);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($finance);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->updateFinance($this->request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteFinance()
    {
        $finance = new Finance();
        $finance->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($finance);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($finance);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->deleteFinance($this->request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAllFinances`: Tests the `getAllFinances` method to ensure it returns a list of finances.
- `testGetFinanceById`: Tests the `getFinanceById` method to ensure it returns a finance by its ID.
- `testCreateFinance`: Tests the `createFinance` method to ensure it creates a new finance and returns it.
- `testUpdateFinance`: Tests the `updateFinance` method to ensure it updates an existing finance and returns it.
- `testDeleteFinance`: Tests the `deleteFinance` method to ensure it deletes a finance by its ID.

Note: This test file assumes that the `FinancesController` class has the following methods:

- `getAllFinances(Request $request)`
- `getFinanceById(Request $request, int $id)`
- `createFinance(Request $request)`
- `updateFinance(Request $request, int $id)`
- `deleteFinance(Request $request, int $id)`

Also, it assumes that the `FinancesRepository` class has the following methods:

- `findAll()`
- `find(int $id)`
- `create(Finance $finance)`
- `update(Finance $finance)`
- `delete(Finance $finance)`