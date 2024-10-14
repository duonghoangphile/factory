<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'include/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $password_input = $_POST['password'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM Staff WHERE user_name = ?');
        $stmt->execute([$user_name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password_input === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['role'] = 'operator'; 
            header('Location: view_job_details.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        echo 'Error during login.';
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: #f4f4f4;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        footer {
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .content {
            padding: 20px;
            margin-top: 60px;
            margin-bottom: 40px;
        }

        form {
            width: 300px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Factory Login</h1>
        <div><?php echo date("Y-m-d H:i:s"); ?></div>
    </header>
    <div class="content">
        <h1>Login</h1>
        <?php if (isset($error)) echo '<p class="error">' . htmlspecialchars($error) . '</p>'; ?>
        <form method="post" action="">
            <label for="user_name">Username:</label>
            <input type="text" name="user_name" id="user_name" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Factory. All rights reserved.</p>
    </footer>
</body>
</html>

