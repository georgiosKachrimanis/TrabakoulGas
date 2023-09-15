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
        $fuel_names = $result2->fetch_all(MYSQLI_ASSOC);


        // Prepare statement and execute query for offers
        $stmt3 = $mysqli->prepare("SELECT * FROM offers WHERE user_id = ?");
        $stmt3->bind_param('i', $user_id);
        $stmt3->execute();

        // Fetch the data from the query result
        $result3 = $stmt3->get_result();
        $offers = $result3->fetch_all(MYSQLI_ASSOC);


        // Calculate statistics
        $totalPrice = 0;
        $minPrice = PHP_INT_MAX;
        $maxPrice = 0;
        $activeOffers = 0;
        $offerStats = [];
        foreach ($offers as $offer) {
            $price = $offer['price'];
            $totalPrice += $price;
            if ($price < $minPrice) {
                $minPrice = $price;
            }
            if ($price > $maxPrice) {
                $maxPrice = $price;
            }
            $isActive = (new DateTime()) < (new DateTime($offer['end_date']));
            if ($isActive) {
                $activeOffers++;
            }

            // Find the corresponding fuel name
            $fuel_name = '';
            foreach ($fuel_names as $fuel) {
                if ($fuel['fuel_id'] == $offer['fuel_id']) {
                    $fuel_name = $fuel['fuel_type'];
                    break;
                }
            }

            // Create an array with offer stats and add it to offerStats
            $offerStats[] = [
                'fuel_name' => $fuel_name,
                'price' => $price,
                'isActive' => $isActive
            ];
        }

        // Creating a new DOMDocument object
        $dom = new DOMDocument('1.0', 'UTF-8');

        // Create root element
        $company = $dom->createElement('company');

        // Create company_name element and append it to company
        $company_name = $dom->createElement('company_name', $company_name);
        $company->appendChild($company_name);

        // Create tax_id element and append it to company
        $tax_id_element = $dom->createElement('tax_id', $tax_id);
        $company->appendChild($tax_id_element);

        // Create location element
        $location = $dom->createElement('location');

        // Create and append location child elements
        $location->appendChild($dom->createElement('street', $street));
        $location->appendChild($dom->createElement('street_number', $street_number));
        $location->appendChild($dom->createElement('postal_code', $postal_code));
        $location->appendChild($dom->createElement('region', $region));
        $location->appendChild($dom->createElement('city', $municipality));

        // Append location to company
        $company->appendChild($location);

        // Create offers element
        $offersElement = $dom->createElement('offers');

        // Loop through each offer
        foreach ($offers as $offer) {
            // Find the corresponding fuel name
            $fuel_name = '';
            foreach ($fuel_names as $fuel) {
                if ($fuel['fuel_id'] == $offer['fuel_id']) {
                    $fuel_name = $fuel['fuel_type'];
                    break;
                }
            }

            // Create an offer element
            $offerElement = $dom->createElement('offer');

            // Check if the offer is active
            $isActive = (new DateTime()) < (new DateTime($offer['end_date'])) ? 'true' : 'false';

            // Set 'active' attribute
            $offerElement->setAttribute('active', $isActive);

            // Create and append offer child elements
            $offerElement->appendChild($dom->createElement('fuel_id', $offer['fuel_id']));
            $offerElement->appendChild($dom->createElement('fuel_type', $fuel_name));
            $offerElement->appendChild($dom->createElement('end_date', $offer['end_date']));
            $offerElement->appendChild($dom->createElement('price', $offer['price']));

            // Append offer to offers
            $offersElement->appendChild($offerElement);
        }


        // Append offers to company
        $company->appendChild($offersElement);


        // Add the offers_stats to the XML
        $offersStatsElement = $dom->createElement('global_offers');

        foreach ($fuel_names as $fuel) {
            // Prepare statement and execute query for stats
            $stmt4 = $mysqli->prepare("SELECT MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price FROM offers WHERE fuel_id = ?");
            $stmt4->bind_param('i', $fuel['fuel_id']);
            $stmt4->execute();

            // Fetch the data from the query result
            $result4 = $stmt4->get_result();
            $stats = $result4->fetch_assoc();

            // Create a stats element for each fuel type
            $offerStatElement = $dom->createElement('global_offer');

            // Create and append stats child elements
            $offerStatElement->appendChild($dom->createElement('fuel_name', $fuel['fuel_type']));
            $offerStatElement->appendChild($dom->createElement('minPrice', number_format((float)$stats['min_price'], 2, '.', '')));
            $offerStatElement->appendChild($dom->createElement('maxPrice', number_format((float)$stats['max_price'], 2, '.', '')));
            $offerStatElement->appendChild($dom->createElement('avgPrice', number_format((float)$stats['avg_price'], 2, '.', '')));

            // Append offer_stat to offers_stats
            $offersStatsElement->appendChild($offerStatElement);
        }

        // Append offers_stats to company
        $company->appendChild($offersStatsElement);
        // Append company to dom
        $dom->appendChild($company);

        // Save to XML file

        $xmlFile = 'company_' . $tax_id . '.xml';
        $xmlString = $dom->saveXML();
        $dtd = '<!DOCTYPE company SYSTEM "company.dtd">';
        $xmlWithDtd = str_replace('<company>', $dtd . '<company>', $xmlString);
        file_put_contents($xmlFile, $xmlWithDtd);


        // Enable user error handling
        libxml_use_internal_errors(true);

        // Create a new DOMDocument to load the XML for validation
        $xmlForValidation = new DOMDocument();
        $xmlForValidation->load($xmlFile);

        // Validate the XML file against the DTD
        if (!$xmlForValidation->validate()) {
            $errors = libxml_get_errors(); // Get all errors
            foreach ($errors as $error) {
                echo "Error {$error->code} at line {$error->line}:\n";
                echo trim($error->message);
                echo "\n\n";
            }
            libxml_clear_errors(); // Clear errors for next XML operations
            exit();

        }

        // Load the validated XML for transformation
        $xml = new DOMDocument();
        $xml->load($xmlFile);

        $xsl = new DOMDocument();
        $xsl->load('xml_statistics.xsl');

        // Configure the transformer
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);

        // Transform the XML into HTML using the XSL file
        echo $proc->transformToXML($xml);


    }
}
