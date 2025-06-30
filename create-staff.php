<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "unispa");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get role from form
    $role = $_POST['role'];
    $prefix = ($role === 'Admin') ? 'A' : 'S';

    // Generate ID like A001 or S001
    $query = $conn->prepare("SELECT MAX(CAST(SUBSTRING(staff_id, 2) AS UNSIGNED)) AS max_id FROM staff WHERE staff_id LIKE ?");
    $like = $prefix . '%';
    $query->bind_param("s", $like);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $max = $row['max_id'] ?? 0;
    $staff_id = $prefix . str_pad($max + 1, 3, '0', STR_PAD_LEFT);
    $query->close();

    // Get values
    $name = $_POST['staff_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $rawPassword = $_POST['password']; // plain text password

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO staff 
        (staff_id, role, staff_name, email, contact_number, date_of_birth, gender, username, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $staff_id, $role, $name, $email, $contact, $dob, $gender, $username, $rawPassword);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Send activation email via Python
    $escapedID = escapeshellarg($staff_id);
    $escapedUser = escapeshellarg($username);
    $escapedPass = escapeshellarg($rawPassword);
    $command = "python C:\\xampp7\\htdocs\\UNISPA\\send_activation_email_staff.py $escapedID $escapedUser $escapedPass";
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "<script>alert('Staff created and activation email sent.'); window.location='manage-staff.php';</script>";
    } else {
        echo "<script>alert('Staff created, but failed to send email.'); window.location='manage-staff.php';</script>";
    }

    exit();
}
?>


<!DOCTYPE html>
<html>
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

<h1 style="text-align: center; color: #4B0082;">
  <i class="fas fa-user-tie"></i> Create New Staff / Admin
</h1>
<form method="POST">

  <label>Role</label>
  <select name="role" required>
    <option value="">-- Select Role --</option>
    <option value="Staff">Staff</option>
    <option value="Admin">Admin</option>
  </select>

  <label>Name</label>
  <input type="text" name="staff_name" required>

  <label>Email</label>
  <input type="email" name="email" required>

  <label>Contact Number</label>
  <input type="text" name="contact_number" required>

  <label>Date of Birth</label>
  <input type="date" name="date_of_birth" required>

  <label>Gender</label>
  <select name="gender" required>
    <option value="">Select</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>

  <label>Username</label>
  <input type="text" name="username" required>

  <label>Password</label>
  <input type="password" name="password" required>

  <button type="submit">Save Staff/Admin</button>
</form>

</body>
</html>
