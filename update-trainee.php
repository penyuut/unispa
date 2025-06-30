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
    echo "No trainee ID provided.";
    exit();
}

$trainee_id = $_GET['id'];

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['trainee_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $progress = $_POST['progress_level'];
    $enroll = $_POST['enrollment_date'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = "Trainee";

    // Check if username is already used by another trainee
    $check = $conn->prepare("SELECT trainee_id FROM trainee WHERE username = ? AND trainee_id != ?");
    $check->bind_param("ss", $username, $trainee_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $check->close();
        $conn->close();
        echo "<script>alert('Username already taken by another trainee.'); window.history.back();</script>";
        exit();
    }
    $check->close();

    // If password field is filled, update password
    if (!empty($password)) {
        $stmt = $conn->prepare("UPDATE trainee SET trainee_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, progress_level=?, enrollment_date=?, username=?, password=?, role=? WHERE trainee_id=?");
        $stmt->bind_param("sssssssssss", $name, $email, $contact, $dob, $gender, $progress, $enroll, $username, $password, $role, $trainee_id);
    } else {
        // No password change
        $stmt = $conn->prepare("UPDATE trainee SET trainee_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, progress_level=?, enrollment_date=?, username=?, role=? WHERE trainee_id=?");
        $stmt->bind_param("ssssssssss", $name, $email, $contact, $dob, $gender, $progress, $enroll, $username, $role, $trainee_id);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Trainee updated successfully.'); window.location='manage-trainee.php';</script>";
    exit();
}

// Fetch trainee data
$stmt = $conn->prepare("SELECT * FROM trainee WHERE trainee_id = ?");
$stmt->bind_param("s", $trainee_id);
$stmt->execute();
$result = $stmt->get_result();
$trainee = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$trainee) {
    echo "Trainee not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Trainee | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0e8ff;
      padding: 40px;
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 30px;
    }

    form {
      width: 70%;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #333;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 18px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    input[disabled] {
      background-color: #eee;
    }

    button {
      background-color: #4B0082;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      display: block;
      margin: auto;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      text-decoration: none;
      color: #4B0082;
      font-weight: 500;
    }

    .note {
      font-size: 0.9em;
      color: #666;
      margin-top: -12px;
      margin-bottom: 12px;
    }
  </style>
</head>
<body>

<h1><i class="fas fa-user-edit"></i> Update Trainee Info</h1>

<form method="POST">
  <label for="trainee_id">Trainee ID</label>
  <input type="text" value="<?= htmlspecialchars($trainee['trainee_id']) ?>" disabled>

  <label for="trainee_name">Trainee Name</label>
  <input type="text" name="trainee_name" value="<?= htmlspecialchars($trainee['trainee_name']) ?>" required>

  <label for="email">Email</label>
  <input type="email" name="email" value="<?= htmlspecialchars($trainee['email']) ?>" required>

  <label for="contact_number">Contact Number</label>
  <input type="text" name="contact_number" value="<?= htmlspecialchars($trainee['contact_number']) ?>" required>

  <label for="date_of_birth">Date of Birth</label>
  <input type="date" name="date_of_birth" value="<?= $trainee['date_of_birth'] ?>" required>

  <label for="gender">Gender</label>
  <select name="gender" required>
    <option value="Male" <?= $trainee['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $trainee['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
  </select>

  <label for="progress_level">Progress Level</label>
  <input type="text" name="progress_level" value="<?= htmlspecialchars($trainee['progress_level']) ?>" required>

  <label for="enrollment_date">Enrollment Date</label>
  <input type="date" name="enrollment_date" value="<?= $trainee['enrollment_date'] ?>" required>

  <label for="username">Username</label>
  <input type="text" name="username" value="<?= htmlspecialchars($trainee['username']) ?>" required>

  <label for="role">Role</label>
  <input type="text" name="role" value="Trainee" disabled>

  <label for="password">Password <span class="note">(Leave blank if you don't want to change it)</span></label>
  <input type="password" name="password">

  <button type="submit"><i class="fas fa-save"></i> Update Trainee</button>
</form>

<div class="back-link">
  <a href="manage-trainee.php"><i class="fas fa-arrow-left"></i> Back to Trainee List</a>
</div>

</body>
</html>
