<?php
// Include config.php to connect to the database
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis-container', 6379); // Replace 'redis_host' with your Redis container or server hostname

// Define a cache key for the employee data (you can use any unique identifier)
$cacheKey = 'employees_list';

// Try to fetch the employees list from Redis
$cachedEmployees = $redis->get($cacheKey);

if ($cachedEmployees) {
    // Cache hit: Decode and use the cached data
    $employees = json_decode($cachedEmployees, true);
} else {
    // Cache miss: Fetch employees from MySQL database
    try {
        $sql = "SELECT * FROM Employee";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cache the employees data in Redis for future requests (set expiration time, e.g., 1 hour)
        $redis->set($cacheKey, json_encode($employees), 3600); // Cache for 1 hour (3600 seconds)
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General page styles */
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

        /* Table styles */
        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 16px;
        }

        .table-container {
            overflow-x: auto;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            table {
                width: 95%;
            }

            th, td {
                font-size: 14px;
            }
        }

        /* Pagination styles (if needed) */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #4CAF50;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
            border: 1px solid #4CAF50;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Employee List</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>SSN</th>
                    <th>First Name</th>
                    <th>Middle Initials</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                    <th>Salary</th>
                    <th>Address</th>
                    <th>Birthday</th>
                    <th>Department Number</th>
                    <th>Super SSN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee['SSN']; ?></td>
                        <td><?php echo $employee['FirstName']; ?></td>
                        <td><?php echo $employee['MiddleInitials']; ?></td>
                        <td><?php echo $employee['LastName']; ?></td>
                        <td><?php echo $employee['Sex']; ?></td>
                        <td><?php echo $employee['Salary']; ?></td>
                        <td><?php echo $employee['Address']; ?></td>
                        <td><?php echo $employee['Birthday']; ?></td>
                        <td><?php echo $employee['DepartmentNumber']; ?></td>
                        <td><?php echo $employee['SuperSSN']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination section (optional) -->
    <div class="pagination">
        <!-- Add pagination links here if necessary -->
        <a href="#">Previous</a>
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">Next</a>
    </div>

</body>
</html>
