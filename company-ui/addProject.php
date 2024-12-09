<?php
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $projectName = htmlspecialchars($_POST['ProjectName']);
    $projectNumber = $_POST['ProjectNumber'];
    $location = htmlspecialchars($_POST['Location']);
    $departmentNumber = $_POST['DepartmentNumber'];

    // Insert project into the database
    $query = "INSERT INTO Project (ProjectName, ProjectNumber, Location, DepartmentNumber) 
              VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$projectName, $projectNumber, $location, $departmentNumber]);

    // Invalidate or update the cache after adding a new project
    $redis->del('projects_list'); // Delete the old cached list
    
    echo "<p class='success'>Project added successfully!</p>";
}
?>

<!-- HTML Form for adding a Project -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
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

        .form-container {
            max-width: 700px;
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

        input[type="text"], input[type="number"], input[type="submit"] {
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

    <h1>Add New Project</h1>

    <div class="form-container">
        <form method="POST" action="addProject.php">
            <label for="ProjectName">Project Name:</label>
            <input type="text" id="ProjectName" name="ProjectName" required>

            <label for="ProjectNumber">Project Number:</label>
            <input type="number" id="ProjectNumber" name="ProjectNumber" required>

            <label for="Location">Location:</label>
            <input type="text" id="Location" name="Location" required>

            <label for="DepartmentNumber">Department Number:</label>
            <input type="number" id="DepartmentNumber" name="DepartmentNumber" required>

            <input type="submit" value="Add Project">
        </form>
    </div>

</body>
</html>
