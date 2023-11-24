<?php
session_start();
require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$meal = null; // Initialize $meal to avoid undefined variable warning

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["meal_id"])) {
    $meal_id = $_GET["meal_id"];

    // Fetch meal details
    $mealStatement = $pdo->prepare("SELECT * FROM meals WHERE meal_id = ?");
    $mealStatement->execute([$meal_id]);
    $meal = $mealStatement->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            margin: 10px 0;
        }

        p {
            font-size: 16px;
            margin: 5px 0;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Meal Details - <?php echo isset($meal['meal_name']) ? $meal['meal_name'] : 'Meal Not Found'; ?></h1>

<?php if ($meal): ?>
    <h2>Ingredients</h2>
    <p><?php echo $meal['ingredients']; ?></p>

    <h2>Video</h2>
    <p><?php echo $meal['video']; ?></p>

    <h2>Image</h2>
    <p><?php echo $meal['image']; ?></p>

    <h2>Instructions</h2>
    <p><?php echo $meal['instructions']; ?></p>
    
        <?php else: ?>
            <p>Meal not found.</p>
        <?php endif; ?>

        <h2>Back to Categories</h2>
        <p><a href="9customer.php">Back to Categories</a></p>
    </div>
</body>
</html>
