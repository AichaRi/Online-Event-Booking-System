<?php
session_start();
require 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$cart    = $_SESSION['cart'] ?? [];
$success = '';
$error   = '';
$total   = 0;

// Handle booking confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['confirm_booking']) 
    && !empty($cart)
) {
    $user_id = $_SESSION['user_id'];
    $now     = date('Y-m-d H:i:s');
    $pdo->beginTransaction();
    try {
        foreach ($cart as $item) {
            // Re-check availability
            $stmtCheck = $pdo->prepare("
                SELECT e.max_tickets 
                       - COALESCE(SUM(b.num_tickets),0) AS available
                FROM events e
                LEFT JOIN bookings b 
                     ON e.event_id = b.event_id
                WHERE e.event_id = ?
            ");
            $stmtCheck->execute([$item['event_id']]);
            $avail = $stmtCheck->fetchColumn();
            if ($item['quantity'] > $avail) {
                throw new Exception("Not enough tickets for {$item['name']}.");
            }
            // Insert booking
            $subtotal = $item['price'] * $item['quantity'];
            $stmt = $pdo->prepare("
                INSERT INTO bookings
                  (user_id, event_id, booking_date, num_tickets, total_price)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $item['event_id'],
                $now,
                $item['quantity'],
                $subtotal
            ]);
        }
        $pdo->commit();
        unset($_SESSION['cart']);
        $success = "Booking confirmed!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Booking failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="site-header">
    <div class="logo">Event Booking System</div>
    <nav class="user-controls">
      <a href="cart.php">Cart</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main class="cart">
    <div class="cart__header">
      <h1>Your Cart</h1>
    </div>

    <?php if ($error): ?>
      <div class="cart__messages">
        <p class="message message--error">
          <?= htmlspecialchars($error) ?>
        </p>
      </div>
    <?php elseif ($success): ?>
      <div class="cart__messages">
        <p class="message message--success">
          <?= htmlspecialchars($success) ?>
        </p>
      </div>
    <?php endif; ?>

    <?php if (!empty($cart)): ?>
      <section class="cart__items">
        <table class="cart__table">
          <thead>
            <tr>
              <th>Event</th>
              <th>Date &amp; Time</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $item): 
              $subtotal = $item['price'] * $item['quantity'];
              $total   += $subtotal;
            ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= htmlspecialchars($item['date_time']) ?></td>
              <td><?= $item['quantity'] ?></td>
              <td>$<?= number_format($item['price'], 2) ?></td>
              <td>$<?= number_format($subtotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>

      <div class="cart__summary">
        <p class="cart__total">
          Total: $<?= number_format($total, 2) ?>
        </p>
      </div>

      <div class="cart__meta">
        <p>
          Current Date &amp; Time: <?= date('Y-m-d H:i:s') ?>
        </p>
      </div>

      <div class="cart__actions">
        <form method="post">
          <button 
            type="submit" 
            name="confirm_booking" 
            class="cart__button"
          >Reserve Tickets</button>
        </form>
      </div>

    <?php else: ?>
      <section class="cart__items">
        <p>Your cart is empty.</p>
      </section>
    <?php endif; ?>

  </main>

  <footer class="site-footer">
    &copy; <?= date('Y') ?> Event Booking System
  </footer>

</body>
</html>
