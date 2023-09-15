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

// Check user role
$user_role = "visitor";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Connect to DB using PDO
    $dsn = 'mysql:host=localhost;dbname=kachrimanis_ergasia_3';
    $username = 'george';
    $password = 'ergasia_3';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

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
?>

<script>
    function openPopup() {
        document.getElementById("popup").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }
</script>

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
            <!-- Offers registration page link only for registered users-->
            <div class="col">
                <?php
                if (isset($_SESSION['user_id']) && $user_role == 1) {
                    // If the user is logged in and has role 1, show an offers registration link
                    echo '<a class="nav-link" href="offers_registration.php">Καταχώρηση</a>';
                }
                ?>
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
            <?php // If the user is logged in and has role admin(0), show a create announcement button
            if (isset($_SESSION['user_id']) && $user_role == 0) {

                ?>
                <button onclick="openPopup()" class="btn-lg btn-secondary">Δημιουργία νέας ανακοίνωσης.</button>
                <?php
            }
            ?>
        </div>

        <!-- Create new announcement button -->
        <div>
            <div id="popup" class="popup">
                <div class="popup-content">
                    <span class="close" onclick="closePopup()">&times;</span>
                    <h2>Δημιουργία νέας ανακοίνωσης</h2>
                    <form action="create_announcement.php" class="pop-up" method="POST">
                        <label for="announcement_title">Τίτλος Ανακοίνωσης:</label>
                        <input type="text" id="announcement_title" name="announcement_title">
                        <br>
                        <label for="announcement_date">Ημερομηνία Καταχώρησης:</label>
                        <input type="date" id="announcement_date" name="announcement_date" required>
                        <br>
                        <label for="announcement_text">Κείμενο:</label>
                        <textarea id="announcement_text" name="announcement_text" required></textarea>
                        <br>
                        <button type="submit" name="submit" class="btn-lg btn-secondary">Αποστολή</button>
                        <button type="reset" class="btn-lg btn-secondary">Καθαρισμός</button>
                    </form>
                </div>

            </div>
        </div>


        <div class="container text-start announcement border border-dark border-5">
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

                while ($stmt->fetch()) {
                    // Output each announcement in a list item
                    echo "<li>";
                    echo "<h3 style='float:left; margin-right:10px'>" . date("d/m/Y", strtotime($date_string)) . "</h3>";
                    if ($user_role === 0) {
                        echo "<form action='delete_announcement.php' method='post' style='float:right'>";
                        echo "<input type='hidden' name='id' value='$id'>";
                        echo "<button type='submit' name='submit' class='btn-lg btn-secondary' onclick='return confirm(\"Are you sure you want to delete this announcement?\")'>Delete</button>";
                        echo "</form>";
                    }
                    echo "<div style='clear:both'></div>";
                    echo "<a href='read_announcement.php?id=$id'><h2>$title</h2></a>";
                    echo "<textarea readonly rows='10' style='margin: 10px 0; width: 80%; resize: none;'>$text</textarea>";
                    echo "</li>";
                }
                // Close statement and database connection
                $stmt->close();
                $mysqli->close();
                ?>
            </ul>
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