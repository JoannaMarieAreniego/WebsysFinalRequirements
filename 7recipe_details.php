<?php
session_start();
require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    echo "You must login first";
    header("Refresh: 2; url=3login.php");
    session_destroy();
    exit();
}

if (isset($_GET["recipe_id"])) {
    $recipe_id = $_GET["recipe_id"];
    
    $stmt = $pdo->prepare("SELECT * FROM meals WHERE meal_id = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    $instructions = getInstructions($pdo, $recipe_id);
    $ingredients = getIngredients($pdo, $recipe_id);
    
} else {
    echo "Recipe not found.";
    exit();
}

function getInstructions($pdo, $meal_id) {
    $stmt = $pdo->prepare("SELECT * FROM instructions WHERE meal_id = ? ORDER BY step_number");
    $stmt->execute([$meal_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getIngredients($pdo, $meal_id) {
    $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recipe Details</title>
</head>
<body>
    <h1>Recipe Details</h1>
    <h2><?php echo $recipe['meal_name']; ?></h2>
    <p>Category: <?php echo $recipe['category_id']; ?></p>
    <p>Video Link: <?php echo $recipe['video_link']; ?></p>
    <p>Image Link: <?php echo $recipe['image_link']; ?></p>
    <h3>Image</h3>
    <img src="<?php echo $recipe['image_link']; ?>" alt="Recipe Image" style="max-width: 50%;">

    <h3>Instructions</h3>
    <ol>
        <?php foreach ($instructions as $instruction) { ?>
            <li><?php echo $instruction['step_description']; ?></li>
        <?php } ?>
    </ol>

    <h3>Ingredients</h3>
    <ul>
        <?php foreach ($ingredients as $ingredient) { ?>
            <li><?php echo $ingredient['ingredient_name']; ?></li>
        <?php } ?>
    </ul>

    <p><a href="5admin.php">Back to Admin Dashboard</a></p>
</body>
</html>