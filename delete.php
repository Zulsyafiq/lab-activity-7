<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "Lab_7");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$matric = mysqli_real_escape_string($conn, $_GET['matric']);

// Verifikasi user yang login bukan menghapus diri sendiri
if ($matric === $_SESSION['matric']) {
    header("Location: display.php?error=cannot_delete_yourself");
    exit;
}

$sql = "DELETE FROM users WHERE matric='$matric'";

if (mysqli_query($conn, $sql)) {
    header("Location: display.php?deleted=1");
} else {
    header("Location: display.php?error=delete_failed");
}

mysqli_close($conn);
?>