<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "unispa");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Auto-generate trainee ID like T001
    $last = $conn->query("SELECT trainee_id FROM trainee ORDER BY trainee_id DESC LIMIT 1");
    $lastID = $last->num_rows ? $last->fetch_assoc()['trainee_id'] : 'T000';
    $num = (int)substr($lastID, 1) + 1;
    $trainee_id = 'T' . str_pad($num, 3, '0', STR_PAD_LEFT);

    // Get form data
    $name = $_POST['trainee_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $progress = $_POST['progress_level'];
    $enroll = $_POST['enrollment_date'];
    $username = $_POST['username'];
    $rawPassword = $_POST['password']; // plain password
    $role = "Trainee";

    // Store plain password (NOT SECURE for production)
    $stmt = $conn->prepare("INSERT INTO trainee 
        (trainee_id, trainee_name, email, contact_number, date_of_birth, gender, progress_level, enrollment_date, username, password, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $trainee_id, $name, $email, $contact, $dob, $gender, $progress, $enroll, $username, $rawPassword, $role);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Escape and send to Python
    $escapedID = escapeshellarg($trainee_id);
    $escapedUser = escapeshellarg($username);
    $escapedPass = escapeshellarg($rawPassword);

    $command = "python C:\\xampp7\\htdocs\\UNISPA\\send_activation_email.py $escapedID $escapedUser $escapedPass";
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "<script>alert('Trainee created and activation email sent.'); window.location='manage-trainee.php';</script>";
    } else {
        echo "<script>alert('Trainee created, but failed to send email.'); window.location='manage-trainee.php';</script>";
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Trainee | UniSpa</title>
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
  </style>
</head>
<body>

<h1><i class="fas fa-user-plus"></i> Create New Trainee</h1>

<form method="POST">
  <label for="trainee_name">Trainee Name</label>
  <input type="text" name="trainee_name" required>

  <label for="email">Email</label>
  <input type="email" name="email" required>

  <label for="contact_number">Contact Number</label>
  <input type="text" name="contact_number" required>

  <label for="date_of_birth">Date of Birth</label>
  <input type="date" name="date_of_birth" required>

  <label for="gender">Gender</label>
  <select name="gender" required>
    <option value="">Select Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>

  <label for="progress_level">Progress Level</label>
  <input type="text" name="progress_level" required>

  <label for="enrollment_date">Enrollment Date</label>
  <input type="date" name="enrollment_date" required>

  <label for="username">Username</label>
  <input type="text" name="username" required>

  <label for="password">Password</label>
  <input type="password" name="password" required>

  <button type="submit"><i class="fas fa-save"></i> Save Trainee</button>
</form>

<div class="back-link">
  <a href="manage-trainee.php"><i class="fas fa-arrow-left"></i> Back to Trainee List</a>
</div>

</body>
</html>
