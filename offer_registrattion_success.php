<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $fuel_id = $_POST['fuel_type'];
    $user_id = $_POST['user_id'];
    $price = $_POST['price'];
    $end_date = $_POST['end_date'];

    // Connect to DB using mysqli
    $mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");

    // Check for connection errors
    if ($mysqli->connect_errno) {
        // Database error
        header("Location: fail.php?error=db_error");
        exit();
    } else {
        // Check if offer exists for this user and fuel
        $stmt = $mysqli->prepare("SELECT * FROM offers WHERE user_id = ? AND fuel_id = ?");
        $stmt->bind_param('ii', $user_id, $fuel_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Offer already exists, update end_date and price
            $stmt = $mysqli->prepare("UPDATE offers SET end_date = ?, price = ? WHERE user_id = ? AND fuel_id = ?");
            $stmt->bind_param('sdii', $end_date, $price, $user_id, $fuel_id);
            $stmt->execute();
        } else {
            // Offer does not exist, insert new row
            $stmt = $mysqli->prepare("INSERT INTO offers (user_id, fuel_id, end_date, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iisd', $user_id, $fuel_id, $end_date, $price);
            $stmt->execute();
        }

        // Close statement and database connection
        $stmt->close();
        $mysqli->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Page refresh timer-->
    <meta http-equiv="refresh" content="20; url = offers_registration.php"/>
    <title>Επιτυχής καταχώρηση προσφοράς</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

</head>

<body>

<header>
    <!-- Navigation bar with logo and links -->
    <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
        <div class="row">
            <div class="col">
                <!-- Logo -->
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <div class="col">
                <!-- Link to the home page -->
                <a class="nav-link " href=index.php>Αρχική</a>
            </div>
            <div class="col">
                <!-- Link to the search page -->
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <div class="col">
                <!-- Link to the offers registration page -->
                <a class="nav-link " href=offers_registration.php>Καταχώρηση</a>
            </div>
            <div class="col">
                <!-- Link to the announcements page -->
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <div class="col">
                <!-- Logout button -->
                <a class="nav-link" href="logout.php">
                    <button type="button" class="button1">Logout</button>
                </a>
            </div>
        </div>
    </nav>
</header>

<main>
    <!-- Display title of the page -->
    <div class="container-lg text-center" id="title">
        <h1>Η Προσφορά καταχωρήθηκε με επιτυχία</h1>
    </div>
    <!-- Provide options for user to select further actions -->
    <div>
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <h2>Παρακαλώ επιλέξτε λειτουργία!</h2>
            <!-- Link to offer registration page -->
            <a href="offers_registration.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Καταχώρηση
                νέας προσφοράς</a>
            <!-- Link to home page -->
            <a href="index.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Αρχική
                Σελίδα</a>
            <!-- Display message that user will return to previous page if no action is taken -->
            <p>Θα επιστρέψετε στην προηγούμενη οθόνη εάν δεν επιλέξετε.</p>
        </div>
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