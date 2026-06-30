<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use App\Entity\Materials;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestMaterials extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MaterialsRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new MaterialsController($this->repository, $this->router);
    }

    public function testGetMaterials()
    {
        $materials = [
            new Materials(1, 'Material 1'),
            new Materials(2, 'Material 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($materials);

        $response = $this->controller->getMaterials();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getContent());
    }

    public function testGetMaterialById()
    {
        $material = new Materials(1, 'Material 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $response = $this->controller->getMaterial(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($material), $response->getContent());
    }

    public function testCreateMaterial()
    {
        $material = new Materials(1, 'Material 1');
        $request = new Request([], [], [], [], [], ['name' => 'Material 1']);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($material);

        $response = $this->controller->createMaterial($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMaterial()
    {
        $material = new Materials(1, 'Material 1');
        $request = new Request([], [], [], [], [], ['name' => 'Material 1']);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($material);

        $response = $this->controller->updateMaterial(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaterial()
    {
        $material = new Materials(1, 'Material 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($material);

        $response = $this->controller->deleteMaterial(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// MaterialsController.php
namespace App\Controller;

use App\Repository\MaterialsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class MaterialsController
{
    private $repository;
    private $router;

    public function __construct(MaterialsRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getMaterials(): Response
    {
        $materials = $this->repository->findAll();
        return new Response(json_encode($materials));
    }

    public function getMaterial(int $id): Response
    {
        $material = $this->repository->find($id);
        return new Response(json_encode($material));
    }

    public function createMaterial(Request $request): Response
    {
        $material = new Materials();
        $material->setName($request->get('name'));
        $this->repository->save($material);
        return new Response('', Response::HTTP_CREATED);
    }

    public function updateMaterial(int $id, Request $request): Response
    {
        $material = $this->repository->find($id);
        $material->setName($request->get('name'));
        $this->repository->save($material);
        return new Response('', Response::HTTP_OK);
    }

    public function deleteMaterial(int $id): Response
    {
        $material = $this->repository->find($id);
        $this->repository->remove($material);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}