<?php
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $departmentName = htmlspecialchars($_POST['DepartmentName']);
    $departmentNumber = $_POST['DepartmentNumber'];
    $managerSSN = $_POST['ManagerSSN'];
    $managerStartDate = $_POST['ManagerStartDate'];

    // Insert department into the database
    $query = "INSERT INTO Department (DepartmentName, DepartmentNumber, ManagerSSN, ManagerStartDate) 
              VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$departmentName, $departmentNumber, $managerSSN, $managerStartDate]);

    // Invalidate or update the cache after adding a new department
    $redis->del('departments_list'); // Delete the old cached list
    echo "<p class='success'>Department added successfully!</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General page styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            padding: 20px 0;
            background-color: #ffffff;
            margin: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form styling */
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="number"], input[type="date"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success {
            text-align: center;
            color: green;
            font-size: 16px;
            margin-top: 20px;
        }

        .form-container input:focus {
            border-color: #4CAF50;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    <h1>Add New Department</h1>

    <div class="form-container">
        <form method="POST">
            <label for="DepartmentName">Department Name:</label>
            <input type="text" id="DepartmentName" name="DepartmentName" required>

            <label for="DepartmentNumber">Department Number:</label>
            <input type="number" id="DepartmentNumber" name="DepartmentNumber" required>

            <label for="ManagerSSN">Manager SSN:</label>
            <input type="text" id="ManagerSSN" name="ManagerSSN" required>

            <label for="ManagerStartDate">Manager Start Date:</label>
            <input type="date" id="ManagerStartDate" name="ManagerStartDate" required>

            <input type="submit" value="Add Department">
        </form>
    </div>

</body>
</html>

