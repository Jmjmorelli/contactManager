<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];

    // Check if the contact exists and belongs to the logged-in user
    $sql = "DELETE FROM contacts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $contact_id, $user_id);

    if ($stmt->execute()) {
        $message = "Contact deleted successfully.";
    } else {
        $error = "Error deleting contact.";
    }

    $stmt->close();
} else {
    $error = "No contact ID provided.";
}

$conn->close();

// Redirect back to read.php after deletion
header("Location: read.php");
exit();
?>
