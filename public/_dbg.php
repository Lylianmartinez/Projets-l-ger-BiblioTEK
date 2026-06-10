<?php header("Content-Type: text/plain");
foreach(["REQUEST_URI","SCRIPT_NAME","PHP_SELF","REQUEST_METHOD","PATH_INFO"] as $k) {
    echo "$k=" . ($_SERVER[$k] ?? "N/A") . "\n";
}
