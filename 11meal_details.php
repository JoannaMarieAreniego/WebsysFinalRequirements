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

    // Fetch meal details
    $stmt = $pdo->prepare("SELECT * FROM meals WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
    $meal = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch instructions
    $instructionsStmt = $pdo->prepare("SELECT * FROM instructions WHERE meal_id = ? ORDER BY step_number");
    $instructionsStmt->execute([$meal_id]);
    $instructions = $instructionsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch ingredients
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
            cursor: pointer;
        }
    </style>
    <script>
        function getYouTubeThumbnail(apiKey, videoId, elementId) {
            var apiUrl = 'https://www.googleapis.com/youtube/v3/videos';
            var requestUrl = `${apiUrl}?id=${videoId}&key=${apiKey}&part=snippet`;

            fetch(requestUrl)
                .then(response => response.json())
                .then(data => {
                    var thumbnailUrl = data.items[0].snippet.thumbnails.medium.url;
                    document.getElementById(elementId).innerHTML = `<img src="${thumbnailUrl}" alt="Video Thumbnail" style="max-width: 100%;">`;
                })
                .catch(error => console.error('Error fetching YouTube video details:', error));
        }
    </script>
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

        <p><a href="9customer.php">Back to Categories</a></p>
    </div>
</body>
</html>
