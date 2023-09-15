<?php
// Connect to DB
$host = 'localhost';
$username = 'george';
$password = 'ergasia_3';
$db = 'kachrimanis_ergasia_3';

// Establish connection with DB
$conn = mysqli_connect("$host", "$username", "$password", "$db");

// Check if the connection is stable
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();

}

// check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// retrieve the input values from the POST request
    $company_name = $_POST["company_name"];
    $tax_id = $_POST["tax_id"];
    $street = $_POST["street"];
    $street_number = $_POST["street_number"];
    $postal_code = $_POST["postal_code"];
    $region = $_POST["region"];
    $city = $_POST["city"];
    $user_name = $_POST["user_name"];
    $user_password = $_POST["password"];
    // Generate password hash
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

// prepare the SQL statement
    $sql = "INSERT INTO company_data (company_name, tax_id, street,  street_number, postal_code, region, city, user_name, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssss", $company_name, $tax_id, $street, $street_number,
        $postal_code, $region, $city, $user_name, $hashed_password);


// Checking for same unique ID(tax_id)
    try {
// Execute the statement
        if ($stmt->execute()) {
// Insert data into the users table
            $user_sql = "INSERT INTO users (id, user_name, user_password) SELECT id, user_name, user_password FROM company_data WHERE tax_id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("s", $tax_id);
            $user_stmt->execute();

        } else {
            echo "Error: " . $stmt->error;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Error code for duplicate entry
// Redirect the user to the error page
            header('Location: AFM_error.php');
            exit();
        } else {
            echo "Error: " . $e->getMessage();
        }
    }

// close the statement and connection
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Επιτυχής καταχώρηση προσφοράς</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

</head>

<body>

<header>
    <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
        <div class="row">
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <div class="col">
                <a class="nav-link " href=index.php>Αρχική</a>
            </div>
            <div class="col">
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <div class="col">
                <a class="nav-link " href=offers_registration.php>Καταχώρηση</a>
            </div>
            <div class="col">
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <div class="col">
                <a class="nav-link" href="login.php">
                    <button type="button" class="button1">Login</button>
                </a>
            </div>
        </div>
    </nav>
</header>
<main>

    <!--Title-->
    <div class="container-lg text-center" id="title">
        <h1>Η καταχώρηση της εταιρείας ήταν επιτυχής!</h1>
    </div>

    <!-- Functions -->
    <div>
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <h2>Παρακαλώ επιλέξτε λειτουργία!</h2>

            <!-- Login Button -->
            <a href="login.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Είσοδος στην
                υπηρεσία</a>

            <!-- Registration Button -->
            <a href="registration.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Εγγραφή
                Εταιρείας</a>

            <!-- Contact Button -->
            <a href="mailto:admin@admin.admin" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Επικοινωνία
                με διαχειριστή</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>
</html>