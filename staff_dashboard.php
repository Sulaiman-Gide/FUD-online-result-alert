<?php
    session_start();
    if (!isset($_SESSION['staff_logged_in'])) {
        header("Location: staff_login.php");
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

    // Fetch the number of students from the database
    $query = "SELECT COUNT(*) as total_students FROM students";
    $result = $conn->query($query);
    $totalStudents = $result->fetch_assoc()['total_students'];

    // Fetch the number of administrators from the database
    $query = "SELECT COUNT(*) as total_admins FROM administrators";
    $result = $conn->query($query);
    $totalAdmins = $result->fetch_assoc()['total_admins'];

    // Hardcoded values for passes and fails
    $totalPasses = 59;
    $totalFails = 29;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Federal University Dutse - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
                background: white;
                padding: 2rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 100%;
                box-sizing: border-box;
                width: 100%;
                height: full;
                padding: 20px 20px 30px 10px;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: flex-start;
                overflow-y: auto;
                overflow-x: hidden;
                user-select: none;
            }

            @media (min-width: 640px) {
                .sidebar {
                    display: flex;
                }
                .main-content {
                    width: 83.333333%;
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
                <h2>Staff Panel</h2>
                <span></span>
                <a href="staff_dashboard.php" class="active">
                    <h1>Dashboard</h1>
                </a>
                <a href="upload_results.php">
                    <h1>Upload Results</h1>
                </a>
                <span></span>
                <a href="staff_View_students.php">
                    <h1>View Students</h1>
                </a>
                <a href="index.html">
                    <i class="fa-solid fa-house icon"></i>
                    <h1>Home</h1>
                </a>
                <span></span>
                <a href="logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket icon"></i>
                    <h1>Logout</h1>
                </a>

            </div>
            <main class="main-content">
                <canvas id="myChart" style="width:100%"></canvas>
                <script>
                    // PHP variables passed to JavaScript
                    const totalStudents = <?php echo json_encode($totalStudents); ?>;
                    const totalAdmins = <?php echo json_encode($totalAdmins); ?>;
                    const totalPasses = <?php echo json_encode($totalPasses); ?>;
                    const totalFails = <?php echo json_encode($totalFails); ?>;

                    // Chart data
                    const xValues = ["Total Staffs", "Total Students", "Total Passes", "Total Fails"];
                    const yValues = [totalAdmins, totalStudents, totalPasses, totalFails];
                    const barColors = ["orange", "blue", "green", "red"];

                    new Chart("myChart", {
                    type: "bar",
                    data: {
                        labels: xValues,
                        datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                        }]
                    },
                    options: {
                        legend: {display: false},
                        title: {
                            display: true,
                            text: "Computer Depertment Online Results & Staffs Statistics | FUD"
                        }
                    }
                    });
                </script>
            </main>
        </div>
    </body>
</html>
