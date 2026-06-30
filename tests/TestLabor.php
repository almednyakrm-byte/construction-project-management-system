<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LaborModule;

class TestLabor extends TestCase
{
    private $laborModule;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->laborModule = new LaborModule($this->pdo);
    }

    public function testGetLabor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM labor')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->laborModule->getLabor($request, $response);
    }

    public function testPostLabor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Labor', 'description' => 'Test description']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO labor (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->laborModule->postLabor($request, $response);
    }

    public function testPutLabor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'Updated Test Labor', 'description' => 'Updated test description']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE labor SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->laborModule->putLabor($request, $response);
    }

    public function testDeleteLabor()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM labor WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->laborModule->deleteLabor($request, $response);
    }
}