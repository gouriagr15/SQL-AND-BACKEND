<?php
include('db.php');

$first_name = $surname = $mobile_no = $email = $password = '';
$resume = ''; 
$course = $current_year = $cgpa = $skills = $projects = $work_exp = $current_city = '';
$role = ''; 
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $mobile_no = trim($_POST['mobile_no'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $resume = $_FILES['resume'];
    $course = trim($_POST['course'] ?? '');
    $current_year = trim($_POST['current_year'] ?? '');
    $cgpa = trim($_POST['cgpa'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $projects = trim($_POST['projects'] ?? '');
    $work_exp = trim($_POST['work_exp'] ?? '');
    $current_city = trim($_POST['current_city'] ?? '');
    $role = trim($_POST['role'] ?? ''); // Get role from POST

    if (!preg_match('/^[0-9]{10}$/', $mobile_no)) {
        $error = 'Mobile number must be 10 digits.';
    }

    $checkEmailQuery = $conn->prepare("SELECT * FROM user_info WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $result = $checkEmailQuery->get_result();
    if ($result->num_rows > 0) {
        $error = 'Email already registered.';
    }

    if (empty($error)) {
        $resume_name = time() . '_' . basename($resume['name']); 
        $target_dir = "uploads/"; 
        $target_file = $target_dir . $resume_name;

        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_type, ['pdf', 'doc', 'docx']) && $resume['size'] <= 5000000) { 
            if (move_uploaded_file($resume['tmp_name'], $target_file)) {
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO user_info (first_name, surname, mobile_no, email, password, resume, course, current_year, cgpa, skills, projects, work_exp, current_city, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssisssssssssss", $first_name, $surname, $mobile_no, $email, $hashed_password, $target_file, $course, $current_year, $cgpa, $skills, $projects, $work_exp, $current_city, $role);
                    if ($stmt->execute()) {
                        echo "Registration successful!";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                $error = "Error uploading your resume.";
            }
        } else {
            $error = "Invalid file type or size exceeded.";
        }
    } 

    if (!empty($error)) {
        echo $error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body>
    <h2>Registration Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" required><br>

        <label for="mobile_no">Mobile No:</label>
        <input type="text" name="mobile_no" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="resume">Resume:</label>
        <input type="file" name="resume" accept=".pdf,.doc,.docx" required><br>

        <label for="course">Course:</label>
        <input type="number" name="course" required><br>

        <label for="current_year">Current Year:</label>
        <input type="text" name="current_year" required><br>

        <label for="cgpa">CGPA:</label>
        <input type="number" name="cgpa" required><br>

        <label for="skills">Skills:</label>
        <input type="text" name="skills" required><br>

        <label for="projects">Projects:</label>
        <input type="text" name="projects" required><br>

        <label for="work_exp">Work Experience:</label>
        <input type="text" name="work_exp" required><br>

        <label for="current_city">Current City:</label>
        <input type="text" name="current_city" required><br>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="user">User</option>
            <option value="coordinator">Coordinator</option>
            <option value="admin">Admin</option>
        </select><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
