<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$staffList = [];
$result = $conn->query("SELECT * FROM staff");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffList[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Staff | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      padding: 40px 20px;
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 30px;
    }

    .actions {
      text-align: center;
      margin-bottom: 20px;
    }

    .actions a button {
      background-color: #4B0082;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .actions a button:hover {
      background-color: #6a0dad;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #4B0082;
      color: white;
    }

    tr:hover {
      background-color: #f2f2f2;
    }

    .action-btns a button {
      margin-right: 6px;
      padding: 6px 12px;
      border-radius: 6px;
      border: none;
      font-size: 0.9em;
      cursor: pointer;
    }

    .update-btn {
      background-color: #ffc107;
      color: black;
    }

    .delete-btn {
      background-color: #dc3545;
      color: white;
    }

    .back-link {
      text-align: center;
      margin-top: 30px;
    }

    .back-link a {
      color: #4B0082;
      text-decoration: none;
      font-weight: 500;
      border-bottom: 1px dotted #4B0082;
    }

    .back-link a:hover {
      border-bottom: none;
    }
  </style>
</head>
<body>

  <h1><i class="fas fa-users-cog"></i> Manage Staff Accounts</h1>

  <div class="actions">
    <a href="create-staff.php"><button><i class="fas fa-user-plus"></i> Create New Staff</button></a>
  </div>

  <table>
    <thead>
      <tr>
        <th>No.</th>
        <th>Staff ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Email</th>
        <th>Contact</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Username</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($staffList) > 0): ?>
        <?php $no = 1; foreach ($staffList as $staff): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($staff['staff_id']) ?></td>
            <td><?= htmlspecialchars($staff['staff_name']) ?></td>
            <td><?= htmlspecialchars($staff['role']) ?></td>
            <td><?= htmlspecialchars($staff['email']) ?></td>
            <td><?= htmlspecialchars($staff['contact_number']) ?></td>
            <td><?= htmlspecialchars($staff['date_of_birth']) ?></td>
            <td><?= htmlspecialchars($staff['gender']) ?></td>
            <td><?= htmlspecialchars($staff['username']) ?></td>
            <td class="action-btns">
              <a href="update-staff.php?id=<?= $staff['staff_id'] ?>">
                <button class="update-btn">Update</button>
              </a>
              <a href="delete-staff.php?id=<?= $staff['staff_id'] ?>" onclick="return confirm('Are you sure you want to delete this staff?');">
                <button class="delete-btn">Delete</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="10" style="text-align:center; color:#555;">No staff records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="back-link">
    <a href="admin-dashboard.php"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
  </div>

</body>
</html>
