<?php
// This is the list of regions and cities used for the search
include 'regions_cities.php';

// Set the session timeout to 5 minutes (300 seconds)
ini_set('session.gc_maxlifetime', 300);

// Start the session
session_start();

// Check the last activity time
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    // User has been inactive for 5 minutes, log them out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Types of Fuel
$fuel_types = [
    1 => 'Βενζίνη Απλή',
    2 => 'Βενζίνη Super',
    3 => 'Πετρέλαιο Κινήσεως',
    4 => 'Πετρέλαιο Κίνησης Super',
    5 => 'Πετρέλαιο Θέρμανσης'
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// get the values submitted through the form
    $region = $_GET['region'];
    $city = $_GET['city'];
    $fuel = $_GET['fuel_id'];


    // Connect to DB using mysqli
    $mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");
    // check for errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // construct the SQL query to get the average of available offers for the fuel type we want
    $sql_average = "SELECT AVG(price)  FROM offers WHERE fuel_id = '$fuel'";

    // construct the SQL query to first check for available offers for the fuel type we want
    $sql = "SELECT * FROM offers WHERE fuel_id='$fuel'";

    // execute the query and get the results
    $result = $mysqli->query($sql);

    // execute the query and get the average results
    $average = $mysqli->query($sql_average);
    // Fetch the result as an associative array
    $row_average = $average->fetch_assoc();
    // Get the average price as a double with 2 decimal points
    $average_price = number_format($row_average['AVG(price)'], 2, '.', '');

    // check if any offers were found
    if ($result->num_rows > 0) {
        // construct the SQL query to check for companies in the region and city we are looking for
        $sql_2 = "SELECT * FROM company_data WHERE region='$region' AND city='$city'";

        // execute the query and get the results
        $result_2 = $mysqli->query($sql_2);

        // check if any companies were found
        if ($result_2->num_rows > 0) {
            // construct the SQL query to join the two tables and get the offers for the fuel type and companies in the region and city we want
            $sql_3 = "SELECT o.*, c.* FROM offers o INNER JOIN company_data c ON o.user_id = c.id 
                WHERE o.fuel_id ='$fuel' AND c.region='$region' AND c.city='$city'";

            // Execute the query and get the results
            $result_3 = $mysqli->query($sql_3);

            // Check if any offers were found
            if ($result_3->num_rows > 0) {
                // Loop through the results and add each row to the $results array
                while ($row = $result_3->fetch_assoc()) {
                    // Convert the price from string to float
                    $row['price'] = floatval($row['price']);
                    $results[] = $row;
                }
            }

            // Sort the $results array by price
            // Create array for prices
            $prices = array_column($results, 'price');
            // Sort the $results array by price
            array_multisort($prices, SORT_ASC, $results);
        }

        // close the database connection
        $mysqli->close();
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Αναζήτηση Προσφορών</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- Header -->
<header>

    <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
        <div class="row">
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <div class="col">
                <a class="nav-link" href=index.php>Αρχική</a>
            </div>
            <div class="col"> <!--Active page-->
                <a class="nav-link active disabled" href=search.php>Αναζήτηση</a>
            </div>
            <!-- Only shown to registered users: Offers Registration -->
            <div class="col">
                <?php
                if (isset($_SESSION['user_id']) && $user_role == 1) {
                    // If the user is logged in and has role 1, show an offers registration link
                    echo '<a class="nav-link" href="offers_registration.php">Καταχώρηση</a>';
                }
                ?>
            </div>
            <div class="col">
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
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

<!-- Main -->
<main>

    <!--Title of the page-->
    <div class="container-fluid flex-wrap text-center" style="padding-top: 5%">
        <div class="row">
            <h1>Επιλογές Αναζήτησης.</h1>
        </div>
    </div>

    <!--Search function-->
    <form action="" method="get">
        <div class="container-fluid flex-wrap text-center">
            <div class="row">
                <!--Col0 empty-->
                <div class="col-3"></div>
                <!--Col1 dropdown 1 Region and City search-->
                <div class="col-2">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="region">*Περιφέρεια</label>
                        <select id="region" class="form-control" aria-label="region" aria-describedby="basic-addon1"
                                name="region" required>
                            <?php
                            foreach ($regions_cities as $perifereies => $dimoi) {
                                echo "<option value='$perifereies'>$perifereies</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="city">*Δήμος</label>
                        <select id="city" class="form-control" aria-label="city" aria-describedby="basic-addon1"
                                name="city" required>
                        </select>
                    </div>
                </div>
                <!--Col2 dropdown 2 Fuel Type -->
                <div class="col-2">
                    <div class="input-group mb-6">
                        <label class="input-group-text" for="fuel_id">*Είδος Καυσίμου</label>
                        <select class="form-select" id="fuel_id" name="fuel_id" required>
                            <option selected></option>
                            <option value="1">Βενζίνη Απλή</option>
                            <option value="2">Βενζίνη Super</option>
                            <option value="3">Πετρέλαιο Κινήσεως</option>
                            <option value="4">Πετρέλαιο Κίνησης Super</option>
                            <option value="5">Πετρέλαιο Θέρμανσης</option>
                        </select>
                    </div>
                </div>
                <!--Col3 empty-->
                <div class="col-3"></div>
            </div>
            <div class="row">
                <p>Τα πεδία με αστερίσκο (*) είναι υποχρεωτικά!</p>
            </div>
            <!-- Search and Clear Button-->
            <div class="row">
                <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
                    <button type="submit" class="btn-lg btn-secondary">Αναζήτηση</button>
                    <button type="reset" class="btn-lg btn-secondary">Καθαρισμός</button>
                </div>
            </div>

        </div>

    </form>

    <div>

        <?php if (!empty($results)) {

            // Find the price closest to the average price
            $closest_price = null;
            $closest_price_diff = null;
            foreach ($results as $row) {
                $price_diff = abs($row['price'] - $average_price);
                if ($closest_price_diff === null || $price_diff < $closest_price_diff) {
                    $closest_price = $row['price'];
                    $closest_price_diff = $price_diff;
                }
            }
            ?>
            <!-- Table of results -->
            <div class="container-fluid flex-wrap text-center" id="results">
                <table class="table" id="results">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">α/α</th>
                        <th scope="col">Επωνυμία</th>
                        <th scope="col">Διεύθυνση</th>
                        <th scope="col">Τύπος Καυσίμου</th>
                        <th scope="col">Τιμή</th>
                        <th scope="col">Λήξη Προσφοράς</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    // Print the average price

                    $counter = 1;

                    foreach ($results as $row) {
                        // Highlight the row with the closest price to the average price
                        $highlight = $row['price'] == $closest_price ? "style='background-color: lightgreen;'" : "";
                        echo "<tr $highlight>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . $row['company_name'] . "</td>";
                        echo "<td><a href='https://www.google.com/maps/search/?api=1&query=" .
                            urlencode($row['street'] . ", " . $row['street_number'] .
                                ", " . $row['postal_code'] . ", " . $row['city'] . ", " . $row['region']) .
                            "' target='_blank'>" . $row['street'] . " " . $row['street_number'] . ", " .
                            $row['postal_code'] . " " . $row['city'] . ", " . $row['region'] . "</a></td>";

                        echo "<td>" . $fuel_types[$row['fuel_id']] . "</td>";
                        echo "<td>" . $row['price'] . "€</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row['end_date'])) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <!-- No results message -->
            <div class="container-fluid flex-wrap text-center" id="no-results">
                <div class="row">
                    <p>Δεν βρέθηκαν αποτελέσματα.</p>
                </div>
            </div>
        <?php } ?>
    </div>

</main>

<footer>
    <!-- Privacy and policy Links-->
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
<!--Region and Address script-->
<script>
    $(document).ready(function () {
        $("#region").change(function () {
            var selectedRegion = $(this).val();
            var cities = <?php echo json_encode($regions_cities); ?>[selectedRegion];
            $("#city").empty();
            cities.forEach(function (city) {
                $("#city").append("<option value='" + city + "'>" + city + "</option>");
            });
        });
    });
</script>

