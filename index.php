<?php
session_start();
require 'config.php';  // provides $pdo

// If already logged in, go straight to home
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT user_id, name, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password & set session
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: home.php');
        exit;
    } else {
        $err = 'Login failed. Please check your email and password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <?php if ($err): ?>
      <p style="color:red;"><?php echo htmlspecialchars($err); ?></p>
    <?php endif; ?>
    <form method="post" action="">
      <input name="email" type="email" placeholder="Email" required><br><br>
      <input name="password" type="password" placeholder="Password" required><br><br>
      <button type="submit">Login</button>
    </form>
    <p>Not a member yet? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>

