<?php
session_start();
require 'config.php';  // provides $pdo

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $p1      = $_POST['password'];
    $p2      = $_POST['confirm_password'];

    // 1) Check passwords match
    if ($p1 !== $p2) {
        $err = 'Passwords do not match.';
    } else {
        // 2) Check email isn’t already registered
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $err = 'That email is already in use.';
        } else {
            // 3) Insert new user
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            $ins  = $pdo->prepare("
                INSERT INTO users (name, email, password)
                VALUES (:name, :email, :pw)
            ");
            $ins->execute([
                ':name'  => $name,
                ':email' => $email,
                ':pw'    => $hash
            ]);

            // 4) Redirect to login
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Register</h2>
    <?php if ($err): ?>
      <p style="color:red;"><?php echo htmlspecialchars($err); ?></p>
    <?php endif; ?>
    <form id="regForm" method="post" action="">
      <input name="name" placeholder="Name" required><br><br>
      <input name="email" type="email" placeholder="Email" required><br><br>
      <input id="password" name="password" type="password" placeholder="Password" required><br><br>
      <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" required><br><br>
      <p id="passError" style="color:red;"></p>
      <button type="submit">Register</button>
    </form>
  </div>

  <script>
  document.getElementById('regForm')
    .addEventListener('submit', function(e) {
      var p1 = document.getElementById('password').value;
      var p2 = document.getElementById('confirm_password').value;
      if (p1 !== p2) {
        e.preventDefault();
        document.getElementById('passError').textContent = 'Passwords do not match';
      }
    });
  </script>
</body>
</html>
