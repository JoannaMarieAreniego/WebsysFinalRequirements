<?php
if (isset($_GET['meal_id'])) {
    $meal_id = $_GET['meal_id'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT image_link FROM meals WHERE meal_id = ?");
        $stmt->execute([$meal_id]);
        $image_link = $stmt->fetchColumn();

        if ($image_link) {
            // Output the image
            header("Content-type: image/jpeg"); // Adjust content type based on your image type
            readfile($image_link);
            exit();
        } else {
            // Handle if image link is not found
            echo "Image not found";
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
