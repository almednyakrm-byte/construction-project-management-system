<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TasksController;
use App\Repository\TasksRepository;
use App\Service\TasksService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestTasks extends TestCase
{
    private $tasksController;
    private $tasksRepository;
    private $tasksService;
    private $router;
    private $pdo;

    protected function setUp(): void
    {
        $this->tasksRepository = $this->createMock(TasksRepository::class);
        $this->tasksService = $this->createMock(TasksService::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->tasksController = new TasksController(
            $this->tasksRepository,
            $this->tasksService,
            $this->router,
            $this->pdo
        );
    }

    public function testGetTasks(): void
    {
        $this->tasksRepository->expects($this->once())
            ->method('getAllTasks')
            ->willReturn([
                ['id' => 1, 'title' => 'Task 1'],
                ['id' => 2, 'title' => 'Task 2'],
            ]);

        $response = $this->tasksController->getTasks();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateTask(): void
    {
        $task = ['title' => 'New Task'];

        $this->tasksService->expects($this->once())
            ->method('createTask')
            ->with($task)
            ->willReturn(['id' => 1, 'title' => 'New Task']);

        $response = $this->tasksController->createTask($task);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateTask(): void
    {
        $taskId = 1;
        $task = ['title' => 'Updated Task'];

        $this->tasksRepository->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn(['id' => 1, 'title' => 'Task 1']);

        $this->tasksService->expects($this->once())
            ->method('updateTask')
            ->with($taskId, $task)
            ->willReturn(['id' => 1, 'title' => 'Updated Task']);

        $response = $this->tasksController->updateTask($taskId, $task);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteTask(): void
    {
        $taskId = 1;

        $this->tasksRepository->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn(['id' => 1, 'title' => 'Task 1']);

        $this->tasksService->expects($this->once())
            ->method('deleteTask')
            ->with($taskId);

        $response = $this->tasksController->deleteTask($taskId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This test file assumes that the `TasksController` class has methods for each of the CRUD operations (get, post, put, delete) and that the `TasksRepository` and `TasksService` classes have methods for interacting with the database. The `RouterInterface` and `\PDO` classes are also assumed to be used in the `TasksController` class.