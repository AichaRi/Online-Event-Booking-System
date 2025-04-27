<?php
session_start();
require 'config.php';   // gives you $pdo

// Determine display name
$username = isset($_SESSION['user_name'])
    ? $_SESSION['user_name']
    : 'Guest';

// Fetch all upcoming events
try {
    $stmt = $pdo->query("
        SELECT
            event_id,
            event_name,
            event_datetime,
            image,
            location,
            ticket_price,
            description
        FROM events
        ORDER BY event_datetime ASC
    ");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error loading events: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking System</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <div style="display: flex; align-items: center;">
        <img src="logo.jpg" alt="Event Booking Logo" style="height:60px;">
        <h1 style="margin-left: 10px;">Event Booking System</h1>
    </div>
    <div>
        <p>Welcome, <?php echo htmlspecialchars($username); ?>.</p>
    </div>
    <div class="header-buttons">
        <button onclick="location.href='cart.php'">Cart</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</header>

<main>
    <?php if (empty($events)): ?>
        <p style="text-align:center; padding: 50px;">No events available at the moment. Please check back later.</p>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $e): ?>
                <?php
                    // Format date nicely
                    $date = date('F j, Y', strtotime($e['event_datetime']));
                ?>
                <div class="event-card">
                    <?php if ($e['image'] && file_exists($e['image'])): ?>
                        <img
                          src="<?php echo htmlspecialchars($e['image']); ?>"
                          alt="<?php echo htmlspecialchars($e['event_name']); ?>"
                        >
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($e['event_name']); ?></h3>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($e['location']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($e['description'])); ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($e['ticket_price'],2); ?></p>
                    <button
                      class="book-now"
                      onclick="location.href='event.php?id=<?php echo $e['event_id']; ?>'"
                    >Book Now</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <p style="text-align:center; padding: 20px;">
        &copy; <?php echo date("Y"); ?> Event Booking System. All rights reserved.
    </p>
</footer>

</body>
</html>
