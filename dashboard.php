<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle contact form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_contact'])) {
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];
    $user_id = $_SESSION['user_id'];

    // Check if contact name and email are not empty
    if (!empty($contact_name) && !empty($contact_email)) {
        // Insert contact into the database
        $sql = "INSERT INTO contacts (user_id, name, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $contact_name, $contact_email);

        if ($stmt->execute()) {
            $message = "Contact added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Contact name and email are required.";
    }
}

// Fetch contacts for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM contacts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <!-- Add Contact Form -->
        <h2>Add Contact</h2>
        <?php if (isset($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="dashboard.php" method="POST">
            <div class="input-group">
                <label for="contact_name">Contact Name:</label>
                <input type="text" id="contact_name" name="contact_name" required>
            </div>
            <div class="input-group">
                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" required>
            </div>
            <button type="submit" name="add_contact">Add Contact</button>
        </form>

        <!-- Links to other pages -->
        <h2>Actions</h2>
        <a href="view_contacts.php">View Contacts</a>
        <a href="index.php">Logout</a>
    </div>
</body>
</html>
