<?php
session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}

require 'config.php';  // provides $pdo

// Validate and fetch event ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manageEvents.php');
    exit;
}
$eventId = (int)$_GET['id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :id");
    $stmt->execute([':id' => $eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        throw new Exception('Event not found.');
    }
} catch (Exception $e) {
    die('Error: ' . htmlspecialchars($e->getMessage()));
}

// Handle form submission
$errorMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect & trim inputs
    $eventName     = trim($_POST['eventName']);
    $eventDate     = trim($_POST['eventDate']);      // datetime-local
    $eventLocation = trim($_POST['eventLocation']);
    $ticketPrice   = trim($_POST['ticketPrice']);
    $maxTickets    = intval($_POST['maxTickets']);
    $description   = trim($_POST['description']);

    // Image: if a new file uploaded, handle upload; otherwise keep old
    $imagePath = $event['image'];
    if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] === UPLOAD_ERR_OK) {
        $imageDir = 'uploads/';
        $newPath = $imageDir . basename($_FILES['eventImage']['name']);
        if (move_uploaded_file($_FILES['eventImage']['tmp_name'], $newPath)) {
            $imagePath = $newPath;
        } else {
            $errorMessage = "Error uploading event image!";
        }
    }

    // Validation
    if (empty($eventName) || empty($eventDate) || empty($eventLocation) || empty($ticketPrice) || empty($maxTickets) || empty($description)) {
        $errorMessage = "All fields are required!";
    }

    // If no errors, update in database
    if (empty($errorMessage)) {
        $sql = "UPDATE events SET
            event_name     = :name,
            event_datetime = :dt,
            location       = :loc,
            ticket_price   = :price,
            image          = :img,
            max_tickets    = :max,
            description    = :desc
          WHERE event_id = :id";
        $upd = $pdo->prepare($sql);
        $upd->execute([
            ':name'  => $eventName,
            ':dt'    => $eventDate,
            ':loc'   => $eventLocation,
            ':price' => $ticketPrice,
            ':img'   => $imagePath,
            ':max'   => $maxTickets,
            ':desc'  => $description,
            ':id'    => $eventId,
        ]);
        header('Location: manageEvents.php');
        exit;
    }
}

// Prepare datetime-local value
$dtValue = date('Y-m-d\TH:i', strtotime($event['event_datetime']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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
        <h1>Edit Event</h1>

        <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label>Event Name:</label><br>
            <input type="text" name="eventName" value="<?php echo htmlspecialchars($event['event_name']); ?>" required><br><br>

            <label>Date &amp; Time:</label><br>
            <input type="datetime-local" name="eventDate" value="<?php echo $dtValue; ?>" required><br><br>

            <label>Location:</label><br>
            <input type="text" name="eventLocation" value="<?php echo htmlspecialchars($event['location']); ?>" required><br><br>

            <label>Ticket Price ($):</label><br>
            <input type="number" name="ticketPrice" step="0.01" value="<?php echo htmlspecialchars($event['ticket_price']); ?>" required><br><br>

            <label>Maximum Tickets:</label><br>
            <input type="number" name="maxTickets" value="<?php echo htmlspecialchars($event['max_tickets']); ?>" required><br><br>

            <label>Event Image:</label><br>
            <input type="file" name="eventImage" accept="image/*"><br>
            <p>Current image: <?php echo !empty($event['image']) ? basename($event['image']) : 'None'; ?></p>
            <br>

            <label>Description:</label><br>
            <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="manageEvents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </section>
</main>

</body>
</html>

