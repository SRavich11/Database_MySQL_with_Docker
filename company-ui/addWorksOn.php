<?php
// Include config.php to connect to the database
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

// Initialize variables
$employeeSSN = $projectNumber = $hours = "";
$employeeSSNErr = $projectNumberErr = $hoursErr = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if (empty($_POST["SSN"])) {
        $employeeSSNErr = "Employee SSN is required.";
    } else {
        $employeeSSN = $_POST["SSN"];
    }

    if (empty($_POST["projectNumber"])) {
        $projectNumberErr = "Project Number is required.";
    } else {
        $projectNumber = $_POST["projectNumber"];
    }

    if (empty($_POST["hours"])) {
        $hoursErr = "Hours worked is required.";
    } else {
        $hours = $_POST["hours"];
        // Validate that the hours entered is a valid decimal number (up to 5 digits total, 2 after decimal)
        if (!preg_match("/^\d{1,3}(\.\d{1,2})?$/", $hours)) {
            $hoursErr = "Invalid hours format. Enter a number with up to 2 decimal places (e.g., 99.99).";
        }
    }

    // If no errors, insert the record into the database
    if (empty($employeeSSNErr) && empty($projectNumberErr) && empty($hoursErr)) {
        try {
            // Prepare the SQL query
            $sql = "INSERT INTO WorksOn (SSN, ProjectNumber, Hours) 
                    VALUES (:SSN, :projectNumber, :hours)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':SSN' => $employeeSSN, ':projectNumber' => $projectNumber, ':hours' => $hours]);

            // Invalidate or update the cache after adding the work assignment
            $redis->del('works_on_list'); // Delete the old cached list

            // Success message
            echo "<p class='success'>Workhour Records added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!-- HTML Form for adding a Work Assignment -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Work Assignment</title>
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

        input[type="text"], input[type="submit"] {
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

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
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

    <h1>Add Work Assignment</h1>

    <div class="form-container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="SSN">Employee SSN:</label>
            <input type="text" id="SSN" name="SSN" value="<?php echo $employeeSSN; ?>">
            <span class="error"><?php echo $employeeSSNErr; ?></span><br><br>

            <label for="projectNumber">Project Number:</label>
            <input type="text" id="projectNumber" name="projectNumber" value="<?php echo $projectNumber; ?>">
            <span class="error"><?php echo $projectNumberErr; ?></span><br><br>

            <label for="hours">Hours Worked:</label>
            <input type="text" id="hours" name="hours" value="<?php echo $hours; ?>">
            <span class="error"><?php echo $hoursErr; ?></span><br><br>

            <input type="submit" value="Add Work Assignment">
        </form>
    </div>

</body>
</html>
