<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST["username"];
    $user_password = $_POST["password"];

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
        $stmt = $pdo->prepare("SELECT id, user_name, user_password FROM users WHERE user_name = :user_name");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($user_password, $row["user_password"])) {
                // Login successful
                $_SESSION["user_id"] = $row["id"];
                header("Location: index.php");
                exit();
            } else {
                // Wrong password
                header("Location: fail.php?error=wrong_credentials");
            }
        } else {
            // User not found
            header("Location: fail.php?error=user_not_found");
        }
    } catch (PDOException $e) {
        // Database error
        header("Location: fail.php?error=db_error");
        $error = "Error connecting to the database: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Είσοδος</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

<header>
    <!-- Navigation bar with a logo and links -->
    <nav class="nav justify-content-center flex-wrap">
        <!-- Container for the navigation bar items -->
        <div class="row">
            <!-- Logo for the website -->
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <!-- Link to the homepage -->
            <div class="col">
                <a class="nav-link" href=index.php>Αρχική</a>
            </div>
            <!-- Link to the search page -->
            <div class="col">
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <!-- Link to the announcements page -->
            <div class="col">
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <!-- Disabled login button - user needs to log in to access the page -->
            <div class="col">
                <a class="nav-link disabled" href="login.php">
                    <button type="button" class="button1" disabled>Login</button>
                </a>
            </div>
        </div>
    </nav>
</header>


<main>

    <!--Title-->
    <div class="container-lg text-center" id="title">
        <h1>Καλώς ήρθατε στην TrabakoulGas</h1>
    </div>

    <!-- Input Form-->
    <form action="" name="user_login" class="user_login" method="post">

        <!--User Name input-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text" id="user_name">Όνομα Χρήστη:</span>
                    <input type="email" class="form-control" aria-label="company_name"
                           aria-describedby="basic-addon1" required placeholder="Username(e-mail)" name="username">
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--User Password Input-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text" id="tax_id">Κωδικός Χρήστη:</span>
                    <input type="password" id="user_password" class="form-control" aria-label="user_password"
                           aria-describedby="basic-addon1" required
                           minlength="8" maxlength="20" placeholder="password(8-20 char.)" name="password">
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--Accept button and log on option-->
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <button type="submit" class="btn-lg btn-secondary">Είσοδος</button>
            <a class="nav-link" id="log_on" href="registration.php">Εγγραφή νέας επιχείρησης</a>
        </div>
    </form>
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