<?php
    session_start();
    require "config.php";
    $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $error = $_POST["email"];
        $password = $_POST["password"];

        if(empty($email) || empty($password)) {
            $error = "All fields are required";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if($user){
                if(password_verify($password, $user["password"])){
                    $_SESSION['user'] = $user;
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Incorrect password";
                }
            } else {
                $error = "User not found";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTP-8">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <h1>Event Booking System</h1>
        </header>

        <main>
            <h2>Customer Login</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
            </form>
            <p class="error"><?=$error ?></p>
            <p>Not a member yet? <a href="register.php">Register Here</a></p>
        </main>
    </body>
</html>