<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

require 'config.php';  //give $pdo

// Initialize error message
$errorMessage = "";

// Form handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['datetime'];
    $eventLocation = $_POST['eventLocation'];
    $ticketPrice = $_POST['ticketPrice'];
    $eventImage = $_FILES['eventImage'];
    $maxTickets = $_POST['maxTickets'];
    $description = $_POST['description'];



    // Validate required fields
    if (empty($eventName) || empty($eventDate) || empty($eventLocation) || empty($ticketPrice) || empty($eventImage) || empty($maxTickets) || empty($description)) {
        $errorMessage = "All fields are required!";
    } else {
        // Handle file upload (event image)
        $imageDir = 'uploads/';
        $imagePath = $imageDir . basename($eventImage['name']);
        
        if (move_uploaded_file($eventImage['tmp_name'], $imagePath)) {
            // Now, save event data to the database 
$eventName     = trim($_POST['eventName']);
    $eventDatetime = trim($_POST['datetime']);         // datetime-local
    $eventLocation = trim($_POST['eventLocation']);
    $ticketPrice   = trim($_POST['ticketPrice']);
    $maxTickets    = intval($_POST['maxTickets']);
    // $imagePath already holds 'uploads/filename.ext'

   $sql = "INSERT INTO events
    (event_name, event_datetime, location, ticket_price, image, max_tickets, description)
  VALUES
    (:name, :dt, :loc, :price, :img, :max, :desc)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name'  => $eventName,
    ':dt'    => $eventDatetime,
    ':loc'   => $eventLocation,
    ':price' => $ticketPrice,
    ':img'   => $imagePath,
    ':max'   => $maxTickets,
    ':desc'  => $description
]);

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
         <input name="datetime" type="datetime-local" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="eventLocation" required><br><br>

        <label>Ticket Price:</label><br>
        <input type="number" name="ticketPrice" step="0.01" required><br><br>

        <label>Maximum Tickets:</label><br>
        <input type="number" name="maxTickets" required><br><br>

        <label>Event Image:</label><br>
        <input type="file" name="eventImage" accept="image/*" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <button type="submit" " class="btn-small">Add Event</button>

    </form>
 <section>

</main>

</body>
</html>

