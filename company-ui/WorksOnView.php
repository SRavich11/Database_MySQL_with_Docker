<?php
// Include config.php to connect to the database
include('config.php');

// Redis connection
$redis = new Redis();
$redis->connect('redis', 6379); // Connect to Redis container

// Attempt to get WorksOn data from Redis cache
$cacheKey = 'works_on_list';
$cachedWorksOn = $redis->get($cacheKey);

if ($cachedWorksOn) {
    // Cache hit: Decode and use the cached data
    $worksOn = json_decode($cachedWorksOn, true);
} else {
    // Cache miss: Fetch WorksOn data from MySQL database
    try {
        $sql = "SELECT * FROM WorksOn";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $worksOn = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cache the WorksOn data in Redis for future requests (set expiration time, e.g., 1 hour)
        $redis->set($cacheKey, json_encode($worksOn), 3600); // Cache for 1 hour (3600 seconds)
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
    <title>Works On Assignments</title>
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

        /* Pagination styles (optional) */
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

    <h1>Works On Assignments</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Employee SSN</th>
                    <th>Project Number</th>
                    <th>Hours Worked</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($worksOn as $work): ?>
                    <tr>
                        <td><?php echo $work['SSN']; ?></td>
                        <td><?php echo $work['ProjectNumber']; ?></td>
                        <td><?php echo $work['Hours']; ?></td>
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