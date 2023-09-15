<?php
$dtd_file_path = 'company.dtd'; // Replace with your DTD file path

if (is_readable($dtd_file_path)) {
    echo 'The file is readable';
} else {
    echo 'The file is not readable';
}

?>
