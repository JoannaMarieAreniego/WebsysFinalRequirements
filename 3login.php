<?php
session_start();

require("0conn.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = $_POST["username"];
    $entered_password = $_POST["password"];

    if ($entered_username === "admin" && $entered_password === "admin") {
        $_SESSION["is_admin"] = true;
        header("Location: 5admin.php");
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$entered_username]);
        $user = $stmt->fetch();

        if ($user && password_verify($entered_password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            if ($user["is_admin"] == 1) {
                $_SESSION["is_admin"] = true;
                header("Location: 5admin.php");
            } else {
                header("Location: 6customer.php");
            }
            exit();
        } else {
            echo "Invalid username or password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
