<?php 
    //////////////////////////////////////////////

    // TODO :: email will be a candidate key so check for uniqueness 


    session_start();

    // Requiring db.php for once
    require_once 'db.php';

    // Type --> json
    header('Content-Type: application/json');

    // Inntial check for request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request.'
        ]);
        exit;
    }

    // Handle data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check data empty or not
    if ($email === '' || $password === '' || $name === ''){
        echo json_encode([
            'status' => 'error',
            'message' => 'Name, Email and Password required.'
        ]);
        exit;
    }

    // Prepare password
    $hashed_password = password_hash($password,PASSWORD_DEFAULT); 

    // Email password inserting 
    try {
        //INSERT INTO users (name, email, hashed_password, role)
        // VALUES ('john_doe', 'john@gmail.com', 'hashedpassword123', 'user');

        // TODO :: setup query
        $query = "INSERT INTO users(name, email, hashed_password, role)  
            VALUES (:name, :email, :pass, :role)";
        $stmt = $pdo->prepare($query);

        // TODO :: For future upgrade options user as user (default)
        $user_type = 'user';

        // Execute the Query
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':pass' => $hashed_password,
            ':role' => $user_type        
        ]);

        // Add to session values
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        
    } catch (Throwable $e) {
        error_log("Sign-Up error: " . $e->getMessage());

        echo json_encode([
            'status' => 'error',
            'message' => 'Server error.'
        ]);
        exit;
    }

    // Successfull Insertion
    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful.'
    ]);
    exit;


?>