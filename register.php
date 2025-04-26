<?php
    require 'config.php';
    $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];

        if(empty($name) || empty($email) || empty($password) || empty($confirm)){
            $error = "All fields are required";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        } elseif($password !== $confirm){
            $error = "Passwords do not match";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            //check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if($stmt->fetch()){
                $error = "Email already registered";
            } else { 
                //insert new user
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                if($stmt->execute([$name, $email, $hashed])){
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Registration failed.";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <h1>Event Booking System</h1>
        </header>

        <main>
            <h2>Register</h2>
            <form method="post">
                <input type="text" name="name" placeholder="Full Name" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="passsword" placeholder="Password" required><br><br>
                <input type="password" name="password" placeholder="Confirm Password" required><br><br>
                <button type="submit">Register</button>
            </form>
            <p class="error"><?= $error ?></p>
        </main>
    </body>
</html>