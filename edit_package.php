<?php
include 'db_connect.php';

$success_message = "";
$error_message = "";
$package = null;

// Check if ID parameter is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    if ($id > 0) {
        // Prepare and execute the SQL statement to retrieve the package details
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $package = $result->fetch_assoc();
        } else {
            $error_message = "Package not found.";
        }

        $stmt->close();
    } else {
        $error_message = "Invalid package ID.";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $package_name = trim($_POST['package_name']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $status = trim($_POST['status']);

    if ($id > 0 && !empty($package_name) && $price !== false && !empty($status)) {
        // Prepare and execute the SQL statement to update the package details
        $stmt = $conn->prepare("UPDATE users SET package_name = ?, price = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $package_name, $price, $status, $id);

        if ($stmt->execute()) {
            // Redirect to the view packages page
            header("Location: view_packages.php");
            exit();
        } else {
            $error_message = "Error updating package: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Invalid input data.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Package</h1>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($package): ?>
            <form method="post" action="edit_package.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($package['id']); ?>">
                <div class="form-group">
                    <label for="package_name">Package Name:</label>
                    <input type="text" id="package_name" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($package['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($package['status']); ?>" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Update Package">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
