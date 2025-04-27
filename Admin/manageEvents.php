<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="../style.css">
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
        <h1>Manage Events</h1>

        <?php if (empty($events)): ?>
            <p>No events found. <a href="addEvent.php">Add a new event</a>.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                            <td><?php echo date('F d, Y', strtotime($event['event_datetime'])); ?></td>
                            <td>
                                <a href="viewEvent.php?id=<?php echo $event['event_id']; ?>">View</a> |
                                <a href="editEvent.php?id=<?php echo $event['event_id']; ?>">Edit</a> |
                                <a href="deleteEvent.php?id=<?php echo $event['event_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </section>
</main>

</body>
</html>


