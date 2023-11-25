<?php
session_start();
require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
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

        ul {
            list-style: none;
            padding: 0;
        }

        li {
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
        <h1>Welcome, Customer!</h1>

        <h2>Categories</h2>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <a href="10recipe_detail_customer.php?category_id=<?php echo $category['category_id']; ?>">
                        <?php echo $category['category_name']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <h2>Logout</h2>
        <p><a href="4logout.php">Logout</a></p>
    </div>
</body>
</html>
