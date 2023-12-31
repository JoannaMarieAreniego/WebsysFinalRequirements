<<<<<<< HEAD
=======

>>>>>>> 5fe90fe20225a99b7c0e72287c2c9b70d1a91b11
<?php
session_start();
require("0conn.php");

if (isset($_GET['meal_id'])) {
    $meal_id = $_GET['meal_id'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM meals WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
    $meal = $stmt->fetch(PDO::FETCH_ASSOC);

    $instructionsStmt = $pdo->prepare("SELECT * FROM instructions WHERE meal_id = ? ORDER BY step_number");
    $instructionsStmt->execute([$meal_id]);
    $instructions = $instructionsStmt->fetchAll(PDO::FETCH_ASSOC);

    $ingredientsStmt = $pdo->prepare("SELECT * FROM ingredients WHERE meal_id = ?");
    $ingredientsStmt->execute([$meal_id]);
    $ingredients = $ingredientsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: 9customer.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recipe'])) {
    $deleteStmt = $pdo->prepare("DELETE FROM meals WHERE meal_id = ?");
    $deleteStmt->execute([$meal_id]);
    header("Location: 9customer.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_recipe'])) {
    header("Location: 16editpost.php?meal_id=$meal_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png">
    <style>
   body {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            display: flex;
            flex-wrap: wrap;
        }

        .topnav {
            background-color: #16b978;
            overflow: hidden;
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            padding-top: 90px;
            transition: top 0.3s;
        }

        .topnav a {
            float: center;
            color: #f2f2f2;
            text-align: center;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 17px;
            display: flex;
            align-items: center;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
        .topnav a i {
            margin-right: 30px;
        }

        .container {
            width: 100%;
            margin-top: 120px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        h1, h2, h3 {
            color: #04AA6D;
        }

        img {
            width: 40%;
            height: auto;
            margin-top: 10px;
        }
        a {
            color: #007BFF;
        }

        button, .shopping-list-btn {
            padding: 0;
            margin: 0;
            background: none;
            border: none;
            cursor: pointer;
            color: black;
            margin-left: 10px;
            display: center;
            align-items: center;
            font-size:15px;
        }

        .shopping-list-btn i,
        button i {
            margin-right: 10px;
        }

        p a {
            color: #16b978;
            text-decoration: none;
        }

        .clearfix::after {
                content: "";
                clear: both;
                display: table;
            }

            .logo-container {
                position: fixed;
                top: 0;
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #fff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .logo {
                display: flex;
                align-items: center;
            }

            .logo img {
                height: 50px;
                padding: 20px;
                width: auto;
                margin-right: 10px;
            }

            .logo h1 {
                font-family: cursive;
                font-size: 24px;
                margin: 0;
                color: #16b978;
            }
        .button-secondary {
            margin-top: 20px;
                margin-left: 25px;
                margin-bottom: 20px;
                color: gray;
                padding: 8px 16px;
                text-decoration: none;
                display: flex;
                align-items: center;
                border: none;
                font-size: 20px;
                background-color: transparent; 
            }
    </style>
</head>
<body>
<div class="logo-container">
        <div class="logo">
            <img src="logo.png" alt="Tastebud Logo">
            <h1>Tastebud</h1>
        </div>
    </div>
     
    <div class="topnav">
        <a href="9customer.php"><i class="fa fa-fw fa-home"></i> Home</a>
        <a href="categories.php"><i class="fas fa-fw fa-user"></i>Categories</a>
        <a href="14chat.php"><i class="fa-solid fa-comment"></i>Chat</a>
        <a href="12user_profile.php"><i class="fas fa-fw fa-user"></i> Profile</a>
        <a href="4logout.php"><i class="fas fa-fw fa-sign-out"></i> Logout</a>
</div>

    <div class="container">
    <button class="button-secondary" onclick="window.location.href='12user_profile.php'">
        <i class="fas fa-arrow-left"></i> </button>

    <h2 style="margin-top: 20px;">
    <span style="display: inline-block; margin-right: 10px;"><?php echo $meal['meal_name']; ?></span>
    <a href="shoppingList.php?meal_id=<?php echo $meal_id; ?>" class="shopping-list-btn">
    <i class="fas fa-shopping-cart"></i></a>
    <form style="display: inline-block;" method="post" action="">
        <button type="submit" name="edit_recipe"><i class="fas fa-edit"></i></button>
        <button type="submit" name="delete_recipe" onclick="return confirm('Are you sure you want to delete this recipe?')"><i class="fas fa-trash-alt"></i> </button>
</form>

</h2>
<img src="<?php echo $meal['image_link']; ?>" alt="Recipe Image"><br><br>
<a href="<?php echo $meal['video_link']; ?>" target="_blank">Watch Video</a>


    <h3 id="instructions">Instructions</h3>
    <ul>
        <?php
        foreach ($instructions as $instruction) {
            echo "<li>{$instruction['step_description']}</li>";
        }
        ?>

    <h3 id="ingredients">Ingredients</h3>
        <?php
        foreach ($ingredients as $ingredient) {
            echo "<li>{$ingredient['ingredient_name']}</li>";
        }
        ?>
    </ul>



</body>
</html>
=======
</head>
<body>
    <div class="container">
        <h1>Meal Details</h1>

        <h2>Meal Name: <?php echo $meal['meal_name']; ?></h2>
        <h3>Video</h3>
        <p>Video Link: <a href="<?php echo $meal['video_link']; ?>" target="_blank">Watch Video</a></p>
        <h3>Image</h3>
        <img src="<?php echo $meal['image_link']; ?>" alt="Recipe Image" style="max-width: 50%;">

        <h3>Instructions</h3>
        <ol>
            <?php
            foreach ($instructions as $instruction) {
                echo "<li>{$instruction['step_description']}</li>";
            }
            ?>
        </ol>

        <h3>Ingredients</h3>
        <ul>
            <?php
            foreach ($ingredients as $ingredient) {
                echo "<li>{$ingredient['ingredient_name']}</li>";
            }
            ?>
        </ul>


        <form method="post" action="">
            <button type="submit" name="edit_recipe">Edit</button>
            <button type="submit" name="delete_recipe" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</button>
        </form>

        <a href="shoppingList.php?meal_id=<?php echo $meal_id; ?>" class="shopping-list-btn">Shopping List</a>
        <p><a href="9customer.php">Back to Categories</a></p>
    </div>
</body>
</html>

>>>>>>> 5fe90fe20225a99b7c0e72287c2c9b70d1a91b11
