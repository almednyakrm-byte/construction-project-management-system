<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\Stream;

class Testمشاريع extends TestCase
{
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
    }

    public function testGetAllمشاريع()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([]);

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مشروع 1'],
                ['id' => 2, 'name' => 'مشروع 2'],
            ]);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مشاريع')
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'name' => 'مشروع 1'],
                ['id' => 2, 'name' => 'مشروع 2'],
            ]));

        $controller = new مشاريعController($this->mockPDO);
        $controller->getAllمشاريع($request, $response);
    }

    public function testGetمشروعById()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'مشروع 1']);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مشاريع WHERE id = ?')
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'مشروع 1']));

        $controller = new مشاريعController($this->mockPDO);
        $controller->getمشروعById($request, $response);
    }

    public function testCreateمشروع()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with(['name' => 'مشروع 3']);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مشاريع (name) VALUES (?)')
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مشروع 3']);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'مشروع created successfully']));

        $controller = new مشاريعController($this->mockPDO);
        $controller->createمشروع($request, $response);
    }

    public function testUpdateمشروع()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with(['name' => 'مشروع 1 updated', 1]);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مشاريع SET name = ? WHERE id = ?')
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مشروع 1 updated']);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'مشروع updated successfully']));

        $controller = new مشاريعController($this->mockPDO);
        $controller->updateمشروع($request, $response);
    }

    public function testDeleteمشروع()
    {
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مشاريع WHERE id = ?')
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'مشروع deleted successfully']));

        $controller = new مشاريعController($this->mockPDO);
        $controller->deleteمشروع($request, $response);
    }
}