<?php
// MySQL connection settings
$host = 'purdue_db';  // MySQL container name (as defined in docker-compose.yml)
$dbname = 'Company';
$username = 'root'; // Your database username
$password = 'secretpass'; // Your database password ****must be encrypted when committed!! DONT EXPOSE FILE** 

// MySQL connection attempt
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected to MySQL successfully!";
} catch (PDOException $e) {
    echo "MySQL connection failed: " . $e->getMessage();
}

// Redis connection settings
$redis_host = 'redis';  // Redis container name (as defined in docker-compose.yml)
$redis_port = 6379;     // Default Redis port

// Redis connection attempt
try {
    $redis = new Redis();
    $redis->connect($redis_host, $redis_port);

    // Optional: Check Redis connection with ping
} catch (Exception $e) {
    echo "Redis connection failed: " . $e->getMessage();
}
?>
