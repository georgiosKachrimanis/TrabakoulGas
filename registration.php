<?php
require 'regions_cities.php';

function regions_and_cities($regions_cities)
{
    foreach ($regions_cities as $perifereies => $dimoi) {
        echo "<option value='$perifereies'>$perifereies</option>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Εγγραφή Εταιρείας</title>
    <link rel="stylesheet" href="css/style_trabakoulgas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>

    <nav class="nav justify-content-center" aria-label="Main Navigation">
        <div class="row">
            <div class="col">
                <a href=index.php aria-label="TrabakoulGas"><img
                            src="img/logo.png" style="width:auto; height: 5rem" alt="Company Image"></a>
            </div>
            <div class="col">
                <a class="nav-link" href=index.php>Αρχική</a>
            </div>
            <div class="col">
                <a class="nav-link" href=search.php>Αναζήτηση</a>
            </div>
            <div class="col">
                <a class="nav-link" href=offers_registration.php>Καταχώρηση</a>
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
    <div class="container text-center" id="title">
        <h1>Εγγραφή Εταιρείας</h1>
    </div>

    <div class="container text-center">


    </div>
    <!--Registration Form-->
    <form action="registration_report.php" method="POST">

        <!--Company Name-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text" id="company_name">Επωνυμία Επιχειρήσεως</span>
                    <input type="text" class="form-control" aria-label="company_name"
                           aria-describedby="basic-addon1" name="company_name" required
                           placeholder="Η επωνυμία της επιχείρησης σας">*
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
                           required minlength="9" maxlength="9" name="tax_id" placeholder="Πρέπει να είναι 9 ψηφία">*
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
                    <input type="text" class="form-control" aria-label="street" aria-describedby="basic-addon1"
                           required id="street" name="street" placeholder="π.χ. Μεγάλου Αλεξάνδρου">*
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
                    <input type="text" class="form-control" aria-label="street_number"
                           required name="street_number" id="street_number" placeholder="π.χ 1-Α">*
                </div>
            </div>
            <!--Postal Code-->
            <div class="col-sm-2">
                <div class="input-group mb-6">
                    <span class="input-group-text">Ταχυδρομικός Κώδικας</span>
                    <input type="text" class="form-control" aria-label="postal code"
                           required name="postal_code" maxlength="5" minlength="5" id="postal_code"
                           placeholder="π.χ. 12345">*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--Region-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <label class="input-group-text" for="region">Περιφερειακή Ενότητα</label>
                    <select id="region" class="form-control" aria-label="region" aria-describedby="basic-addon1"
                            name="region" required>
                        <?php
                        regions_and_cities($regions_cities);
                        ?>
                    </select>*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--Municipality-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <label class="input-group-text" for="city">Δήμος</label>
                    <select id="city" class="form-control" aria-label="city" aria-describedby="basic-addon1"
                            name="city" required>
                    </select>*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--User Name-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text">Username</span>
                    <input type="email" class="form-control" aria-label="user_name" aria-describedby="basic-addon1"
                           required placeholder="To email σας" name="user_name" id="user_name">*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--Password-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text">Συνθηματικό</span>
                    <input type="password" class="form-control" aria-label="password" aria-describedby="basic-addon1"
                           name="password" id="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                           placeholder="8 ψηφία ελάχιστο, με 1 ψηφίο να είναι κεφαλαίο, 1 μικρό και ένα αριθμός υποχρεωτικά!">*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--Password confirmation/check-->
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="input-group mb-6">
                    <span class="input-group-text">Επιβεβαίωση Συνθηματικού</span>
                    <input type="password" class="form-control"
                           placeholder="Επιβεβαίωση συνθηματικού" aria-label="confirm_password"
                           required id="confirm_password">*
                </div>
            </div>
            <div class="col"></div>
        </div>

        <!--extra instructions-->
        <div class="row">
            <div class="col"></div>
            <div class="col"><i>Όλα τα πεδία με αστερίσκο(*) είναι υποχρεωτικά!</i></div>
            <div class="col"></div>
        </div>

        <!--Accept and clear buttons-->
        <div class="col-md-6 p-lg-6 mx-auto my-5 text-center">
            <button type="submit" name="submit" class="btn-lg btn-secondary">Αποστολή</button>
            <button type="reset" class="btn-lg btn-secondary">Καθαρισμός</button>
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

<!--Password check script-->
<script>
    var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        if (password.value !== confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>

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

</body>
</html>