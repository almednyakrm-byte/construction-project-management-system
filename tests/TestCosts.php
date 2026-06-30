<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\CostsController;
use App\Repository\CostsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOException;

class TestCosts extends TestCase
{
    private $costsController;
    private $costsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->costsRepository = $this->createMock(CostsRepository::class);
        $this->costsController = new CostsController($this->costsRepository);
    }

    public function testGetCosts()
    {
        $expectedResponse = ['costs' => []];
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM costs')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->costsRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);
        $response = $this->costsController->getCosts();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostCost()
    {
        $costData = ['name' => 'Test Cost', 'amount' => 10.99];
        $expectedResponse = ['message' => 'Cost created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO costs (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->costsRepository->expects($this->once())
            ->method('create')
            ->with($costData)
            ->willReturn($expectedResponse);
        $response = $this->costsController->postCost($costData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutCost()
    {
        $costId = 1;
        $costData = ['name' => 'Updated Cost', 'amount' => 20.99];
        $expectedResponse = ['message' => 'Cost updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE costs SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->costsRepository->expects($this->once())
            ->method('update')
            ->with($costId, $costData)
            ->willReturn($expectedResponse);
        $response = $this->costsController->putCost($costId, $costData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteCost()
    {
        $costId = 1;
        $expectedResponse = ['message' => 'Cost deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM costs WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->costsRepository->expects($this->once())
            ->method('delete')
            ->with($costId)
            ->willReturn($expectedResponse);
        $response = $this->costsController->deleteCost($costId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// CostsController.php
namespace App\Controller;

use App\Repository\CostsRepository;

class CostsController
{
    private $costsRepository;

    public function __construct(CostsRepository $costsRepository)
    {
        $this->costsRepository = $costsRepository;
    }

    public function getCosts()
    {
        return $this->costsRepository->getAll();
    }

    public function postCost(array $costData)
    {
        return $this->costsRepository->create($costData);
    }

    public function putCost(int $costId, array $costData)
    {
        return $this->costsRepository->update($costId, $costData);
    }

    public function deleteCost(int $costId)
    {
        return $this->costsRepository->delete($costId);
    }
}



// CostsRepository.php
namespace App\Repository;

class CostsRepository
{
    public function getAll()
    {
        // Implement logic to retrieve all costs from database
        // For testing purposes, return an empty array
        return [];
    }

    public function create(array $costData)
    {
        // Implement logic to create a new cost in the database
        // For testing purposes, return a success message
        return ['message' => 'Cost created successfully'];
    }

    public function update(int $costId, array $costData)
    {
        // Implement logic to update an existing cost in the database
        // For testing purposes, return a success message
        return ['message' => 'Cost updated successfully'];
    }

    public function delete(int $costId)
    {
        // Implement logic to delete a cost from the database
        // For testing purposes, return a success message
        return ['message' => 'Cost deleted successfully'];
    }
}