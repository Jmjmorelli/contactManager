<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle contact creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (!empty($contact_name) && !empty($contact_email)) {
        // Insert contact into the database
        $sql = "INSERT INTO contacts (user_id, name, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $contact_name, $contact_email);

        if ($stmt->execute()) {
            $message = "Contact added successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add a New Contact</h1>

        <?php if (isset($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="create.php" method="POST">
            <div class="input-group">
                <label for="contact_name">Contact Name:</label>
                <input type="text" id="contact_name" name="contact_name" required>
            </div>
            <div class="input-group">
                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" required>
            </div>
            <button type="submit">Add Contact</button>
        </form>

        <!-- Link to go back to dashboard or view contacts -->
        <a href="dashboard.php">Back to Dashboard</a>
        <a href="view_contacts.php">View Contacts</a>
    </div>
</body>
</html>
