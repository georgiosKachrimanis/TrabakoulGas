<?php
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


// Update the last activity time
$_SESSION['last_activity'] = time();

$user_id = $_SESSION['user_id'];
// Connect to DB using PDO
$dsn = 'mysql:host=localhost;dbname=kachrimanis_ergasia_3';
$username = 'george';
$password = 'ergasia_3';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

// Check user role
$user_role = "visitor";
if (isset($_SESSION['user_id'])) {


    // User Role
    try {
        $pdo = new PDO($dsn, $username, $password, $options);

        // Prepare statement and execute query
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_role = $row["role"];
        }
    } catch (PDOException $e) {
        // Database error
        header("Location: fail.php?error=db_error");
        $error = "Error connecting to the database: " . $e->getMessage();
    }
}
// Offers Search
try {
    $pdo = new PDO($dsn, $username, $password, $options);


    // Write the SQL query to select all offers grouped by fuel type
    $sql_prices = "SELECT fuels.fuel_type, MAX(offers.price) AS max_price, MIN(offers.price) AS min_price, 
            ROUND(AVG(offers.price), 2) AS avg_price
            FROM offers JOIN fuels ON offers.fuel_id = fuels.fuel_id GROUP BY fuels.fuel_type;";

    // Execute the query and fetch the results
    $stmt = $pdo->query($sql_prices);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*// Display the results in a table
    echo "<table>";
    echo "<tr><th>Fuel Type</th><th>Max Price</th><th>Min Price</th><th>Avg Price</th></tr>";
    foreach ($results as $row) {
        echo "<tr><td>" . $row['fuel_type'] . "</td><td>" . $row['max_price'] . "</td><td>" . $row['min_price'] . "</td><td>" . $row['avg_price'] . "</td></tr>";
    }
    echo "</table>";*/

} catch (PDOException $e) {
    // Database error
    echo "Error connecting to the database: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Αρχική Σελίδα</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

<header>
    <!-- Navigation bar -->
    <nav class="nav justify-content-center flex-wrap" aria-label="Main Navigation">
        <!-- Navigation bar items -->
        <div class="row">
            <!-- Company logo -->
            <div class="col">
                <a href="index.php" aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <!-- Active page: Home -->
            <div class="col">
                <a class="nav-link active disabled" href="index.php">Αρχική</a>
            </div>
            <!-- Navigation link: Search -->
            <div class="col">
                <a class="nav-link" href="search.php">Αναζήτηση</a>
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
            <!-- Navigation link: Announcements -->
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


<main>

    <div class="container-fluid flex-wrap text-center" style="padding-top: 3%">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-4>">
                <h1 style="display: inline">Ημερήσια σύνοψη τιμών </h1>
                <h2 id="current_date" style="display: inline"></h2>
                <div class="col-2"></div>
            </div>

        </div>
    </div>

    <div class="container-fluid flex-wrap text-center" style="padding-top: 1%">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-4>">
                <ul class="noBullet">

                    <?php
                    // Loop through the results and display each fuel type with its max, min, and avg prices
                    foreach ($results as $row) {
                        echo "<li><h2>" . $row['fuel_type'] . "</h2>";
                        echo "<p>Μέγιστη: " . $row['max_price'] . " / Ελάχιστη: " . $row['min_price'] . " / Μέση: " . $row['avg_price'] . "</p></li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="col-2"></div>
        </div>
    </div>


    <div class="container-fluid flex-wrap text-center" style="padding-top: 1%">
        <div class="row">
            <h1>Τελευταίες Ανακοινώσεις</h1>
            <div class="container text-start announcement border border-dark border-5 w-50">
                <ul>
                    <?php
                    // Connect to DB using mysqli
                    $mysqli = new mysqli("localhost", "george", "ergasia_3", "kachrimanis_ergasia_3");
                    if ($mysqli->connect_errno) {
                        // Database error
                        header("Location: fail.php?error=db_error");
                        exit();
                    }

                    // Prepare statement and execute query
                    $stmt = $mysqli->prepare("SELECT id, title, text, date_string FROM announcements ORDER BY id DESC");
                    $stmt->execute();
                    $stmt->bind_result($id, $title, $text, $date_string);

                    $count = 0;
                    while ($stmt->fetch() && $count < 3) {
                        // Output each announcement in a list item
                        echo "<li>";
                        echo "<h3 style='float:left; margin-right:10px'>" . date("d/m/Y", strtotime($date_string)) . "</h3>";
                        echo "<div style='clear:both'></div>";
                        echo "<a href='read_announcement.php?id=$id'><h2>$title</h2></a>";
                        echo "</li>";
                        $count++;
                    }
                    // Close statement and database connection
                    $stmt->close();
                    $mysqli->close();
                    ?>
                </ul>
                <div class="col-2"></div>
            </div>


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

<script>
    /* Date Script */
    date = new Date();
    year = date.getFullYear();
    month = date.getMonth() + 1;
    day = date.getDate();
    document.getElementById("current_date").innerHTML = (day) + "/" + month + "/" + year;
    document.getElementById("year").innerHTML = year;
</script>
</html>
