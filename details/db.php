<?php
// Database connection for login credentials
$credentials_host = "localhost"; // Change if necessary
$credentials_user = "root"; // Your database username
$credentials_password = ""; // Your database password
$credentials_database = "user_credentials"; // Your credentials database name

$credentials_conn = new mysqli($credentials_host, $credentials_user, $credentials_password, $credentials_database);

// Check connection for credentials
if ($credentials_conn->connect_error) {
    die("Connection failed: " . $credentials_conn->connect_error);
}

// Database connection for user records
$records_database = "user_records"; // Your records database name

$records_conn = new mysqli($credentials_host, $credentials_user, $credentials_password, $records_database);

// Check connection for records
if ($records_conn->connect_error) {
    die("Connection failed: " . $records_conn->connect_error);
}
?>
