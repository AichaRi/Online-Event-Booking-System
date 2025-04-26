<?php
session_start();
require 'config.php';
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
// Validate event ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "Invalid event.";
    exit;
}
$event_id = (int) $_GET['id'];

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();
if (!$event) {
    echo "Event not found.";
    exit;
}

// Calculate tickets sold
$stmtSold = $pdo->prepare("SELECT COALESCE(SUM(num_tickets), 0) AS tickets_sold FROM bookings WHERE event_id = ?");
$stmtSold->execute([$event_id]);
$row = $stmtSold->fetch();
$tickets_sold = $row['tickets_sold'];

// Calculate available tickets
$available = $event['max_tickets'] - $tickets_sold;

$error = '';
// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int)$_POST['quantity'];
    if ($quantity < 1 || $quantity > $available) {
        $error = "Please select a valid number of tickets.";
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = [
            'event_id'   => $event['event_id'],
            'name'       => $event['event_name'],
            'date_time'  => $event['event_datetime'],
            'price'      => $event['ticket_price'],
            'quantity'   => $quantity
        ];
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Book Tickets</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
    <p>Date & Time: <?php echo htmlspecialchars($event['event_datetime']); ?></p>
    <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
    <p>Price per Ticket: $<?php echo number_format($event['ticket_price'], 2); ?></p>
    <p>Tickets Available: <?php echo $available; ?></p>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="quantity">Quantity:</label>
        <select name="quantity" id="quantity">
            <?php for ($i = 1; $i <= $available; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
</body>
</html>
