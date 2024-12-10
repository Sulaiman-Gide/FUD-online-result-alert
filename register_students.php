<?php
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: admin_login.php");
        exit;
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "online_result";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone_number'];
        $department_id = 1;
    
        // Check if the student_id already exists
        $check_sql = "SELECT * FROM students WHERE student_id = '$student_id'";
        $result = $conn->query($check_sql);
    
        if ($result->num_rows > 0) {
            $error_message = "A student with registration number '$student_id' already exists.";
        } else {
            // Insert the new student
            $sql = "INSERT INTO students (student_id, name, email, password, phone_number, department_id) 
                    VALUES ('$student_id', '$name', '$email', '$password', '$phone_number', '$department_id')";
    
            if ($conn->query($sql) === TRUE) {
                $success_message = "New student profile created successfully.";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Federal University Dutse</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            display: flex;
            overflow-y: hidden;
            background-color: #f9fafb;
            user-select: none;
            height: 100%;
            overflow: hidden;
        }

        .sidebar {
            display: none;
            flex-direction: column;
            width: 13rem;
            height: 100%;
            margin-left: 0.3rem;
            margin-top: 0.3rem;
            margin-bottom: 0.3rem;
            border-radius: 0.375rem;
            background-color: white;
            overflow-y: scroll;
            padding: 0.5rem 0.7rem;
            border: 1px solid #e5e7eb;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }
        .sidebar h2 {
            font-weight: 800;
            font-size: 1.52rem;
            text-align: center;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .sidebar h1 {
            font-weight: 800;
            font-size: 1rem;
            text-align: center;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar span {
            display: block;
            border: 1px solid #d1d5db;
            width: 100%;
            margin: 1rem 0;
        }

        .sidebar a,
        .sidebar div {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            
            cursor: pointer;
            padding: 0.4rem 0.5rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar div:hover {
            background-color: #1f2937;
            color: white;
        }

        .sidebar a.active {
            background-color: #1f2937;
            color: white;
        }

        .sidebar a.logout {
            background-color: red;
            color: white;
        }

        .sidebar a.logout:hover {
            background-color: #8B0000;
        }

        .sidebar a:hover .icon,
        .sidebar div:hover .icon {
            color: white;
        }

        .sidebar a .icon,
        .sidebar div .icon {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        .sidebar a h1,
        .sidebar div h1 {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .main-content {
            background-color: #f7f9fc;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 100%;
            height: 100%;
            padding: 2rem 0 4rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            overflow-x: hidden;
            overflow-y: auto;
        }

        h2 {
            font-size: 1.2rem;
            margin-left: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            margin-left: 20px;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        form {
            width: 95%;
            padding: 20px;
            margin: 0 auto;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            background-color: teal;
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.75rem 1.2rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: rgb(0, 44, 44);
        }

        @media (min-width: 640px) {
            .sidebar {
                display: flex;
            }
        }

        @media (min-width: 768px) {
            .sidebar {
                width: 20%;
            }
            .main-content {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <span></span>
            <a href="admin_dashboard.php">
                <h1>Dashboard</h1>
            </a>
            <a href="manage_staff.php">
                <h1>Manage Lecturers</h1>
            </a>
            <a href="add_staff.php">
                <h1>Add Lecturer</h1>
            </a>
            <span></span>
            <a href="register_students.php" class="active">
                <h1>Register Student</h1>
            </a>
            <a href="manage_students.php">
                <h1>View Students</h1>
            </a>
            <span></span>
            <a href="index.html">
                <h1>Home</h1>
            </a>
            <a href="logout.php" class="logout">
                <h1>Logout</h1>
            </a>
        </div>

        <div class="main-content">
            <h2>Add Student Profile</h2>
            <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
            <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
            <form action="register_students.php" method="POST">
                <div class="form-group">
                    <label for="student_id">Registration Number:</label>
                    <input type="text" id="student_id" name="student_id" required>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number">
                </div>

                <div class="btn-div">
                    <input type="submit" value="Add Student" class="btn">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
