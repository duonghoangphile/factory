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

try {
    $stmt = $pdo->prepare('
        SELECT 
            m.id AS machine_id,
            m.machine_name,
            ms.status_name,
            ma.timer_1,
            ma.timer_2,
            ma.counter_1,
            ma.speed,
            ma.assign_date,
            ma.release_date
        FROM 
            Mashines m
        JOIN 
            MachineAssign ma ON m.id = ma.machine_id
        JOIN 
            MachineStatus ms ON m.machine_status_id = ms.id
        WHERE 
            ma.staff_id = ?
            AND (ma.release_date IS NULL OR ma.release_date > NOW())
    ');
    $stmt->execute([$operator_id]);
    $machines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error fetching job details: ' . htmlspecialchars($e->getMessage());
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Job Details</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            margin-right: 8px;
            text-decoration: none;
            color: #0066cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Assigned Machines</h1>
        <div>
            <span><?php echo date("Y-m-d H:i:s"); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <div class="content">
        <?php if (!empty($machines)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Machine ID</th>
                        <th>Machine Name</th>
                        <th>Status</th>
                        <th>Timer 1</th>
                        <th>Timer 2</th>
                        <th>Counter 1</th>
                        <th>Speed</th>
                        <th>Assign Date</th>
                        <th>Release Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($machines as $machine): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($machine['machine_id']); ?></td>
                            <td><?php echo htmlspecialchars($machine['machine_name']); ?></td>
                            <td><?php echo htmlspecialchars($machine['status_name']); ?></td>
                            <td><?php echo htmlspecialchars($machine['timer_1']); ?></td>
                            <td><?php echo htmlspecialchars($machine['timer_2']); ?></td>
                            <td><?php echo htmlspecialchars($machine['counter_1']); ?></td>
                            <td><?php echo htmlspecialchars($machine['speed']); ?></td>
                            <td><?php echo htmlspecialchars($machine['assign_date']); ?></td>
                            <td><?php echo htmlspecialchars($machine['release_date'] ?? 'Currently Assigned'); ?></td>
                            <td>
                                <a href="update_job_details.php?machine_id=<?php echo urlencode($machine['machine_id']); ?>">Update</a>
                                <a href="adjust_parameters.php?machine_id=<?php echo urlencode($machine['machine_id']); ?>">Adjust</a>
                                <a href="message_manager.php?machine_id=<?php echo urlencode($machine['machine_id']); ?>">Message</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No machines assigned to you.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Factory. All rights reserved.</p>
    </footer>
</body>
</html>

