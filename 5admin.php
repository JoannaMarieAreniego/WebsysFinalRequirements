<?php
session_start();
require("0conn.php");

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: 3login.php");
    exit();
}

$loggedInUsername = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_selected"])) {
    if (isset($_POST["selected_categories"]) && is_array($_POST["selected_categories"])) {
        $selectedCategories = $_POST["selected_categories"];
        foreach ($selectedCategories as $categoryId) {
            $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
            $stmt->execute([$categoryId]);
        }

        $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"])) {
    $category_name = $_POST["category_name"];
    $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->execute([$category_name]);
}

    $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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

        form {
            margin-top: 10px;
        }

        .category {
            margin: 20px 0;
        }


        .delete-button {
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>

        <h2>Add Category</h2>
        <form method="post">
            <input type="text" name="category_name" placeholder="Category Name" required>
            <button type="submit">Add Category</button>
        </form>

        <h2>Categories</h2>
        <form method="post" id="deleteForm">
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li class="category">
                        <input type="checkbox" id="category_<?php echo $category['category_id']; ?>" name="selected_categories[]" value="<?php echo $category['category_id']; ?>">
                        <span>
                            <a href="8category_page.php?category_id=<?php echo $category['category_id']; ?>">
                                <?php echo $category['category_name']; ?>
                            </a>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" name = "delete_selected" onclick="deleteSelectedCategories()">Delete Selected</button>
        </form>
        <h2>Manage Recipes</h2>
        <p><a href="6add_recipe.php">Add New Recipe</a></p>

        <h2>Logout</h2>
        <p><a href="4logout.php">Logout</a></p>
    </div>

    <script>
        function deleteSelectedCategories() {
            const form = document.getElementById("deleteForm");
            const checkboxes = form.querySelectorAll('input[name="selected_categories[]"]');
            const selectedCategories = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedCategories.length === 0) {
                alert("Please select categories to delete.");
                return;
            }

            if (confirm("Are you sure you want to delete the selected categories?")) {
                form.submit();
            }
        }
    </script>
</body>
</html>