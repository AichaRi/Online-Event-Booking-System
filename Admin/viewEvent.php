<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}
require 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manageEvents.php');
    exit;
}
$eventId = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :id");
$stmt->execute([':id' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$event) {
    die('Event not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Event</title>
  <link rel="stylesheet" href="../style.css">
  </head>
<body>
  <aside>
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="manageEvents.php">Manage Events</a></li>
      <li><a href="addEvent.php">Add Event</a></li>
      <li><a href="viewBookings.php">View Bookings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>
  <main>
    <section>
      <h1>Event Details</h1>
      <?php if ($event['image']): ?>
        <img src="<?= htmlspecialchars($event['image']) ?>" alt="" class="event-image">
      <?php endif; ?>
      <h2><?= htmlspecialchars($event['event_name']) ?></h2>
      <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_datetime'])) ?></p>
      <p><strong>Time:</strong> <?= date('g:i A', strtotime($event['event_datetime'])) ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
      <p><strong>Price:</strong> $<?= number_format($event['ticket_price'], 2) ?></p>
      <p><strong>Available Tickets:</strong> <?= htmlspecialchars($event['max_tickets']) ?></p>
      <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($event['description'])) ?></p>
      <p><a href="manageEvents.php">&larr; Back to Events</a></p>
    </section>
  </main>
</body>
</html>
