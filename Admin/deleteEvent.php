<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

require 'config.php';  // provides $pdo

// Validate event ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manageEvents.php');
    exit;
}
$eventId = (int)$_GET['id'];

// Fetch event details
try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :id");
    $stmt->execute([':id' => $eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        throw new Exception('Event not found.');
    }

    // Count how many bookings are linked to this event
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = :id");
    $countStmt->execute([':id' => $eventId]);
    $bookingCount = (int)$countStmt->fetchColumn();
} catch (Exception $e) {
    die('Error: ' . htmlspecialchars($e->getMessage()));
}

// Handle event deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && $bookingCount === 0) {
    try {
        $delStmt = $pdo->prepare("DELETE FROM events WHERE event_id = :id");
        $delStmt->execute([':id' => $eventId]);
        header('Location: manageEvents.php');
        exit;
    } catch (PDOException $e) {
        $errorMessage = "Error deleting event: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
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
        <h1>Delete Event</h1>

        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete the following event?</p>

        <p><strong>Event Name:</strong> <?php echo htmlspecialchars($event['event_name']); ?></p>
        <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_datetime'])); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <p><strong>Bookings:</strong> <?php echo $bookingCount; ?></p>

        <form method="POST" action="">
            <button type="submit" class="btn btn-danger" <?php echo $bookingCount > 0 ? 'disabled' : ''; ?>>Yes, Delete this Event</button>
            <a href="manageEvents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </section>
</main>

</body>
</html>
