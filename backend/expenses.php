<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $expenseId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($expenseId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid expense ID']);
        exit;
    }

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('SELECT * FROM expenses WHERE id = :id');
    $stmt->bindParam(':id', $expenseId);
    $stmt->execute();

    // Process the output
    $expenses = $stmt->fetchAll();
    if (empty($expenses)) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Expense not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($expenses);
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $description = filter_var($inputData['description'] ?? null, FILTER_SANITIZE_STRING);
    $amount = filter_var($inputData['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $date = filter_var($inputData['date'] ?? null, FILTER_SANITIZE_STRING);

    if ($description === false || $amount === false || $date === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('INSERT INTO expenses (description, amount, date) VALUES (:description, :amount, :date)');
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Process the output
    $expenseId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $expenseId]);
    exit;
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $expenseId = filter_var($inputData['id'] ?? null, FILTER_VALIDATE_INT);
    $description = filter_var($inputData['description'] ?? null, FILTER_SANITIZE_STRING);
    $amount = filter_var($inputData['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $date = filter_var($inputData['date'] ?? null, FILTER_SANITIZE_STRING);

    if ($expenseId === false || $description === false || $amount === false || $date === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('UPDATE expenses SET description = :description, amount = :amount, date = :date WHERE id = :id');
    $stmt->bindParam(':id', $expenseId);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Process the output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Expense not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Expense updated successfully']);
    exit;
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get the input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $expenseId = filter_var($inputData['id'] ?? null, FILTER_VALIDATE_INT);

    if ($expenseId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid expense ID']);
        exit;
    }

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare('DELETE FROM expenses WHERE id = :id');
    $stmt->bindParam(':id', $expenseId);
    $stmt->execute();

    // Process the output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Expense not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Expense deleted successfully']);
    exit;
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);