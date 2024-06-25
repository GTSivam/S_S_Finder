<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['output'])) {
    $output = $_POST['output'];

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="url_status_codes.txt"');
    echo $output;
    exit;
} else {
    echo "No data to download.";
}
?>
