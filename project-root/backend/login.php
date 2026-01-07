<?php
    // Start a session
    session_start();
    require_once 'db.php';

    // Type
    header('Content-Type: application/json');

    // Check method before handle data
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request.'
        ]);
        exit;
    }

    // Handle data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check if email or password empty
    if ($email === '' || $password === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email and Password required.'
        ]);
        exit;
    }

    // Email password validation
    try {
        $query = "SELECT email, hashed_password FROM users WHERE email = :u LIMIT 1";
        $stmt = $pdo->prepare($query);

        // Execute the Query
        $stmt->execute([':u' => $email]);

        // Fetch return to user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Session data about email for later use in for front-end and back-end
            // ----- should clear these on logout.php 
            $_SESSION['email'] = $user['email'];

            // Successful login attempt
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful'
            ]);
            exit;
        }

        // Unsuccessful Login attempt
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ]);
        exit;
    } catch (Throwable $e) {
        error_log("Login error: " . $e->getMessage());

        echo json_encode([
            'status' => 'error',
            'message' => 'Server error.'
        ]);
        exit;
    }
?>