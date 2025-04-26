<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// Initialize error message
$errorMessage = "";

// Form handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $eventLocation = $_POST['eventLocation'];
    $ticketPrice = $_POST['ticketPrice'];
    $eventImage = $_FILES['eventImage'];
    $maxTickets = $_POST['maxTickets'];

    // Validate required fields
    if (empty($eventName) || empty($eventDate) || empty($eventTime) || empty($eventLocation) || empty($ticketPrice) || empty($eventImage) || empty($maxTickets)) {
        $errorMessage = "All fields are required!";
    } else {
        // Handle file upload (event image)
        $imageDir = 'uploads/';
        $imagePath = $imageDir . basename($eventImage['name']);
        
        if (move_uploaded_file($eventImage['tmp_name'], $imagePath)) {
            // Now, save event data to the database (placeholder logic for now)
            // Example: INSERT INTO events (event_name, event_date, event_time, location, ticket_price, image_path, max_tickets) VALUES (...);

            // Redirect after successful event creation
            header("Location: manageEvents.php");
            exit;
        } else {
            $errorMessage = "Error uploading event image!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
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

    <h1>Add New Event</h1>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Event Name:</label><br>
        <input type="text" name="eventName" required><br><br>

        <label>Date:</label><br>
        <input type="date" name="eventDate" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="eventLocation" required><br><br>

        <label>Ticket Price:</label><br>
        <input type="number" name="ticketPrice" step="0.01" required><br><br>

        <label>Maximum Tickets:</label><br>
        <input type="number" name="maxTickets" required><br><br>

        <label>Event Image:</label><br>
        <input type="file" name="eventImage" accept="image/*" required><br><br>

        <label>Description:</label><br>
        <textarea name="eventLocation" required></textarea><br><br>

        <button type="submit"  onclick="window.location.href='manageEvents.php';" class="btn-small">Add Event</button>

    </form>
 <section>

</main>

</body>
</html>
