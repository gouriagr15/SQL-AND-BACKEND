<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles3.css"> 
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome!</h1>

    <?php if ($role == 'admin'): ?>
        <h2>Admin Access</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Surname</th>
                <th>Mobile No</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Course</th>
                <th>Current Year</th>
                <th>CGPA</th>
                <th>Skills</th>
                <th>Projects</th>
                <th>Work Experience</th>
                <th>Current City</th>
            </tr>
            <?php
            $sql = "SELECT * FROM user_info";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['surname']}</td>
                    <td>{$row['mobile_no']}</td>
                    <td>{$row['email']}</td>
                    <td><a href='{$row['resume']}' target='_blank'>View Resume</a></td>
                    <td>{$row['course']}</td>
                    <td>{$row['current_year']}</td>
                    <td>{$row['cgpa']}</td>
                    <td>{$row['skills']}</td>
                    <td>{$row['projects']}</td>
                    <td>{$row['work_exp']}</td>
                    <td>{$row['current_city']}</td>
                </tr>";
            }
            ?>
        </table>
    <?php elseif ($role == 'coordinator'): ?>
        <h2>Coordinator Access</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Surname</th>
                <th>Mobile No</th>
                <th>Email</th>
            </tr>
            <?php
            $sql = "SELECT id, first_name, surname, mobile_no, email FROM user_info";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['surname']}</td>
                    <td>{$row['mobile_no']}</td>
                    <td>{$row['email']}</td>
                </tr>";
            }
            ?>
        </table>
    <?php else: ?>
        <h2>User Access</h2>
        <p>Welcome to your dashboard!</p>
    <?php endif; ?>
</body>
</html>
