<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

// Redirect to login if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer-signin.php");
    exit();
}

$customerName = $_SESSION['customer']['username']; // Or use 'name' depending on your system

// Get service ID from query string
if (!isset($_GET['serviceID'])) {
    echo "No service selected.";
    exit();
}

$serviceID = $_GET['serviceID'];
$service = $conn->query("SELECT * FROM services WHERE serviceID = '$serviceID'")->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingDate = $_POST['bookingDate'];
    $bookingTime = date("H:i:s", strtotime($_POST['bookingTime']));

// Check that date is between Monday and Friday
$dayOfWeek = date('N', strtotime($bookingDate)); // 1 = Monday, 7 = Sunday
if ($dayOfWeek > 5) {
    echo "<script>alert('Booking is only available Monday to Friday.'); window.history.back();</script>";
    exit();
}

// Check time is between 10:00 and 20:00
if ($bookingTime < '10:00:00' || $bookingTime >= '20:00:00') {
    echo "<script>alert('Booking time must be between 10:00 AM and 8:00 PM.'); window.history.back();</script>";
    exit();
}


    $extraRequest = $_POST['extraRequest'] ?? '';
    $paymentStatus = 'Pending';
    $paymentProof = null;

    // Handle image upload
    if (isset($_FILES['paymentProof']) && $_FILES['paymentProof']['error'] == 0) {
        $targetDir = "uploads/payment/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $ext = pathinfo($_FILES['paymentProof']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('pay_') . '.' . $ext;
        $targetFile = $targetDir . $filename;
        move_uploaded_file($_FILES['paymentProof']['tmp_name'], $targetFile);
        $paymentProof = $targetFile;
    }

    // Auto-generate bookID
    $last = $conn->query("SELECT bookID FROM booking ORDER BY bookID DESC LIMIT 1");
    $lastID = $last->num_rows ? $last->fetch_assoc()['bookID'] : 'B000';
    $newID = 'B' . str_pad((int)substr($lastID, 1) + 1, 3, '0', STR_PAD_LEFT);


// Check for time clash for the same service and time
// Get new booking start & end time
$newStart = date("H:i:s", strtotime($bookingTime));
$duration = (int) $service['duration'];
$newEnd = date("H:i:s", strtotime("+$duration minutes", strtotime($bookingTime)));

// Prepare conflict check
$checkClash = $conn->prepare("
    SELECT b.*, s.duration 
    FROM booking b
    JOIN services s ON b.serviceID = s.serviceID
    WHERE b.bookingDate = ? AND b.serviceID = ?
");

$checkClash->bind_param("ss", $bookingDate, $serviceID);
$checkClash->execute();
$checkResult = $checkClash->get_result();

$conflict = false;

while ($row = $checkResult->fetch_assoc()) {
    $existingStart = $row['bookingTime'];
    $existingEnd = date("H:i:s", strtotime("+{$row['duration']} minutes", strtotime($existingStart)));

    // Check for overlap
    if (!($newEnd <= $existingStart || $newStart >= $existingEnd)) {
        $conflict = true;
        break;
    }
}

if ($conflict) {
    echo "<script>alert('This booking overlaps with another session. Please choose a different time.'); window.history.back();</script>";
    exit();
}




    // Insert into booking table (using serviceName instead of serviceID)
    $stmt = $conn->prepare("INSERT INTO booking 
(bookID, customerName, serviceID, serviceName, bookingDate, bookingTime, paymentStatus, paymentProof, extraRequest)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $newID, $customerName, $serviceID, $service['service_name'], $bookingDate, $bookingTime, $paymentStatus, $paymentProof, $extraRequest);


    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Booking successful!'); window.location.href='customer-dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Confirm Booking | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0c3fc, #8ec5fc);
      padding: 40px;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #4B0082;
    }
    label {
      font-weight: bold;
      margin-top: 10px;
    }
    input, select, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      background-color: #4B0082;
      color: white;
      font-weight: bold;
      margin-top: 20px;
    }
    button:hover {
      background-color: #6a0dad;
    }
    .readonly {
      background-color: #f4f4f4;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-calendar-check"></i> Confirm Booking</h1>
    <form method="POST" enctype="multipart/form-data">
      <label>Service Name</label>
      <input type="text" class="readonly" readonly value="<?= htmlspecialchars($service['service_name']) ?>">

      <label>Price (RM)</label>
      <input type="text" class="readonly" readonly value="<?= htmlspecialchars($service['price']) ?>">

      <label>Duration (minutes)</label>
      <input type="text" class="readonly" readonly value="<?= htmlspecialchars($service['duration']) ?>">

      <label for="bookingDate">Booking Date</label>
      <input type="date" name="bookingDate" required>

      <label for="bookingTime">Booking Time</label>
      <input type="time" name="bookingTime" min="10:00" max="16:00" required>


      <label for="paymentProof">Upload Payment Proof (optional)</label>
      <input type="file" name="paymentProof" accept="image/*">

      <label for="extraRequest">Extra Request</label>
      <textarea name="extraRequest" rows="3" placeholder="e.g. Please prepare lavender oil."></textarea>

      <button type="submit">Submit Booking</button>
    </form>
  </div>
</body>
</html>
