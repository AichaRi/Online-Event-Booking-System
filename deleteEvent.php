<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// Dummy event data (replace with database later)
$eventId = isset($_GET['id']) ? $_GET['id'] : null;
$eventName = "Sample Event"; 
$eventDate = "2025-05-01";   
$eventLocation = "Sample Venue"; 
$eventDescription = "Sample event description."; 

// Dummy check for bookings (replace with actual check in DB)
$hasBookings = false; 

// Handle event deletion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($hasBookings) {
        $errorMessage = "This event cannot be deleted because it has bookings.";
    } else {
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
    <nav>
        <ul>
            <li><a href="manageEvents.php">Manage Events</a></li>
            <li><a href="addEvent.php">Add Event</a></li>
            <li><a href="viewBookings.php">View Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</aside>

<main>
    <section>
        <header>
            <h1>Delete Event</h1>
        </header>

        <?php if (isset($errorMessage)): ?>
            <section role="alert" class="error-message">
                <p><?php echo $errorMessage; ?></p>
            </section>
        <?php endif; ?>

        <article>
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete the following event?</p>

            <h4> <?php echo htmlspecialchars($eventName); ?></h4>
            <p><strong >Date:</strong> <?php echo htmlspecialchars($eventDate); ?></p>
            <p><strong >Location:</strong> <?php echo htmlspecialchars($eventLocation); ?></p>
            <p><strong >Bookings:</strong> <?php echo htmlspecialchars($eventDescription); ?></p>
        </article>


        <form method="POST" action="">
        <fieldset class="button-group">
        <button type="submit" class="btn-delete" onclick="window.location.href='manageEvents.php';">
           Yes, Delete this Event</button>
           <button type="button" class="btn-cancel" onclick="window.location.href='manageEvents.php';">
           Cancel</button>
         </fieldset>
        </form>
    </section>
</main>

</body>
</html>
