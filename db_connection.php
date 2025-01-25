<?php
$servername = "localhost";
$username = "root"; // আপনার ডাটাবেস ব্যবহারকারীর নাম
$password = ""; // আপনার ডাটাবেস পাসওয়ার্ড
$dbname = "library"; // ডাটাবেসের নাম

// ডাটাবেস কানেকশন
$conn = new mysqli($servername, $username, $password, $dbname);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connect to `library_system` database
$conn_library_system = new mysqli($servername, $username, $password, "library_system");

// Check connection for `library_system`
if ($conn_library_system->connect_error) {
    die("Connection to library_system failed: " . $conn_library_system->connect_error);
}

?>
