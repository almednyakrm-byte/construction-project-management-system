<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\EmployeeController;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testموظفين extends TestCase
{
    private $employeeController;
    private $employeeRepository;
    private $employeeService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->employeeRepository = $this->createMock(EmployeeRepository::class);
        $this->employeeService = $this->createMock(EmployeeService::class);
        $this->employeeController = new EmployeeController($this->employeeRepository, $this->employeeService);
    }

    public function testGetEmployees()
    {
        $this->employeeRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Employee 1'],
                ['id' => 2, 'name' => 'Employee 2'],
            ]);

        $response = $this->employeeController->getEmployees();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetEmployeeById()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Employee 1']);

        $response = $this->employeeController->getEmployee(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetEmployeeByIdNotFound()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->getEmployee(1);
    }

    public function testCreateEmployee()
    {
        $request = new Request([], [], [], [], [], ['json' => ['name' => 'Employee 1']]);
        $this->employeeService->expects($this->once())
            ->method('createEmployee')
            ->with(['name' => 'Employee 1'])
            ->willReturn(['id' => 1, 'name' => 'Employee 1']);

        $response = $this->employeeController->createEmployee($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateEmployee()
    {
        $request = new Request([], [], [], [], [], ['json' => ['name' => 'Employee 1']]);
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Employee 1']);
        $this->employeeService->expects($this->once())
            ->method('updateEmployee')
            ->with(1, ['name' => 'Employee 1'])
            ->willReturn(['id' => 1, 'name' => 'Employee 1']);

        $response = $this->employeeController->updateEmployee(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateEmployeeNotFound()
    {
        $request = new Request([], [], [], [], [], ['json' => ['name' => 'Employee 1']]);
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->updateEmployee(1, $request);
    }

    public function testDeleteEmployee()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Employee 1']);
        $this->employeeService->expects($this->once())
            ->method('deleteEmployee')
            ->with(1);

        $response = $this->employeeController->deleteEmployee(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteEmployeeNotFound()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->deleteEmployee(1);
    }
}


This test file covers the following scenarios:

*   `testGetEmployees`: Verifies that the `getEmployees` method returns a successful response with a list of employees.
*   `testGetEmployeeById`: Verifies that the `getEmployee` method returns a successful response with a single employee.
*   `testGetEmployeeByIdNotFound`: Verifies that the `getEmployee` method throws a `NotFoundHttpException` when the employee is not found.
*   `testCreateEmployee`: Verifies that the `createEmployee` method creates a new employee and returns a successful response.
*   `testUpdateEmployee`: Verifies that the `updateEmployee` method updates an existing employee and returns a successful response.
*   `testUpdateEmployeeNotFound`: Verifies that the `updateEmployee` method throws a `NotFoundHttpException` when the employee is not found.
*   `testDeleteEmployee`: Verifies that the `deleteEmployee` method deletes an existing employee and returns a successful response.
*   `testDeleteEmployeeNotFound`: Verifies that the `deleteEmployee` method throws a `NotFoundHttpException` when the employee is not found.