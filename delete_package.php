<?php
include 'db_connect.php';

$success_message = "";
$error_message = "";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    if ($id > 0) {
        // Prepare and execute the SQL statement to delete the package
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirect to the view packages page
            header("Location: view_packages.php");
            exit();
        } else {
            $error_message = "Error deleting package: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Invalid package ID.";
    }

    $conn->close();
} else {
    $error_message = "No package ID specified.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Package</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #343a40;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Package</h1>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <a href="view_packages.php" class="btn">Back to Packages List</a>
    </div>
</body>
</html>
