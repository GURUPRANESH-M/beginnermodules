<?php
require 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user details based on the logged-in username
$username = $_SESSION['username'];
$stmt = $records_conn->prepare("SELECT name, address, mobile, age FROM user_details WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $address, $mobile, $age);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form submitted is for updating details
    if (isset($_POST['update_details'])) {
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $mobile = trim($_POST['mobile']);
        $age = intval(trim($_POST['age']));

        // Update details in the database
        $update_stmt = $records_conn->prepare("UPDATE user_details SET name = ?, address = ?, mobile = ?, age = ? WHERE username = ?");
        $update_stmt->bind_param("sssis", $name, $address, $mobile, $age, $username);
        if ($update_stmt->execute()) {
            $success_message = "Details updated successfully.";
        } else {
            $error_message = "Error updating details.";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4A90E2;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }

        button {
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #357ABD;
            transform: scale(1.05);
        }

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
    <title>Display Details When Logged In</title>
    <script>
        function toggleEditForm() {
            var editForm = document.getElementById("editForm");
            if (editForm.style.display === "none") {
                editForm.style.display = "block";
            } else {
                editForm.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
        
        <button onclick="toggleEditForm()"><i class="fas fa-user-edit"></i> Edit Details</button> <!-- Edit button with icon -->

        <div id="editForm" class="edit-form"> <!-- Edit form initially hidden -->
            <h3>Edit Your Details</h3>
            <form action="" method="POST">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>

                <label>Mobile:</label>
                <input type="text" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>

                <label>Age:</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required>

                <button type="submit" name="update_details"><i class="fas fa-save"></i> Update Details</button>
            </form>
        </div>

        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        
        <a href="logout.php"><button><i class="fas fa-sign-out-alt"></i> Logout</button></a>
    </div>
</body>
</html>
