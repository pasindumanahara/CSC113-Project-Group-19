<?php
    require_once 'db.php';

    // Get POST values
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo "Email and password required";
        exit;
    }

    // Hash password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (email, hashed_password, role) VALUES (:email, :hashed_password, :role)");

    try {
        $stmt->execute([
            ':email' => $email,
            ':hashed_password' => $password_hashed,
            ':role' => 'admin'
        ]);
        echo "User inserted successfully";
    } catch (PDOException $e) {
        echo "Error inserting user: " . $e->getMessage();
    }

    // Close connection
    $conn = null;
?>
