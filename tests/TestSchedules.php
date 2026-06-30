<?php

namespace App\Tests\Controller;

use App\Controller\SchedulesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestSchedules extends TestCase
{
    private $schedulesController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->schedulesController = new SchedulesController($this->pdoMock);
    }

    public function testGetSchedules()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM schedules')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->schedulesController->getSchedules();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateSchedule()
    {
        $request = new Request([], [], ['json' => ['name' => 'Test Schedule', 'description' => 'Test description']]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO schedules (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->schedulesController->createSchedule($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateSchedule()
    {
        $request = new Request([], [], ['json' => ['name' => 'Updated Test Schedule', 'description' => 'Updated test description']]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE schedules SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->schedulesController->updateSchedule(1, $request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteSchedule()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM schedules WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->schedulesController->deleteSchedule(1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file includes tests for the following API operations:

- `getSchedules`: Tests the GET request to retrieve all schedules.
- `createSchedule`: Tests the POST request to create a new schedule.
- `updateSchedule`: Tests the PUT request to update an existing schedule.
- `deleteSchedule`: Tests the DELETE request to delete a schedule.

Each test method uses the `createMock` method to create a mock PDO object, which is then used to simulate the database interactions. The `expects` method is used to specify the expected method calls and their parameters. The `willReturn` method is used to specify the return value of the mock object.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `SchedulesController` class and the database schema for the `schedules` table.