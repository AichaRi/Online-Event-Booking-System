<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// Dummy event data (replace with database later)
$eventId = isset($_GET['id']) ? $_GET['id'] : null;
$eventName = "Sample Event"; // Replace with database fetch based on $eventId
$eventDate = "2025-05-01";   // Replace with database fetch
$eventLocation = "Sample Venue"; // Replace with database fetch
$eventDescription = "Sample event description."; // Replace with database fetch

// Dummy check for bookings (replace with actual check in DB)
$hasBookings = false; // Replace with database check if there are bookings for this event

// Handle event deletion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check for bookings before deleting
    if ($hasBookings) {
        // Add an error message if bookings are linked
        $errorMessage = "This event cannot be deleted because it has bookings.";
    } else {
        // Normally you would delete the event from the database here
        // Example: DELETE FROM events WHERE event_id = $eventId;

        // Redirect to manageEvents.php after deleting
        header("Location: manageEvents.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <link rel="stylesheet" href="css/style.css">
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
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <h3>Are you sure you want to delete this event?</h3>
    <p><strong>Event Name:</strong> <?php echo $eventName; ?></p>
    <p><strong>Date:</strong> <?php echo $eventDate; ?></p>
    <p><strong>Location:</strong> <?php echo $eventLocation; ?></p>
    <p><strong>Description:</strong> <?php echo $eventDescription; ?></p>

    <form method="POST" action="">
        <button type="submit">Yes, Delete this Event</button>
        <a href="manageEvents.php">Cancel</a>
    </form>
 <section>

</main>

</body>
</html>
