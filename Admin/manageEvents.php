<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="style.css">
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
        <h2>Manage Events</h2>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sample Event 1</td>
                    <td>2025-05-01</td>
                    <td class="actions">
                        <a href="viewEvent.php?id=1">View</a> 
                        <a href="editEvent.php?id=1">Edit</a> 
                        <a href="deleteEvent.php?id=1">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Sample Event 2</td>
                    <td>2025-06-15</td>
                    <td class="actions">
                        <a href="viewEvent.php?id=2">View</a> 
                        <a href="editEvent.php?id=2">Edit</a> 
                        <a href="deleteEvent.php?id=2">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
