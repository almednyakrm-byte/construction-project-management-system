<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProjectsController;
use App\Repository\ProjectsRepository;
use App\Service\ProjectsService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestProjects extends TestCase
{
    private $projectsController;
    private $projectsRepository;
    private $projectsService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->projectsRepository = $this->createMock(ProjectsRepository::class);
        $this->projectsService = $this->createMock(ProjectsService::class);
        $this->projectsController = new ProjectsController($this->projectsService);
    }

    public function testGetProjects()
    {
        $expectedResponse = ['projects' => []];
        $this->projectsService->expects($this->once())
            ->method('getProjects')
            ->willReturn($expectedResponse);
        $response = $this->projectsController->getProjects();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateProject()
    {
        $projectData = ['name' => 'Test Project', 'description' => 'Test project description'];
        $expectedResponse = ['project' => $projectData];
        $this->projectsService->expects($this->once())
            ->method('createProject')
            ->with($projectData)
            ->willReturn($expectedResponse);
        $response = $this->projectsController->createProject($projectData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateProject()
    {
        $projectId = 1;
        $projectData = ['name' => 'Updated Project', 'description' => 'Updated project description'];
        $expectedResponse = ['project' => $projectData];
        $this->projectsService->expects($this->once())
            ->method('updateProject')
            ->with($projectId, $projectData)
            ->willReturn($expectedResponse);
        $response = $this->projectsController->updateProject($projectId, $projectData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteProject()
    {
        $projectId = 1;
        $expectedResponse = ['message' => 'Project deleted successfully'];
        $this->projectsService->expects($this->once())
            ->method('deleteProject')
            ->with($projectId)
            ->willReturn($expectedResponse);
        $response = $this->projectsController->deleteProject($projectId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\ProjectsController.php

namespace App\Controller;

use App\Service\ProjectsService;

class ProjectsController
{
    private $projectsService;

    public function __construct(ProjectsService $projectsService)
    {
        $this->projectsService = $projectsService;
    }

    public function getProjects()
    {
        return $this->projectsService->getProjects();
    }

    public function createProject($projectData)
    {
        return $this->projectsService->createProject($projectData);
    }

    public function updateProject($projectId, $projectData)
    {
        return $this->projectsService->updateProject($projectId, $projectData);
    }

    public function deleteProject($projectId)
    {
        return $this->projectsService->deleteProject($projectId);
    }
}