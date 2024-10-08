<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch contacts for the logged-in user
$sql = "SELECT id, name, email FROM contacts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contacts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Your Contacts</h1>

        <!-- Display contacts if available -->
        <?php if (count($contacts) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td>
                                <!-- Links to update and delete actions -->
                                <a href="update.php?id=<?php echo $contact['id']; ?>">Edit</a>
                                <a href="delete.php?id=<?php echo $contact['id']; ?>" onclick="return confirm('Are you sure you want to delete this contact?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No contacts found. <a href="dashboard.php">Add some here</a>.</p>
        <?php endif; ?>

        <!-- Link back to the dashboard or logout -->
        <a href="dashboard.php">Go back to Dashboard</a>
    </div>
</body>
</html>
