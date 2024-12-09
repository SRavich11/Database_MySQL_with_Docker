<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Database</title>
    <style>
        /* Apply basic page structure */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1, h2 {
            color: #333;
        }

        /* Styling for the unordered list and links */
        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px; /* Space between links */
        }

        ul li a {
            text-decoration: none;
            color: #4CAF50; /* Green color for links */
            font-size: 16px;
            display: block; /* Make the links block elements for better spacing */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        ul li a:hover {
            background-color: #4CAF50; /* Green background on hover */
            color: #fff; /* White text on hover */
        }

        /* Container for better page layout */
        .container {
            width: 100%;
            max-width: 800px; /* Limit width for better readability */
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 10px;
                max-width: 100%;
            }

            ul li a {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ABC Mining Company</h1>

        <ul>
            <li><a href="addDepartment.php">Add Department</a></li>
            <li><a href="addEmployee.php">Add Employee</a></li>
            <li><a href="addProject.php">Add Project</a></li>
            <li><a href="empView.php">View Employees</a></li>
            <li><a href="projView.php">View Projects</a></li>
            <li><a href="deptView.php">View Departments</a></li>
            <li><a href="addWorksOn.php">Assign Employee to Project</a></li>
            <li><a href="WorksOnView.php">Employee-Project Relation table</a></li>
        </ul>
    </div>
</body>
</html>
