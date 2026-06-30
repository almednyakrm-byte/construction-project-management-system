<?php

require_once 'db.php';

// Get user role and ID from session
$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Check if user is logged in
if (!$user_id) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM موظفين');
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employees);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Validate and sanitize input data
    $required_fields = array('اسم', 'وظيفة', 'رقم الهاتف');
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Prepare INSERT statement
    $stmt = $pdo->prepare('INSERT INTO موظفين (اسم, وظيفة, رقم_الهاتف) VALUES (:اسم, :وظيفة, :رقم_الهاتف)');
    $stmt->bindParam(':اسم', $data['اسم']);
    $stmt->bindParam(':وظيفة', $data['وظيفة']);
    $stmt->bindParam(':رقم_الهاتف', $data['رقم_الهاتف']);

    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Execute INSERT statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Employee created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create employee'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Validate and sanitize input data
    $required_fields = array('id', 'اسم', 'وظيفة', 'رقم الهاتف');
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Prepare UPDATE statement
    $stmt = $pdo->prepare('UPDATE موظفين SET اسم = :اسم, وظيفة = :وظيفة, رقم_الهاتف = :رقم_الهاتف WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':اسم', $data['اسم']);
    $stmt->bindParam(':وظيفة', $data['وظيفة']);
    $stmt->bindParam(':رقم_الهاتف', $data['رقم_الهاتف']);

    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Execute UPDATE statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update employee'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Validate and sanitize input data
    $required_fields = array('id');
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Prepare DELETE statement
    $stmt = $pdo->prepare('DELETE FROM موظفين WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);

    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Execute DELETE statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete employee'));
    }
}