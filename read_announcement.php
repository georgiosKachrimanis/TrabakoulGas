<?php

// Set the session timeout to 5 minutes (300 seconds)
ini_set('session.gc_maxlifetime', 300);

// Start the session
session_start();

// Update the last activity time
$_SESSION['last_activity'] = time();

$user_id = $_SESSION['user_id'];

// Check the last activity time
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    // User has been inactive for 5 minutes, log them out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ανακοινώσεις</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<header>
    <!-- Main Navigation Bar -->
    <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
        <div class="row">
            <div class="col">
                <!-- Company Logo -->
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <div class="col">
                <!-- Home page link -->
                <a class="nav-link" href=index.php>Αρχική</a>
            </div>
            <div class="col">
                <!-- Search page link -->
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <div class="col">
                <!-- Announcements page link (active page) -->
                <a class="nav-link active disabled" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <!-- Navigation link: Login or Logout -->
            <div class="col">
                <?php
                if (isset($_SESSION['user_id'])) {
                    // If the user is logged in, show a logout link
                    echo '<a class="nav-link" href="logout.php"><button type="button" class="button1">Logout</button></a>';
                } else {
                    // If the user is not logged in, show a login button
                    echo '<a class="nav-link" href="login.php"><button type="button" class="button1">Login</button></a>';
                }
                ?>
            </div>
        </div>
    </nav>
</header>


<main>
    <div class="container text-center">
        <div id="title"><h1>Ανακοινώσεις</h1>

        </div>
        <?php
        // Connect to DB using mysqli
        $mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");

        // Get the ID of the announcement from the URL parameter
        $id = $_GET['id'];
        $int_id = intval($id);


        // Construct the SQL query to select the announcement with the given ID
        $sql = "SELECT * FROM announcements WHERE id = '$int_id'";

        // Execute the query and get the result
        $result = $mysqli->query($sql);

        // Check if the announcement was found
        if ($result->num_rows > 0) {
            // Fetch the result as an associative array
            $row = $result->fetch_assoc();

            // Display the title and text of the announcement
            echo "<h3>" . $row['date_string'] . "</h3>";
            echo "<h1>" . $row['title'] . "</h1>";
            echo "<p>" . $row['text'] . "</p>";
        } else {
            // If the announcement was not found, display an error message
            echo "Announcement not found.";
        }

        // Close the database connection
        $mysqli->close();
        ?>


    </div>
</main>

<footer>
    <!--Privacy and policy Links-->
    <!-- Copyright -->
    <div class="text-lg-center footer">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <a class="nav-link" href="terms_of_use.html"> Όροι χρήσης</a>
                <a class="nav-link" href="policy.html">Πολιτική Απορρήτου</a>
                Copyright: © 2022
                <a class="text-reset fw-bold" href="index.php">TrabakoulGas.com</a>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
</footer>

</body>
</html>