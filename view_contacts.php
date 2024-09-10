<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
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
    <title>Your Contacts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Your Contacts</h1>
        <a href="dashboard.php">Back to Dashboard</a>

        <?php if (count($contacts) > 0): ?>
            <ul>
                <?php foreach ($contacts as $contact): ?>
                    <li><?php echo htmlspecialchars($contact['name']) . ' (' . htmlspecialchars($contact['email']) . ')'; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No contacts found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
