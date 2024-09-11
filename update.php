<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle the update request
if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];

    // Fetch the contact from the database
    $sql = "SELECT name, email FROM contacts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $contact_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contact = $result->fetch_assoc();
    } else {
        $error = "Contact not found or you don't have permission to edit it.";
    }

    $stmt->close();
}

// Handle the form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact_id = $_POST['contact_id'];
    $new_name = $_POST['contact_name'];
    $new_email = $_POST['contact_email'];

    // Update the contact information in the database
    $sql = "UPDATE contacts SET name = ?, email = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $new_name, $new_email, $contact_id, $user_id);

    if ($stmt->execute()) {
        $message = "Contact updated successfully.";
        header("Location: read.php"); // Redirect to contacts page after update
        exit();
    } else {
        $error = "Error updating contact: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Update Contact</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (isset($contact)): ?>
            <form action="update.php" method="POST">
                <input type="hidden" name="contact_id" value="<?php echo htmlspecialchars($contact_id); ?>">
                <div class="input-group">
                    <label for="contact_name">Contact Name:</label>
                    <input type="text" id="contact_name" name="contact_name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="contact_email">Contact Email:</label>
                    <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                </div>
                <button type="submit">Update Contact</button>
            </form>
        <?php endif; ?>

        <!-- Link to go back to view contacts -->
        <a href="read.php">Back to Contacts</a>
    </div>
</body>
</html>
