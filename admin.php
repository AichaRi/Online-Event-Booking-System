<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    //This retrieves the username entered in the form. $_POST is used to collect form data after submission.
    $password = $_POST['password'];

    // Check login credentials
    if ($username === 'admin' && $password === '1') {
        $_SESSION['logged_in'] = true;
        //This creates a session variable (logged_in) and sets it to true. This will indicate that the user is logged in and can access protected areas of the website.
        header('Location: manageEvents.php');
        //If the login is successful, this function redirects the user to manageEvents.php. This would be the page where the admin can manage events.
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <fieldset>
        <h2>Admin Login</h2>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </fieldset>
</body>
</html>
