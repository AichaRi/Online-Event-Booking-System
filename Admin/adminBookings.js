// adminBookings.js

// لما يتم تحميل الصفحة بالكامل
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.bookings-table tbody tr');

    // لما تمرر الماوس على الصف، يغير لونه
    rows.forEach(row => {
        row.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f2f2f2';
        });

        row.addEventListener('mouseout', function() {
            this.style.backgroundColor = 'white';
        });
    });
});
