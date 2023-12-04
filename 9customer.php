<?php
session_start();
require("0conn.php");

$loggedInUsername = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM meals ORDER BY date_created ASC");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
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
            flex-grow: 1;
            margin: 60px auto 20px;
            background-color: #fff;
            width: 100%;
        }

        .recipe-box {
            margin-right: 10px;
            box-sizing: border-box;
            float: left;
            padding: 10px;
            border-radius: 15px;
            background: white;
            margin-bottom: 20px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            width: calc(33.33% - 20px); 
            box-sizing: border-box;
            margin-left: 10px;
        }
        .recipe-box img {
            width: 100%; /* Make the image fill the container */
            height: 200px; /* Set a fixed height for consistency */
            object-fit: cover; 
            border-radius: 10px; 
        }

        h1 {
            font-size: 24px;
            margin-left: 60px;
            margin-top: 20px;
            margin-bottom: 40px;;
            color: black;
        }

        .button-primary {
            background-color: #16b978;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .button-primary:hover {
            background-color: #128a61;
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
        .search-container {
            margin-top: 80px;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 20px;
        }

        .search-container label {
            font-size: 18px;
            margin-right: 10px;
        }
        .search-container input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 12px;
            padding-right: 500px;
            margin-left:350px;
            background-color: #f2f2f2;
            border: none;

        }
        .search-container button {
            padding: 10px 16px;
            font-size: 16px;
            background-color: #16b978;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
    <div class="search-container">
        <form action="" method="GET">
            <input type="text" placeholder= "Search" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>


        <h1>Customer Recipes</h1>

        <form action="" method="GET">
            <label for="search">Search Ingredients or Meal Name:</label>
            <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($loggedInUsername)) { ?>
            <h2>Profile</h2>
            <p><a href="12user_profile.php">Profile</a></p>
        
        <!-- padesign ako netong Login to keneme -->
        <?php } else { ?>
            <p>Login to view your profile.</p>
        <?php } ?>

        <div class="clearfix">
            <?php foreach ($recipes as $recipe) { ?>
                <div class="recipe-box">
                    <h2><?php echo $recipe['meal_name']; ?></h2>
                    <img src="<?php echo $recipe['image_link']; ?>" style="max-width: 100%;">
                    <p>Date Created: <?php echo $recipe['date_created']; ?></p>
                    <p><a href="11meal_details.php?meal_id=<?php echo $recipe['meal_id']; ?>">View Details</a></p>
                </li>
            <?php } ?>
        </ul>
        <h2>Logout</h2>
        <p><a href="4logout.php">Logout</a></p>
    </div>

</body>
</html>

<?php
function getCategoryName($pdo, $category_id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    return $category ? $category['category_name'] : 'Unknown';
}
?>
