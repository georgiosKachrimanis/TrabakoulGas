<?php
// Connect to DB using mysqli
$mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");

if ($mysqli->connect_errno) {
    // Database error
    header("Location: fail.php?error=db_error");
    exit();
}

// Check if form was submitted and user is logged in as admin
if (isset($_POST['submit'])) {
    $id = $_POST['id'];

    // Prepare statement and execute query
    $stmt = $mysqli->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to announcements page
    header("Location: announcements.php");
    exit();
}

// Close database connection
$mysqli->close();
?>
