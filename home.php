<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking System</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

<header>
    <div style="display: flex; align-items: center;">
    <img src="logo.jpg" alt="Event Booking Logo">
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
    <!-- Event cards -->
    <?php
    // Sample events array
    $events = [
        ["name" => "concert night", "image" => "concertnight.JPG", "date" => "2025-06-15"],
        ["name" => "Art Exhibition", "image" => "ArtExhibition.JPG", "date" => "2025-07-10"],
        ["name" => "Food Carnival", "image" => "FoodCarnival.JPG", "date" => "2025-08-05"],
        ["name" => "Tech Conference", "image" => "TechConference.JPG", "date" => "2025-09-12"],
    ];


    foreach ($events as $event) {
        echo '
        <div class="event-card">
            <img src="' . htmlspecialchars($event['image']) . '" alt="' . htmlspecialchars($event['name']) . '">
            <h3>' . htmlspecialchars($event['name']) . '</h3>
            <p>' . htmlspecialchars($event['date']) . '</p>
            <button class="book-now">Book Now</button>
        </div>';
    }
    ?>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Event Booking System. All rights reserved.</p>
</footer>

</body>
</html>
