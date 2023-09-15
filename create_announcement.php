<?php
// Get form data
$aTitle = $_POST['announcement_title'];
$aDate_string = $_POST['announcement_date'];
$aText = $_POST['announcement_text'];
// Connect to DB
$host = 'localhost';
$username = 'george';
$password = 'ergasia_3';
$db = 'kachrimanis_ergasia_3';

// Establish connection with DB
$conn = mysqli_connect("$host", "$username", "$password", "$db");

// Check if the connection is stable
if (!$conn) {
    header("Location: fail.php?error=db_error");
    $error = "Error connecting to the database: " . mysqli_connect_error();
}

$sql = "INSERT INTO announcements (title, text, date_string) VALUES ('$aTitle', '$aText', '$aDate_string')";
// Prepare statement and execute query to insert announcement data

if (mysqli_query($conn, $sql)) {
    // Redirect to success page
    header("Location: success.php");
    exit();
} else {
    // Database error
    header("Location: fail.php?error=db_error");
    $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close MySQLi connection
mysqli_close($conn);


?>
