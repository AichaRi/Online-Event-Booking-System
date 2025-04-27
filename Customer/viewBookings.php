<?php
// viewBookings.php
session_start();


require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<aside >
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
        <h1>All Bookings</h1>

        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Booking Date</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Tickets Booked</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("
                        SELECT u.name, u.email, b.booking_date, e.event_name, e.event_datetime, b.num_tickets, b.total_price
                        FROM bookings b
                        INNER JOIN users u ON b.user_id = u.user_id
                        INNER JOIN events e ON b.event_id = e.event_id
                        ORDER BY b.booking_date DESC
                    ");

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['booking_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['event_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['event_datetime']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['num_tickets']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['total_price']) . '</td>';
                        echo '</tr>';
                    }
                } catch (PDOException $e) {
                    echo '<tr><td colspan="7">Error fetching bookings: ' . $e->getMessage() . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

<script src="adminBookings.js"></script> <!-- استدعاء ملف جافاسكريبت -->
</body>
</html>
