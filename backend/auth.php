<?php
// Start the session to handle user authentication
session_start();

// Import the database connection file
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user data
        $userData = array(
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username']
        );
        echo json_encode($userData);
    } else {
        // User is not logged in, return a null response
        echo json_encode(null);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login and registration requests
    if (isset($_POST['action'])) {
        // Check the action type
        if ($_POST['action'] === 'login') {
            // Login action
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the username and password for the query
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $password = $_POST['password'];

                // Prepare a query to select the user data
                $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if the user exists
                if ($result->num_rows > 0) {
                    // Fetch the user data
                    $userData = $result->fetch_assoc();

                    // Verify the password
                    if (password_verify($password, $userData['password'])) {
                        // Password is correct, start a new session
                        $_SESSION['user_id'] = $userData['user_id'];
                        $_SESSION['username'] = $userData['username'];

                        // Return a success response
                        echo json_encode(array('status' => 'success', 'message' => 'Logged in successfully'));
                    } else {
                        // Password is incorrect, return an error response
                        echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
                    }
                } else {
                    // User does not exist, return an error response
                    echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
                }
            } else {
                // Username or password field is missing, return an error response
                echo json_encode(array('status' => 'error', 'message' => 'Please fill in all fields'));
            }
        } elseif ($_POST['action'] === 'register') {
            // Register action
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Prepare the username, email, and password for the query
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                // Check if the username and email are valid
                if (strlen($username) < 3 || strlen($username) > 32) {
                    echo json_encode(array('status' => 'error', 'message' => 'Username must be between 3 and 32 characters'));
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(array('status' => 'error', 'message' => 'Invalid email address'));
                } else {
                    // Prepare a query to check if the username or email already exists
                    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
                    $stmt->bind_param("ss", $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the username or email already exists
                    if ($result->num_rows > 0) {
                        // Username or email already exists, return an error response
                        echo json_encode(array('status' => 'error', 'message' => 'Username or email already exists'));
                    } else {
                        // Hash the password
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        // Prepare a query to insert the new user data
                        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $username, $email, $hashedPassword);
                        $stmt->execute();

                        // Check if the query was successful
                        if ($stmt->affected_rows > 0) {
                            // Query was successful, start a new session
                            $_SESSION['user_id'] = $conn->insert_id;
                            $_SESSION['username'] = $username;

                            // Return a success response
                            echo json_encode(array('status' => 'success', 'message' => 'Registered successfully'));
                        } else {
                            // Query failed, return an error response
                            echo json_encode(array('status' => 'error', 'message' => 'Failed to register'));
                        }
                    }
                }
            } else {
                // Username, email, or password field is missing, return an error response
                echo json_encode(array('status' => 'error', 'message' => 'Please fill in all fields'));
            }
        } elseif ($_POST['action'] === 'logout') {
            // Logout action
            // Unset the session variables
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);

            // Destroy the session
            session_destroy();

            // Return a success response
            echo json_encode(array('status' => 'success', 'message' => 'Logged out successfully'));
        }
    }
} else {
    // Invalid request method, return an error response
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}