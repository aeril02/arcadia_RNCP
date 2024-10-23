<?php
session_start();
$host = 'localhost'; 
$db = 'arcadia'; 
$user = 'root'; 
$pass = ''; 


$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // code de verif
    $stmt = $conn->prepare("SELECT username, password, role_id FROM utilisateur WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // verification_user-table
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role_id = $row['role_id'];

        // Redirect_par role
        switch ($role_id) {
            case 1:
                header("Location: admin.php");
                exit();
            case 2:
                header("Location: veterinaire.php");
                exit();
            case 3:
                header("Location: employe.php");
                exit();
            default:
                $error_message = "Role not recognized.";
                break;
        }
    } else {
        // message d'erreur-false uti
        $error_message = "Invalid username or password.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Login Page</title>
    <style>
    </style>
</head>
<body>
<header>
     <!--header_same for all -->
        <nav>
            <ul>
                <li><img src="photo/logo_arcadia.jpg" alt="logo arcadia"></li>
                <li><a href="index.php">acceuil</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="habitats.php">Habitats</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        
        <?php
        if (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
    </div>
</body>
</html>