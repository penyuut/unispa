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

if (!isset($_GET['id'])) {
    echo "No staff ID provided.";
    exit();
}

$staff_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $name = $_POST['staff_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Update all including password
        $stmt = $conn->prepare("UPDATE staff SET role=?, staff_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, username=?, password=? WHERE staff_id=?");
        $stmt->bind_param("sssssssss", $role, $name, $email, $contact, $dob, $gender, $username, $password, $staff_id);
    } else {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE staff SET role=?, staff_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, username=? WHERE staff_id=?");
        $stmt->bind_param("ssssssss", $role, $name, $email, $contact, $dob, $gender, $username, $staff_id);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: manage-staff.php");
    exit();
}

// Fetch current staff data
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->bind_param("s", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$staff) {
    echo "Staff not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Staff</title>
  <style>
    body { font-family: Arial; background: #f0e8ff; padding: 40px; }
    form {
      background: white;
      width: 70%;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px #ccc;
    }
    label { display: block; font-weight: bold; margin-top: 12px; }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 4px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    input[disabled] {
      background: #eee;
    }
    button {
      background: #4B0082;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .note { font-size: 0.9em; color: #666; }
  </style>
</head>
<body>

<h1 style="text-align:center;">Update Staff</h1>
<form method="POST">
  <label>Staff ID</label>
  <input type="text" value="<?= htmlspecialchars($staff['staff_id']) ?>" disabled>

  <label>Role</label>
  <input type="text" name="role" value="<?= htmlspecialchars($staff['role']) ?>" required>

  <label>Name</label>
  <input type="text" name="staff_name" value="<?= htmlspecialchars($staff['staff_name']) ?>" required>

  <label>Email</label>
  <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>

  <label>Contact Number</label>
  <input type="text" name="contact_number" value="<?= htmlspecialchars($staff['contact_number']) ?>" required>

  <label>Date of Birth</label>
  <input type="date" name="date_of_birth" value="<?= htmlspecialchars($staff['date_of_birth']) ?>" required>

  <label>Gender</label>
  <select name="gender" required>
    <option value="Male" <?= $staff['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $staff['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
  </select>

  <label>Username</label>
  <input type="text" name="username" value="<?= htmlspecialchars($staff['username']) ?>" required>

  <label>Password</label>
  <input type="password" name="password" placeholder="Leave blank to keep current password">
  <div class="note">Leave blank to keep the current password.</div>

  <button type="submit">Save Changes</button>
</form>

</body>
</html>
