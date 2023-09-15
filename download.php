<?php
// Retrieve the user ID from the query string
$tax_id = isset($_GET['tax_id']) ? intval($_GET['tax_id']) : 0;

// Make sure the file name is safe
if ($tax_id > 0) {
    // Specify the path to the XML files
    $path = '/Applications/XAMPP/xamppfiles/htdocs/ergasia_4/';

    // Concatenate the user ID to the path to get the specific file for the user
    $file = $path . 'company_' . $tax_id . '.xml';

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Flush system output buffer
        readfile($file);
        exit;
    } else {
        // Handle case where file does not exist
        echo "File does not exist.";
        exit;
    }
} else {
    // Handle invalid user IDs here
    echo "User does not exist!!! ERROR";
    exit;
}
