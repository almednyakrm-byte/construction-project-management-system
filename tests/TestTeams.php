<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeamsController;
use App\Repository\TeamsRepository;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestTeams extends TestCase
{
    private $teamsController;
    private $teamsRepository;
    private $entityManager;
    private $request;

    protected function setUp(): void
    {
        $this->teamsRepository = $this->createMock(TeamsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->teamsController = new TeamsController($this->teamsRepository, $this->entityManager);
    }

    public function testGetTeams()
    {
        $teams = [
            new Team('Team 1'),
            new Team('Team 2'),
        ];

        $this->teamsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($teams);

        $response = $this->teamsController->getTeams($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($teams), $response->getContent());
    }

    public function testGetTeam()
    {
        $team = new Team('Team 1');

        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $response = $this->teamsController->getTeam($this->request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testGetTeamNotFound()
    {
        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->teamsController->getTeam($this->request, 1);
    }

    public function testCreateTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($team);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Team 1']);

        $response = $this->teamsController->createTeam($this->request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($team);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Team 2']);

        $response = $this->teamsController->updateTeam($this->request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeamNotFound()
    {
        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->teamsController->updateTeam($this->request, 1);
    }

    public function testDeleteTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($team);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $response = $this->teamsController->deleteTeam($this->request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteTeamNotFound()
    {
        $this->teamsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->teamsController->deleteTeam($this->request, 1);
    }
}