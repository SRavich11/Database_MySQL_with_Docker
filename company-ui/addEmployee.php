<?php
// Include the config file with PDO setup
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect and sanitize input data
    $SSN = isset($_POST['SSN']) ? htmlspecialchars($_POST['SSN']) : null;
    $FirstName = isset($_POST['FirstName']) ? htmlspecialchars($_POST['FirstName']) : null;
    $MiddleInitials = isset($_POST['MiddleInitials']) ? htmlspecialchars($_POST['MiddleInitials']) : null; // New field for middle initials
    $LastName = isset($_POST['LastName']) ? htmlspecialchars($_POST['LastName']) : null;
    $Sex = isset($_POST['Sex']) ? htmlspecialchars($_POST['Sex']) : null;
    $Salary = isset($_POST['Salary']) ? htmlspecialchars($_POST['Salary']) : null;
    $Address = isset($_POST['Address']) ? htmlspecialchars($_POST['Address']) : null;
    $Birthday = isset($_POST['Birthday']) ? htmlspecialchars($_POST['Birthday']) : null;
    $DepartmentNumber = isset($_POST['DepartmentNumber']) ? htmlspecialchars($_POST['DepartmentNumber']) : null;
    $SuperSSN = isset($_POST['SuperSSN']) ? htmlspecialchars($_POST['SuperSSN']) : null;

    // Check if SSN and other required fields are not empty
    if (empty($SSN) || empty($FirstName) || empty($LastName) || empty($Sex) || empty($Salary) || empty($Address) || empty($Birthday) || empty($DepartmentNumber)) {
        echo "<p class='error'>All fields are required!</p>";
    } else {

        try {
            // Check if SuperSSN exists in the database if provided
            if ($SuperSSN) {
                // Query to check if SuperSSN exists
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Employee WHERE SSN = ?");
                $stmt->execute([$SuperSSN]);
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    // If SuperSSN doesn't exist, show an error
                    echo "<p class='error'>Error: Supervisor SSN does not exist!</p>";
                    exit;
                }
            }

            // Prepare the SQL query to insert data into Employee table
            $query = "INSERT INTO Employee (SSN, FirstName, MiddleInitials, LastName, Sex, Salary, Address, Birthday, DepartmentNumber, SuperSSN) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);

            // Execute the query with the provided form data
            $stmt->execute([$SSN, $FirstName, $MiddleInitials, $LastName, $Sex, $Salary, $Address, $Birthday, $DepartmentNumber, $SuperSSN]);

            // Invalidate or update the cache after adding a new employee
            $redis->del('employees_list'); // Delete the old cached list of employees

            // Success message
            echo "<p class='success'>Employee added successfully!</p>";
        } catch (PDOException $e) {
            // Catch and display any errors that occur
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!-- HTML Form for adding an Employee -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
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

        .error {
            text-align: center;
            color: red;
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

    <h1>Add New Employee</h1>

    <div class="form-container">
        <form method="POST" action="addEmployee.php">
            <label for="SSN">SSN:</label>
            <input type="text" id="SSN" name="SSN" required>

            <label for="FirstName">First Name:</label>
            <input type="text" id="FirstName" name="FirstName" required>

            <label for="MiddleInitials">Middle Initials:</label>
            <input type="text" id="MiddleInitials" name="MiddleInitials" maxlength="2"><br> <!-- Middle Initials field -->

            <label for="LastName">Last Name:</label>
            <input type="text" id="LastName" name="LastName" required>

            <label for="Sex">Sex:</label>
            <input type="text" id="Sex" name="Sex" required>

            <label for="Salary">Salary:</label>
            <input type="number" id="Salary" name="Salary" required>

            <label for="Address">Address:</label>
            <input type="text" id="Address" name="Address" required>

            <label for="Birthday">Birthday:</label>
            <input type="date" id="Birthday" name="Birthday" required>

            <label for="DepartmentNumber">Department Number:</label>
            <input type="text" id="DepartmentNumber" name="DepartmentNumber" required>

            <label for="SuperSSN">Supervisor SSN (optional):</label>
            <input type="text" id="SuperSSN" name="SuperSSN" placeholder="Enter supervisor's SSN (if any)">

            <input type="submit" value="Add Employee">
        </form>
    </div>

</body>
</html>

