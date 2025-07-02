<?php
session_start();

// Redirect ke login jika belum login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Sambung ke database
$conn = mysqli_connect("localhost", "root", "", "Lab_7");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Dapatkan data users
$sql = "SELECT matric, name, role FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-links a { margin-right: 10px; }
        .delete { color: #cc0000; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['matric']; ?> (<?php echo $_SESSION['role']; ?>)</h2>
    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>".$row["matric"]."</td>
                        <td>".$row["name"]."</td>
                        <td>".$row["role"]."</td>
                        <td class='action-links'>
                            <a href='edit.php?matric=".$row["matric"]."'>Update</a>
                            <a href='delete.php?matric=".$row["matric"]."' class='delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found</td></tr>";
        }
        ?>
    </table>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

<?php
mysqli_close($conn);
?>