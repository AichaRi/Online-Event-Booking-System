<?php
// deleteEvent.php
session_start();
require_once 'config.php';

// التحقق من أن الـ event_id موجود
if (!isset($_GET['event_id'])) {
    header("Location: manageEvents.php");
    exit();
}

$event_id = intval($_GET['event_id']);

// تحقق إذا فيه حجوزات مرتبطة بالحدث
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = :event_id");
    $stmt->execute(['event_id' => $event_id]);
    $bookingCount = $stmt->fetchColumn();

    if ($bookingCount > 0) {
        // اذا فيه حجوزات مرتبطة
        echo "<p style='color:red; font-size:20px; text-align:center; margin-top:50px;'>
                You cannot delete this event because it has existing bookings.
              </p>";
        echo "<div style='text-align:center; margin-top:20px;'>
                <a href='manageEvents.php' style='text-decoration:none; color:white; background-color:#007BFF; padding:10px 20px; border-radius:5px;'>Back to Manage Events</a>
              </div>";
        exit();
    }

    // إذا ما فيه حجوزات، احذف الحدث
    $deleteStmt = $pdo->prepare("DELETE FROM events WHERE event_id = :event_id");
    $deleteStmt->execute(['event_id' => $event_id]);

    header("Location: manageEvents.php");
    exit();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
