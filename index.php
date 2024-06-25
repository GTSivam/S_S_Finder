<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GTSivam</title>
    <style>
        body {
            background-color: #000;
            color: #00FF00;
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #00FF00;
            padding: 8px;
        }
        h1, h2, label, input, p {
            color: #00FF00;
        }
        input[type="text"], input[type="submit"], input[type="file"] {
            background-color: #111;
            color: #00FF00;
            border: 1px solid #00FF00;
            padding: 5px 10px;
            border-radius: 3px;
        }
        input[type="submit"]:hover {
            background-color: #00FF00;
            color: #111;
        }
    </style>
</head>
<body>
<center>
    <img src="hypesec.jpg" alt="Logo">
    <h2>Site Status Checker</h2>

    <form method="post" enctype="multipart/form-data" id="uploadForm">
        <h2>Select file to Upload (subdomains.txt):</h2>
        <input type="file" name="fileToUpload" id="fileToUpload" accept=".txt">
        <br><br>
        <input type="submit" value="Upload File" name="submit">
    </form>
</center>

<?php
function get_http_status_and_time($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
    curl_close($ch);

    return array($httpCode, $totalTime);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
        $fileName = $_FILES['fileToUpload']['name'];
        $fileSize = $_FILES['fileToUpload']['size'];
        $fileType = $_FILES['fileToUpload']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if ($fileExtension === 'txt') {
            $urls = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if ($urls !== false) {
                $statusCodeGroups = array();
                $output = "";
                echo "<br><center><h1>Result:</center></h1><br>";
                echo "<center><table>";
                echo "<tr><th>URL</th><th>Status Code</th></tr>";

                foreach ($urls as $url) {
                    list($httpCode) = get_http_status_and_time($url);
                    if (!isset($statusCodeGroups[$httpCode])) {
                        $statusCodeGroups[$httpCode] = array();
                    }
                    $statusCodeGroups[$httpCode][] = array('url' => $url);

                    echo "<tr><td>{$url}</td><td>{$httpCode}</td></tr>";
                    $output .= "URL: $url Status Code: $httpCode\n";
                }

                echo "</table>";
                echo '<form method="post" action="download.php">';
                echo '<input type="hidden" name="output" value="' . htmlspecialchars($output) . '">';
                echo '<br>';
                echo '<input type="submit" value="Download Results" name="download">';
                echo '</form></center>';
                echo '<br>';
                echo '<center><h2>GTSivam</h2></center>';
                echo '<center><p>This Script Was made by GTSivam! For More Script or any Doubt :</p></center>';
                echo '<center><p>Insta: gt_sivam</p></center>';
                echo '<center><p>Google: gtsivam</p></center>';
                echo '<center><p>GitHub: gtsivam</p></center>';
            } else {
                echo "<center>Error reading file.</center>";
            }
        } else {
            echo "<center>Please upload a .txt file.</center>";
        }
    } else {
        echo "<center>File upload failed.</center>";
    }
}
?>

</body>
</html>
