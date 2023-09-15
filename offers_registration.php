<?php
// Set the session timeout to 5 minutes (300 seconds)
ini_set('session.gc_maxlifetime', 300);

// Start the session
session_start();

// Check the last activity time
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300) || empty($_SESSION['user_id'])) {
    // User has been inactive for 5 minutes, log them out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Connect to DB using mysqli
    $mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");

    // Check for connection errors
    if ($mysqli->connect_errno) {
        // Database error
        header("Location: fail.php?error=db_error");
        exit();
    } else {
        // Prepare statement and execute query for
        $stmt = $mysqli->prepare("SELECT * FROM company_data WHERE id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        // Fetch the data from the query result
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Set the values of the variables
        $company_name = $row['company_name'];
        $tax_id = $row['tax_id'];
        $street = $row['street'];
        $street_number = $row['street_number'];
        $postal_code = $row['postal_code'];
        $region = $row['region'];
        $municipality = $row['city'];

        // Prepare statement and execute query for fuel_types
        $stmt2 = $mysqli->prepare("SELECT * FROM fuels");
        $stmt2->execute();

        // Fetch the data from the query result
        $result2 = $stmt2->get_result();
        $fuel_types = $result2->fetch_all(MYSQLI_ASSOC);
    }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Καταχώρηση Προσφορών</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
<!--header-->
<header>
    <!-- This is the main navigation bar, which is horizontally centered. -->
    <nav class="nav justify-content-center" aria-label="Main Navigation">
        <!-- This row contains the different navigation links, each in a separate column. -->
        <div class="row">
            <!-- This column contains the company logo, which links to the home page. -->
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <!-- These columns contain links to the different pages of the website. -->
            <div class="col">
                <a class="nav-link " href=index.php>Αρχική</a>
            </div>
            <div class="col">
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <div class="col"> <!-- This column contains the link to the current active page, which is disabled. -->
                <a class="nav-link active disabled" href=offers_registration.php>Καταχώρηση</a>
            </div>
            <div class="col">
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <div class="col">
                <!-- This column contains the logout button, which is a styled button element inside a link element. -->
                <a class="nav-link" href="logout.php">
                    <button type="button" class="button1">Logout</button>
                </a>
            </div>
        </div>
    </nav>
</header>

<!--main-->
<main>

    <!--Title-->
    <div class="container-fluid text-center" id="title">
        <h1>Καταχώρηση Προσφοράς</h1>
    </div>

    <!--Registration form The fields will be populated by data from the DB-->
    <div class="container-fluid flex-wrap text-center">
        <form action="offer_registrattion_success.php" method="post">

            <!--Company Name -->
            <div class="row">
                <div class="col"></div>

                <div class="col">
                    <div class="input-group mb-6">
                        <span class="input-group-text" id="company_name">Επωνυμία Επιχειρήσεως</span>
                        <input type="text" class="form-control" aria-label="company_name"
                               aria-describedby="basic-addon1" name="company_name" required
                               value="<?php echo $company_name; ?>" readonly>
                    </div>
                </div>

                <div class="col"></div>
            </div>

            <!--Tax ID number-->
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <div class="input-group mb-6">
                        <span class="input-group-text" id="tax_id">Α.Φ.Μ</span>
                        <input type="text" class="form-control" aria-label="tax_id" aria-describedby="basic-addon1"
                               required minlength="9" maxlength="9" name="tax_id" value="<?php echo $tax_id; ?>"
                               readonly>
                    </div>
                </div>
            </div>

            <!--Address Street name-->
            <div class="row">
                <div class="col"></div>

                <!--Street Name-->
                <div class="col">
                    <div class="input-group mb-6">
                        <span class="input-group-text">Οδός</span>
                        <input type="text" class="form-control" aria-label="address" aria-describedby="basic-addon1"
                               required id="street" value="<?php echo $street; ?>" readonly>
                    </div>
                </div>
                <div class="col"></div>
            </div>

            <!--Address Street Number and Postal Code-->
            <div class="row">
                <div class="col"></div>
                <!--Street Number-->
                <div class="col-sm-2">

                    <div class="input-group mb-6">
                        <span class="input-group-text">Αριθμός</span>
                        <input type="text" class="form-control" aria-label="street number"
                               name="street_number" id="street_name" value="<?php echo $street_number; ?>" readonly>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="input-group mb-6">
                        <span class="input-group-text">Ταχυδρομικός Κώδικας</span>
                        <input type="text" class="form-control" aria-label="postal code"
                               name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>" readonly>
                    </div>
                </div>

                <div class="col"></div>
            </div>

            <!--Municipality-->
            <div class="row">
                <div class="col"></div>

                <div class="col">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="municipality">Δήμος</label>
                        <input type="text" class="form-control" aria-label="municipality" name="municipality"
                               id="municipality" value="<?php echo $municipality; ?>" readonly>
                    </div>
                </div>

                <div class="col"></div>
            </div>

            <!--Region-->
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="region">Περιφέρεια</label>
                        <input type="text" class="form-control" aria-label="region" name="region"
                               id="redion" value="<?php echo $region; ?>" readonly>
                    </div>
                </div>
                <div class="col"></div>
            </div>

            <!--Fuel type-->
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="fuel_type">Τύπος Καυσίμου</label>
                        <select class="form-select" id="fuel_type" name="fuel_type" required>
                            <option selected disabled hidden value=""></option>
                            <?php foreach ($fuel_types as $fuel_type) { ?>
                                <option value="<?php echo $fuel_type['fuel_id']; ?>"><?php echo $fuel_type['fuel_type']; ?></option>
                            <?php } ?>
                        </select>*
                    </div>
                </div>

            </div>

            <!--Price discount and offer end date-->
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                    <label class="input-group mb-6">
                        <span class="input-group-text" id="price">Τιμή</span>
                        <input type="number" required name="price" min="0" value="0.00" step=".01">*
                    </label>
                </div>

                <div class="col-2">
                    <label class="input-group mb-6 justify-content-end">
                        <span class="input-group-text align-self-end" id="end_date">Τέλος Προσφοράς</span>
                        <input type="date" name="end_date" required>*
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-4"></div>
                <div class="col-2"><i>* όλα τα πεδία με αστερίσκο είναι υποχρεωτικά</i></div>
            </div>

            <!-- user_id-->
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <!--Accept and clear buttons-->
            <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
                <button type="submit" class="btn-lg btn-secondary">Αποστολή</button>
                <button type="reset" class="btn-lg btn-secondary">Καθαρισμός</button>
            </div>
        </form>
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <a href="xml_creation.php" target="_blank">
                <button id="createXML" class="btn-lg btn-secondary">Δημιουργία XML</button>
            </a>
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
