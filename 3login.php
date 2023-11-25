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

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$entered_username]);
    $user = $stmt->fetch();

    if ($user && password_verify($entered_password, $user["password"])) {
        $_SESSION["username"] = $entered_username; // Use the entered username
        if ($entered_username === "admin") {
            $_SESSION["is_admin"] = true;
            header("Location: 5admin.php");
        } else {
            $_SESSION["is_admin"] = false;
            header("Location: 9customer.php");
        }
        exit();
    } else {
        echo "Invalid username or password. Please try again.";
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