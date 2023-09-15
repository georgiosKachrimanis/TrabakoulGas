<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ανεπιτυχής καταχώρηση εταιρείας/χρήστη.</title>
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
            <!-- Logo -->
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <!-- Home link -->
            <div class="col">
                <a class="nav-link " href=index.php>Αρχική</a>
            </div>
            <!-- Search link -->
            <div class="col">
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <!-- Announcements link -->
            <div class="col">
                <a class="nav-link" href="announcements.php">Ανακοινώσεις</a>
            </div>
            <!-- Login button -->
            <div class="col">
                <a class="nav-link" href="login.php">
                    <button type="button" class="button1">Login</button>
                </a>
            </div>
        </div>
    </nav>
</header>

<main>

    <!--Message-->
    <div class="container-lg text-center" id="title">
        <h1>Η Εγγραφή της επιχείρησης σας δεν είναι δυνατόν να ολοκληρωθεί!<br></h1>
        <h2><br>Ο Α.Φ.Μ υπάρχει ήδη στο σύστημα!<br>
            Προσπαθήστε να κάνετε είσοδο με τα αναγνωριστικά σας ή<br>
            ελέγξτε τον Α.Φ.Μ που εισάγατε!
        </h2>
    </div>
    <!--Επιλογή λειτουργείας -->
    <div>
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <h2>Παρακαλώ επιλέξτε λειτουργία!</h2>
            <a href="login.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Είσοδος στην
                υπηρεσία</a>
            <a href="registration.php" class="btn-secondary btn-lg" role="button" style="text-decoration: none">Εγγραφή
                Εταιρείας</a>
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
                <a class="text-reset fw-bold" href="indexphp">TrabakoulGas.com</a>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
</footer>

</body>
</html>