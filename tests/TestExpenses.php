<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class TestExpenses extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetExpenses()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM expenses')
            ->willReturn($this->createMock(PDOStatement::class));

        $expensesController = new ExpensesController($this->pdo);
        $response = $expensesController->getExpenses($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCreateExpense()
    {
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Expense', 'amount' => 100.00]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO expenses (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(PDOStatement::class));

        $expensesController = new ExpensesController($this->pdo);
        $response = $expensesController->createExpense($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testUpdateExpense()
    {
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Test Expense', 'amount' => 200.00]);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE expenses SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $expensesController = new ExpensesController($this->pdo);
        $response = $expensesController->updateExpense($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteExpense()
    {
        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM expenses WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $expensesController = new ExpensesController($this->pdo);
        $response = $expensesController->deleteExpense($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}

class ExpensesController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getExpenses(ServerRequestInterface $request, ResponseInterface $response)
    {
        $stmt = $this->pdo->query('SELECT * FROM expenses');
        $expenses = $stmt->fetchAll();
        $response->getBody()->write(json_encode($expenses));
        return $response;
    }

    public function createExpense(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('INSERT INTO expenses (name, amount) VALUES (:name, :amount)');
        $stmt->execute($data);
        $response->getBody()->write(json_encode(['message' => 'Expense created successfully']));
        return $response;
    }

    public function updateExpense(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $stmt = $this->pdo->prepare('UPDATE expenses SET name = :name, amount = :amount WHERE id = :id');
        $stmt->execute(array_merge($data, ['id' => $id]));
        $response->getBody()->write(json_encode(['message' => 'Expense updated successfully']));
        return $response;
    }

    public function deleteExpense(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = $request->getAttribute('id');
        $stmt = $this->pdo->prepare('DELETE FROM expenses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $response->getBody()->write(json_encode(['message' => 'Expense deleted successfully']));
        return $response;
    }
}