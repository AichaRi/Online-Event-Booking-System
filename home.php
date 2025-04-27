<?php
session_start();
require 'config.php'; // الاتصال بقاعدة البيانات

// تحديد اسم المستخدم
$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

// جلب جميع الفعاليات القادمة
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
    <link rel="stylesheet" href="home.css"> 
</head>
<body>

<header>
    <div class="logo-title">
        <img src="logo.jpg" alt="Logo">
        <h1>Event Booking System</h1>
    </div>
    <div class="header-buttons">
        <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
        <button onclick="location.href='cart.php'">Cart</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</header>

<main>
    <?php if (empty($events)): ?>
        <p class="no-events">No events available at the moment. Please check back later.</p>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $e): ?>
                <?php
                    $date = date('F j, Y', strtotime($e['event_datetime']));
                ?>
                <div class="event-card">
                    <?php if (!empty($e['image']) && file_exists($e['image'])): ?>
                        <img src="<?php echo htmlspecialchars($e['image']); ?>" alt="<?php echo htmlspecialchars($e['event_name']); ?>">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($e['event_name']); ?></h3>
                    <p><?php echo htmlspecialchars($date); ?></p>
                    <p><?php echo htmlspecialchars($e['location']); ?></p>
                    <p>$<?php echo number_format($e['ticket_price'], 2); ?></p>
                    <button class="book-now" onclick="location.href='event.php?id=<?php echo $e['event_id']; ?>'">Book Now</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Event Booking System. All rights reserved.
</footer>

</body>
</html>
