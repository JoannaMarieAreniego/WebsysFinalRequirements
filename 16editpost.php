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
        h1 {
            color: #04AA6D;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            font-size: 18px;
            color: #333;
        }

         input, textarea {
            width: 70%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 5px;
            
        }

        textarea {
            resize: vertical;
        }

        button {
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
        .btn-outline-primary {
            color: #16b978;
            padding: 10px 40px;
            border: 2px solid #16b978;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px; 
            margin-left: 10px;
        }
        .btn-outline-primary:hover {
            background-color: #16b978;
            color: #fff;
            padding: 10px 40px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px; 
            margin-left: 10px;
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
    <button class="button-secondary" onclick="window.location.href='15userposts.php'">
        <i class="fas fa-arrow-left"></i> </button>

        <h1>Edit Meal</h1>

        <form method="post" action="17processsedit.php">
            <input type="hidden" name="meal_id" value="<?php echo $meal_id; ?>">
            
        
            Meal Name: <input type="text"name="meal_name" value="<?php echo $meal['meal_name']; ?>" required>
            
            
            Video Link: <input type="text"name="video_link" value="<?php echo $meal['video_link']; ?>" required>
            
            Image: <input type="text" name="image_link" value="<?php echo $meal['image_link']; ?>" required>

            <label for="all_steps">Instructions: </label>
=======
</head>
<body>
    <div class="container">
        <h1>Edit Meal</h1>

        <form method="post" action="17processedit.php">
            <input type="hidden" name="meal_id" value="<?php echo $meal_id; ?>">
            <label for="meal_name">Meal Name:</label>
            <input type="text" name="meal_name" value="<?php echo $meal['meal_name']; ?>" required>
            
            <label for="video_link">Video Link:</label>
            <input type="text" name="video_link" value="<?php echo $meal['video_link']; ?>" required>
            
            <label for="image_link">Image Link:</label>
            <input type="text" name="image_link" value="<?php echo $meal['image_link']; ?>" required>

            <h3>Instructions</h3>
            <label for="all_steps">All Steps:</label>
>>>>>>> 5fe90fe20225a99b7c0e72287c2c9b70d1a91b11
            <textarea name="all_steps" rows="10"><?php
                // Output each step on a new line
                foreach ($instructions as $instruction) {
                    echo $instruction['step_description'] . "\n";
                }
            ?></textarea>

<<<<<<< HEAD
=======
            <h3>Ingredients</h3>
>>>>>>> 5fe90fe20225a99b7c0e72287c2c9b70d1a91b11
            <label for="all_ingredients">All Ingredients:</label>
            <textarea name="all_ingredients" rows="10"><?php
                foreach ($ingredients as $ingredient) {
                    echo $ingredient['ingredient_name'] . "\n";
                }
            ?></textarea>

<<<<<<< HEAD
            <button type="submit"  name="update_recipe" class="btn btn-outline-primary">Update</button>
        </form>
=======
            <button type="submit" name="update_recipe">Update Recipe</button>
        </form>

        <p><a href="9customer.php">Back to Categories</a></p>
>>>>>>> 5fe90fe20225a99b7c0e72287c2c9b70d1a91b11
    </div>
</body>
</html>
