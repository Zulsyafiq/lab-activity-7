<?php
session_start();

// Jika sudah login, redirect ke display
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: display.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "Lab_7");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = mysqli_real_escape_string($conn, $_POST['matric']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE matric='$matric'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $user['matric'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: display.php");
            exit;
        } else {
            $error = "Invalid matric or password";
        }
    } else {
        $error = "Invalid matric or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php 
        if(isset($error)) echo "<p class='error'>$error</p>"; 
        if(isset($_GET['registered'])) echo "<p class='success'>Registration successful! Please login.</p>";
        ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Matric:</label>
                <input type="text" name="matric" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href