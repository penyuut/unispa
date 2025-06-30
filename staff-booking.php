<?php
session_start();

// Check staff login
if (!isset($_SESSION['staff'])) {
    header("Location: staff-login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE booking
if (isset($_GET['delete'])) {
    $bookID = intval($_GET['delete']);
    $conn->query("DELETE FROM booking WHERE bookID = $bookID");
    header("Location: staff-booking.php");
    exit();
}

// UPDATE booking (with file upload)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateBooking'])) {
    $bookID = intval($_POST['bookID']);
    $paymentStatus = $_POST['paymentStatus'];
    $extraRequest = $_POST['extraRequest'];
    $paymentProof = $_POST['existingProof'];

    // Check if a new file is uploaded
    if (isset($_FILES['paymentProof']) && $_FILES['paymentProof']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['paymentProof']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['paymentProof']['tmp_name'], $targetFile)) {
            $paymentProof = $filename;
        }
    }

    $stmt = $conn->prepare("UPDATE booking SET paymentStatus=?, paymentProof=?, extraRequest=? WHERE bookID=?");
    $stmt->bind_param("sssi", $paymentStatus, $paymentProof, $extraRequest, $bookID);
    $stmt->execute();
    $stmt->close();

    header("Location: staff-booking.php");
    exit();
}

// Fetch all bookings
$bookings = $conn->query("SELECT * FROM booking");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Bookings | UniSpa Staff</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f2e6ff, #e5d5ff);
      padding: 40px;
      margin: 0;
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 14px 12px;
      text-align: center;
      border-bottom: 1px solid #e0d7f3;
    }

    th {
      background: #d8c7f0;
      color: #4B0082;
      font-weight: bold;
    }

    tr:hover {
      background: #f3e9ff;
    }

    img {
      width: 100px;
      border-radius: 6px;
      border: 2px solid #ccc;
    }

    .btn, button.btn {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
    }

    .btn {
      background-color: #4B0082;
      color: white;
    }

    .btn:hover {
      background-color: #6a0dad;
    }

    .delete {
      background-color: #a10000;
    }

    .delete:hover {
      background-color: #cc0000;
    }

    form {
      background: #fff;
      max-width: 600px;
      margin: 40px auto;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    form h2 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #333;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border-radius: 8px;
      border: 1px solid #bbb;
      font-size: 1em;
    }

    textarea {
      resize: vertical;
    }

    .btn-submit {
      margin-top: 20px;
      background-color: #4B0082;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 10px;
      font-size: 1em;
      cursor: pointer;
    }

    .btn-submit:hover {
      background-color: #6a0dad;
    }

    .current-proof {
      text-align: center;
      margin-top: 15px;
    }

    .current-proof img {
      width: 120px;
      margin-top: 5px;
      border: 2px solid #888;
      border-radius: 10px;
    }

    @media (max-width: 768px) {
      table, form {
        font-size: 0.9em;
      }
    }
  </style>
</head>
<body>

<h1>Manage Bookings</h1>

<table>
  <thead>
    <tr>
      <th>Book ID</th>
      <th>Customer</th>
      <th>Service</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
      <th>Payment Proof</th>
      <th>Extra Request</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $bookings->fetch_assoc()): ?>
    <tr>
      <td><?= $row['bookID'] ?></td>
      <td><?= $row['customerName'] ?></td>
      <td><?= $row['serviceName'] ?></td>
      <td><?= $row['bookingDate'] ?></td>
      <td><?= $row['bookingTime'] ?></td>
      <td><?= $row['paymentStatus'] ?></td>
      <td>
        <?php if (!empty($row['paymentProof'])): ?>
          <img src="uploads/<?= htmlspecialchars($row['paymentProof']) ?>" alt="Proof">
        <?php else: ?>
          No image
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($row['extraRequest']) ?></td>
      <td>
        <a href="?edit=<?= $row['bookID'] ?>" class="btn">Edit</a>
        <a href="?delete=<?= $row['bookID'] ?>" class="btn delete" onclick="return confirm('Delete this booking?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php if (isset($_GET['edit'])):
  $editID = intval($_GET['edit']);
  $editData = $conn->query("SELECT * FROM booking WHERE bookID = $editID")->fetch_assoc();
?>
<form method="POST" enctype="multipart/form-data">
  <h2>Edit Booking #<?= $editID ?></h2>
  <input type="hidden" name="bookID" value="<?= $editID ?>">
  <input type="hidden" name="existingProof" value="<?= htmlspecialchars($editData['paymentProof']) ?>">

  <label>Payment Status:</label>
  <select name="paymentStatus">
    <option value="Pending" <?= $editData['paymentStatus'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Paid" <?= $editData['paymentStatus'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
  </select>

  <label>Upload Payment Proof:</label>
  <input type="file" name="paymentProof" accept="image/*">

  <?php if (!empty($editData['paymentProof'])): ?>
    <p>Current Proof: <img src="uploads/<?= htmlspecialchars($editData['paymentProof']) ?>" width="100"></p>
  <?php endif; ?>

  <label>Extra Request:</label>
  <textarea name="extraRequest"><?= htmlspecialchars($editData['extraRequest']) ?></textarea>

  <button type="submit" name="updateBooking" class="btn">Save Changes</button>
</form>

<?php endif; ?>

 <div class="signup-link">
      <a href="staff-dashboard.php">&larr; Back to Dashboard</a>
    </div>

</body>
</html>
