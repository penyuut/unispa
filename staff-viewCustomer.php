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

$customer = null;
if (isset($_POST['search'])) {
    $custID = $_POST['custID'];
    $stmt = $conn->prepare("SELECT * FROM customers WHERE custID = ?");
    $stmt->bind_param("s", $custID);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $stmt->close();
}

$allCustomers = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Staff View Customer</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      margin: 0;
      padding: 30px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h1, h2 {
      text-align: center;
      color: #4B0082;
    }

    .form-group {
      margin: 20px 0;
    }

    label {
      font-weight: bold;
      color: #333;
    }

    input, button {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-top: 10px;
      font-size: 1em;
    }

    button {
      background-color: #4B0082;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #6a0dad;
    }

    .profile {
      margin-top: 30px;
      padding: 20px;
      background-color: #f9f9ff;
      border-radius: 10px;
    }

    .profile h2 {
      color: #4B0082;
      margin-bottom: 10px;
    }

    .profile p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 40px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #eee;
    }

    .no-data {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-user-circle"></i> View Customer Profile</h1>

    <form method="post">
      <div class="form-group">
        <label for="custID">Enter Customer ID:</label>
        <input type="text" name="custID" id="custID" placeholder="e.g. C001" required />
      </div>
      <button type="submit" name="search">Search</button>
    </form>

    <?php if ($customer): ?>
      <div class="profile">
        <h2><?= htmlspecialchars($customer['name']) ?></h2>
        <p><strong>ID:</strong> <?= htmlspecialchars($customer['custID']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($customer['custType']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($customer['contact_number']) ?></p>
        <p><strong>DOB:</strong> <?= htmlspecialchars($customer['dob']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($customer['gender']) ?></p>
        <p><strong>Password:</strong> <?= htmlspecialchars($customer['password']) ?></p>
      </div>
    <?php elseif (isset($_POST['search'])): ?>
      <p class="no-data">Customer not found.</p>
    <?php endif; ?>

    <h2>All Registered Customers</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>DOB</th>
          <th>Gender</th>
          <th>Password</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($allCustomers->num_rows > 0): ?>
          <?php while ($row = $allCustomers->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['custID']) ?></td>
              <td><?= htmlspecialchars($row['custType']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['contact_number']) ?></td>
              <td><?= htmlspecialchars($row['dob']) ?></td>
              <td><?= htmlspecialchars($row['gender']) ?></td>
              <td><?= htmlspecialchars($row['password']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="no-data">No customers found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<div class="signup-link">
      <a href="staff-dashboard.php">&larr; Back to Dashboard</a>
    </div>
</body>
</html>
