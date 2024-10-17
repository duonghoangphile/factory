<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'include/db_connect.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header('Location: login.php');
    exit();
}

$operator_id = $_SESSION['user_id'];
$operator_name = $_SESSION['user_name'];

try {
    $stmt = $pdo->prepare('SELECT id, user_name FROM Staff WHERE role = ?');
    $stmt->execute(['manager']);
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$managers) {
        echo "No managers found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching managers.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager_id = intval($_POST['manager_id']);
    $message_body = trim($_POST['message_body']);

    if (empty($message_body)) {
        $error = "Message body cannot be empty.";
    } else {
        try {
            $insert_stmt = $pdo->prepare('INSERT INTO Messages (sender_id, receiver_id, body, timestamp) VALUES (?, ?, ?, NOW())');
            $insert_stmt->execute([$operator_id, $manager_id, $message_body]);

            header('Location: view_job_details.php?message=message_sent');
            exit();
        } catch (PDOException $e) {
           $error = "Error sending message: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Message Manager</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
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
            width: 60%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        input[type="submit"], .cancel-button {
            margin-top: 15px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
        }

        .error {
            color: red;
            text-align: center;
        }

        /* Styles for the virtual keyboard */
        #keyboard {
            margin-top: 10px;
            text-align: center;
        }

        .key {
            display: inline-block;
            padding: 10px;
            margin: 2px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            cursor: pointer;
            user-select: none;
        }

        .key:active {
            background-color: #ddd;
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const messageBody = document.getElementById('message_body');
            const keyboard = document.getElementById('keyboard');
            const keys = [
    'A','B','C','D','E','F','G','H','I',
    'J','K','L','M','N','O','P','Q','R','S',
    'T','U','V','W','X','Y','Z',
                'Space','Backspace'
            ];

            keys.forEach(key => {
                let keyButton = document.createElement('div');
                keyButton.classList.add('key');
                keyButton.textContent = key;
                keyButton.addEventListener('click', () => {
                    if (key === 'Backspace') {
                        messageBody.value = messageBody.value.slice(0, -1);
                    } else if (key === 'Space') {
                        messageBody.value += ' ';
                    } else {
                        messageBody.value += key;
                    }
                });
                keyboard.appendChild(keyButton);
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Send Message to Manager</h1>
        <div>
            <span><?php echo date("Y-m-d H:i:s"); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <div class="content">
        <?php
        if (isset($error)) {
            echo '<p class="error">' . htmlspecialchars($error) . '</p>';
        }
        ?>

        <form method="post" action="">
            <label for="manager_id">Select Manager:</label>
            <select id="manager_id" name="manager_id" required>
                <option value="">--Select Manager--</option>
                <?php foreach ($managers as $manager): ?>
                    <option value="<?php echo htmlspecialchars($manager['id']); ?>"><?php echo htmlspecialchars($manager['user_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="message_body">Message:</label>
            <textarea id="message_body" name="message_body" rows="5" readonly required></textarea>

            <div id="keyboard"></div>

            <input type="submit" value="Send Message">
            <input type="button" class="cancel-button" value="Cancel" onclick="window.location.href='http://localhost/factory/view_job_details.php';">
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Factory. All rights reserved.</p>
    </footer>
</body>
</html>

