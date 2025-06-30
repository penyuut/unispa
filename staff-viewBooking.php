<?php
session_start();

// OPTIONAL: Check if staff is logged in
// if (!isset($_SESSION['staff'])) {
//     header("Location: staff-login.php");
//     exit();
// }

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$events = [];
$result = $conn->query("SELECT * FROM booking");

while ($row = $result->fetch_assoc()) {
    $start = $row['bookingDate'] . 'T' . $row['bookingTime'];
    $durationMinutes = 45; // Default duration

    $endTime = date("H:i:s", strtotime($row['bookingTime'] . "+{$durationMinutes} minutes"));
    $end = $row['bookingDate'] . 'T' . $endTime;

    $events[] = [
        'title' => $row['customerName'] . ' - ' . $row['serviceName'],
        'start' => $start,
        'end' => $end,
        'color' => '#4B0082'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Booking Calendar</title>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      margin: 0;
      padding: 30px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }
    
    .fc .fc-daygrid-event {
  font-size: 12px;   /* You can adjust to 10px or smaller if needed */
  white-space: normal;  /* Allow text to wrap */
  line-height: 1.2;
  padding: 2px 4px;
}

.fc .fc-daygrid-event-title {
  overflow-wrap: break-word;
  word-wrap: break-word;
}

.fc .fc-daygrid-event {
  font-size: 12px;
  white-space: normal;
  padding: 2px 4px;
  line-height: 1.3;
}



    #calendar {
      max-width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-calendar-alt"></i> Booking Calendar Overview</h1>
    <div id="calendar"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 'auto',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: false,
        omitZeroMinute: true
      },
      displayEventTime: false,
      events: <?= json_encode($events) ?>, // ← This is the fix
      dateClick: function(info) {
        const selectedDate = info.dateStr;
        window.location.href = 'view-booking-date.php?date=' + selectedDate;
      }
    });

    calendar.render();
  });
</script>

<div class="signup-link">
      <a href="staff-dashboard.php">&larr; Back to Dashboard</a>
    </div>


</body>
</html>
