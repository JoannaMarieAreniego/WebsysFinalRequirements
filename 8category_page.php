<?php
session_start();
require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Check if the category_id is provided in the query parameter
if (isset($_GET["category_id"])) {
    $category_id = $_GET["category_id"];

    // Retrieve category details from the database
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $category_name = $category["category_name"];
    } else {
        // Category not found, handle accordingly (e.g., show an error message)
        $category_name = "Category Not Found";
    }
} else {
    // category_id not provided, handle accordingly (e.g., redirect or show an error message)
    $category_name = "Category Not Selected";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Page</title>
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
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Category Page</h1>

        <h2>Category Details</h2>
        <p>Category Name: <?php echo $category_name; ?></p>

        <h2>Recipes</h2>
        <?php

        $stmt = $pdo->prepare("SELECT * FROM meals WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($recipes) > 0) {
            echo "<ul>";
            foreach ($recipes as $recipe) {
                echo "<li><a href='7recipe_details.php?recipe_id={$recipe['meal_id']}'>{$recipe['meal_name']}</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No recipes found in this category.</p>";
        }
        ?>

        <h2>Back to Admin Dashboard</h2>
        <p><a href="5admin.php">Back to Admin Dashboard</a></p>
    </div>
</body>
</html>
