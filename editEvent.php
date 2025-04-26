<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// Dummy event data (replace with database later)
$eventName = "Music Festival";
$eventDate = "2025-05-10";
$eventLocation = "Open Air Theater";
$ticketPrice = 50; 
$maximumTickets = 100; 
$eventDescription = "A summer night full of music and fun!";

// Form handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // You would validate and update the database here

    // Redirect back to manageEvents.php after "updating"
    header("Location: manageEvents.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
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
    <h1>Edit Event</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        
        <label>Event Name:</label><br>
        <input type="text" name="eventName" value="<?php echo htmlspecialchars($eventName); ?>" required><br><br>

        <label>Event Date:</label><br>
        <input type="date" name="eventDate" value="<?php echo htmlspecialchars($eventDate); ?>" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="eventLocation" value="<?php echo htmlspecialchars($eventLocation); ?>" required><br><br>

        <label>Ticket Price ($):</label><br>
        <input type="number" name="ticketPrice" value="<?php echo htmlspecialchars($ticketPrice); ?>" required><br><br>

        <label>Maximum Tickets:</label><br>
        <input type="number" name="maximumTickets" value="<?php echo htmlspecialchars($maximumTickets); ?>" required max="150"><br><br>

        <label>Event Image:</label><br>

<input type="file" name="eventImage" id="eventImageInput" accept="image/*"><br><br>

<p id="currentImageText" style="color: black;">
    <?php echo !empty($eventImage) ? "Current image: " . basename($eventImage) : "No current image"; ?>
</p>


<script>
document.getElementById('eventImageInput').addEventListener('change', function(event) {
    const fileName = event.target.files[0]?.name;
    const currentImageText = document.getElementById('currentImageText');
    
    // Update the text content of the current image label
    if (fileName) {
        currentImageText.textContent = "Current image: " + fileName;
    }
});
</script>

        <label>Description:</label><br>
        <textarea name="eventDescription" required><?php echo htmlspecialchars($eventDescription); ?></textarea><br><br>

        <button type="submit" class="btn-small">Update Event</button>
    </form>
</section>
</main>

</body>
</html>
