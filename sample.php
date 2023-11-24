<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: 3login.php");
    exit();
}

require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    echo "You must login as an admin to access this page.";
    header("Refresh: 2; Location: 5admin.php");
    exit();
}

$recipe_preview = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["recipe_name"]) &&
        isset($_POST["category_id"]) &&
        isset($_POST["video_link"]) &&
        isset($_POST["instructions"]) &&
        isset($_POST["ingredients"])
    ) {
        $recipe_name = $_POST["recipe_name"];
        $category_id = $_POST["category_id"];
        $video_link = $_POST["video_link"];

        $stmt = $pdo->prepare("INSERT INTO meals (meal_name, category_id, video_link) VALUES (?, ?, ?)");
        $stmt->execute([$recipe_name, $category_id, $video_link]);

        $meal_id = $pdo->lastInsertId();

        $instructions = explode("\n", $_POST["instructions"]);
        foreach ($instructions as $step_number => $step_description) {
            $step_number = $step_number + 1;
            $stmt = $pdo->prepare("INSERT INTO instructions (meal_id, step_number, step_description) VALUES (?, ?, ?)");
            $stmt->execute([$meal_id, $step_number, trim($step_description)]);
        }

        $ingredients = explode("\n", $_POST["ingredients"]);
        foreach ($ingredients as $ingredient_name) {
            $stmt = $pdo->prepare("INSERT INTO ingredients (meal_id, ingredient_name) VALUES (?, ?)");
            $stmt->execute([$meal_id, trim($ingredient_name)]);
        }

        // Create a preview of the added recipe
        $recipe_preview = generateRecipePreview($pdo, $meal_id);
    }
}

function generateRecipePreview($pdo, $meal_id) {
    // Retrieve the recipe details from the database
    $stmt = $pdo->prepare("SELECT * FROM meals WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
    $recipe = $stmt->fetch();

    // Retrieve the instructions for the recipe
    $stmt = $pdo->prepare("SELECT * FROM instructions WHERE meal_id = ? ORDER BY step_number");
    $stmt->execute([$meal_id]);
    $instructions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve the ingredients for the recipe
    $stmt = $pdo->prepare("SELECT * FROM ingredients WHERE meal_id = ?");
    $stmt->execute([$meal_id]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $preview = "<h2>Recipe Preview</h2>";
    $preview .= "<h3>{$recipe['meal_name']}</h3>";
    $preview .= "<p>Category: {$recipe['category_id']}</p>";
    $preview .= "<p>Video Link: {$recipe['video_link']}</p>";

    $preview .= "<h3>Instructions</h3>";
    $preview .= "<ol>";
    foreach ($instructions as $instruction) {
        $preview .= "<li>{$instruction['step_description']}</li>";
    }
    $preview .= "</ol>";

    $preview .= "<h3>Ingredients</h3>";
    $preview .= "<ul>";
    foreach ($ingredients as $ingredient) {
        $preview .= "<li>{$ingredient['ingredient_name']}</li>";
    }
    $preview .= "</ul>";

    return $preview;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Recipe</title>
    <style>
        #form-section {
            display: block;
        }

        #preview-section {
            display: none;
        }

        #buttons {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script>
        function togglePreview() {
            var formSection = document.getElementById("form-section");
            var previewSection = document.getElementById("preview-section");
            var previewButton = document.getElementById("preview-button");
            var addButton = document.getElementById("add-button");
            var editButton = document.getElementById("edit-button");

            if (formSection.style.display === "block") {
                formSection.style.display = "none";
                previewSection.style.display = "block";
                previewButton.innerText = "Edit";
                addButton.style.display = "none";
                editButton.style.display = "inline";
                toggleReadOnly(true);
            } else {
                formSection.style.display = "block";
                previewSection.style.display = "none";
                previewButton.innerText = "Preview";
                addButton.style.display = "none";
                editButton.style.display = "inline";
                toggleReadOnly(false);
            }
        }

        function toggleReadOnly(readonly) {
            var inputs = document.getElementsByTagName("input");
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].readOnly = readonly;
            }
            var selects = document.getElementsByTagName("select");
            for (var i = 0; i < selects.length; i++) {
                selects[i].disabled = readonly;
            }
            var textareas = document.getElementsByTagName("textarea");
            for (var i = 0; i < textareas.length; i++) {
                textareas[i].readOnly = readonly;
            }
        }
    </script>
</head>
<body>
    <h1>Add New Recipe</h1>
    <div id="form-section">
        <form method="post">
            <div>
                <label for="recipe_name">Recipe Name:</label>
                <input type="text" name="recipe_name" id="recipe_name" required>
            </div>
            <div>
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($categories as $category) {
                        echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="video_link">Video Link:</label>
                <input type="text" name="video_link" id="video_link" required>
            </div>
            <div>
                <label for="instructions">Instructions (one step per line):</label>
                <textarea name="instructions" id="instructions" rows="5" required></textarea>
            </div>
            <div>
                <label for="ingredients">Ingredients (one ingredient per line):</label>
                <textarea name="ingredients" id="ingredients" rows="5" required></textarea>
            </div>
            <div id="buttons">
                <button id="preview-button" type="button" onclick="togglePreview()">Preview</button>
                <button id="add-button" type="submit" style="display: none;">Add Recipe</button>
                <button id="edit-button" type="button" style="display: none;">Edit</button>
            </div>
        </form>
    </div>
    
    <div id="preview-section">
        <?php echo $recipe_preview; ?>
        <div id="buttons">
            <button id="preview-button" type="button" onclick="togglePreview()">Edit</button>
            <button id="add-button" type="submit" style="display: none;">Add Recipe</button>
        </div>
    </div>

    <h2>Go back to <a href="5admin.php">Admin Dashboard</a></h2>
</body>
</html>


