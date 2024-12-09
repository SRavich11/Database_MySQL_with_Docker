<?php
// Include config.php to connect to the database
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

// Attempt to get department data from Redis cache
$cacheKey = 'departments_list';
$cachedDepartments = $redis->get($cacheKey);

if ($cachedDepartments) {
    // Cache hit: Decode and use the cached data
    $departments = json_decode($cachedDepartments, true);
} else {
    // Cache miss: Fetch departments from MySQL database
    try {
        $sql = "SELECT * FROM Department";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cache the department data in Redis for future requests (set expiration time, e.g., 1 hour)
        $redis->set($cacheKey, json_encode($departments), 3600); // Cache for 1 hour (3600 seconds)
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
    <title>Department List</title>
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
            width: 80%;
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

        /* Button styles */
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 20px auto;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>

    <h1>Department List</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Department Number</th>
                    <th>Department Name</th>
                    <th>Manager SSN</th>
                    <th>Manager Start Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                    <tr>
                        <td><?php echo $department['DepartmentNumber']; ?></td>
                        <td><?php echo $department['DepartmentName']; ?></td>
                        <td><?php echo $department['ManagerSSN']; ?></td>
                        <td><?php echo $department['ManagerStartDate']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align: center;">
        <a href="addDepartment.php" class="btn">Add New Department</a>
    </div>

</body>
</html>
