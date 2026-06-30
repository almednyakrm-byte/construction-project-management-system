<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);

        $this->connection->method('connect')->willReturn(true);
        $this->connection->method('fetchAll')->willReturn([
            ['id' => 1, 'username' => 'testuser', 'password' => 'testpassword'],
        ]);

        $this->authRepository->method('getUserByUsername')->willReturn(new User(1, 'testuser', 'testpassword'));
        $this->authRepository->method('getUserById')->willReturn(new User(1, 'testuser', 'testpassword'));
    }

    public function testLogin(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User(1, $username, $password));

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegister(): void
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with($username, $password)
            ->willReturn(new User(1, $username, $password));

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailed(): void
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

1. Successful login: Tests that the `login` method correctly logs in a user with the correct username and password.
2. Successful registration: Tests that the `register` method correctly creates a new user and logs them in.
3. Failed login: Tests that the `login` method correctly handles a failed login attempt when the username or password is incorrect.