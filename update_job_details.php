<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'include/db_connect.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operator') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['machine_id'])) {
    echo "Machine ID not provided.";
    exit();
}

$machine_id = $_GET['machine_id'];
$operator_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare('
        SELECT 
            m.machine_name, 
            ms.status_name, 
            ma.timer_1, 
            ma.timer_2, 
            ma.counter_1, 
            ma.speed 
        FROM 
            Machines m
        JOIN 
            MachineAssign ma ON m.id = ma.machine_id
        JOIN 
            MachineStatus ms ON m.machine_status_id = ms.id
        WHERE 
            m.id = ? 
            AND ma.staff_id = ? 
            AND (ma.release_date IS NULL OR ma.release_date > NOW())
    ');
    $stmt->execute([$machine_id, $operator_id]);
    $machine = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$machine) {
        echo "Machine not found or you are not authorized to adjust parameters for this machine.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching machine details.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timer_1 = intval($_POST['timer_1']);
    $timer_2 = intval($_POST['timer_2']);
    $counter_1 = intval($_POST['counter_1']);
    $speed = intval($_POST['speed']);

    try {
        $update_stmt = $pdo->prepare('
            UPDATE MachineAssign 
            SET timer_1 = ?, timer_2 = ?, counter_1 = ?, speed = ?
            WHERE machine_id = ? AND staff_id = ?
        ');
        $update_stmt->execute([$timer_1, $timer_2, $counter_1, $speed, $machine_id, $operator_id]);

        header('Location: view_job_details.php');
        exit();
    } catch (PDOException $e) {
        echo "Error updating machine parameters.";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Adjust Machine Parameters</title>
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
            width: 50%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        input[type="submit"] {
            margin-top: 15px;
            padding: 10px 15px;
            font-size: 16px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>Adjust Machine Parameters</h1>
        <div>
            <span><?php echo date("Y-m-d H:i:s"); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <div class="content">
        <form method="post" action="">
            <label for="machine_name">Machine Name:</label>
            <input type="text" id="machine_name" name="machine_name" value="<?php echo htmlspecialchars($machine['machine_name']); ?>" disabled>

            <label for="machine_status">Machine Status:</label>
            <select id="machine_status" name="machine_status" required>
                <?php
                try {
                    $status_stmt = $pdo->prepare('SELECT id, status_name FROM MachineStatus');
                    $status_stmt->execute();
                    $statuses = $status_stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($statuses as $status) {
                        $selected = ($machine['status_name'] === $status['status_name']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($status['id']) . '" ' . $selected . '>' . htmlspecialchars($status['status_name']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Error loading statuses</option>';
                }
                ?>
            </select>

            <label for="timer_1">Timer 1:</label>
            <input type="number" id="timer_1" name="timer_1" value="<?php echo htmlspecialchars($machine['timer_1']); ?>" required>

            <label for="timer_2">Timer 2:</label>
            <input type="number" id="timer_2" name="timer_2" value="<?php echo htmlspecialchars($machine['timer_2']); ?>" required>

            <label for="counter_1">Counter 1:</label>
            <input type="number" id="counter_1" name="counter_1" value="<?php echo htmlspecialchars($machine['counter_1']); ?>" required>

            <label for="speed">Speed:</label>
            <input type="number" id="speed" name="speed" value="<?php echo htmlspecialchars($machine['speed']); ?>" required>

            <input type="submit" value="Adjust Parameters">
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Factory. All rights reserved.</p>
    </footer>
</body>
</html>

