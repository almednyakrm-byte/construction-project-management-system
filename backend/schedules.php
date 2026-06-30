<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You must be logged in to access this resource']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Connect to the database
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate the request data
    if (!isset($data['id'])) {
        // Get all schedules
        $stmt = $pdo->prepare('SELECT * FROM schedules');
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($schedules);
    } else {
        // Get a single schedule by ID
        $stmt = $pdo->prepare('SELECT * FROM schedules WHERE id = :id');
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
        $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($schedule) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($schedule);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Schedule not found']);
        }
    }
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate the request data
    if (!isset($data['name']) || !isset($data['description']) || !isset($data['start_time']) || !isset($data['end_time'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
    } else {
        // Check if the user is an admin
        if ($_SESSION['user_role'] != 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Only admins can create schedules']);
        } else {
            // Insert a new schedule
            $stmt = $pdo->prepare('INSERT INTO schedules (name, description, start_time, end_time) VALUES (:name, :description, :start_time, :end_time)');
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':start_time', $data['start_time']);
            $stmt->bindParam(':end_time', $data['end_time']);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Schedule created successfully']);
        }
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Validate the request data
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['description']) || !isset($data['start_time']) || !isset($data['end_time'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
    } else {
        // Check if the user is an admin
        if ($_SESSION['user_role'] != 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Only admins can update schedules']);
        } else {
            // Update a schedule
            $stmt = $pdo->prepare('UPDATE schedules SET name = :name, description = :description, start_time = :start_time, end_time = :end_time WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':start_time', $data['start_time']);
            $stmt->bindParam(':end_time', $data['end_time']);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Schedule updated successfully']);
        }
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Validate the request data
    if (!isset($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
    } else {
        // Check if the user is an admin
        if ($_SESSION['user_role'] != 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Only admins can delete schedules']);
        } else {
            // Delete a schedule
            $stmt = $pdo->prepare('DELETE FROM schedules WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Schedule deleted successfully']);
        }
    }
}

// Close the database connection
$pdo = null;