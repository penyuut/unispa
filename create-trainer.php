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

    // Generate unique trainer ID like TR001, TR002
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(trainer_id, 3) AS UNSIGNED)) AS max_id FROM trainer");
    $row = $result->fetch_assoc();
    $maxNum = $row['max_id'] ?? 0;
    $nextNum = $maxNum + 1;
    $trainer_id = 'TR' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

    // Get form data
    $name = $_POST['trainer_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $speciality = $_POST['speciality'];
    $qualification = $_POST['qualification'];
    $username = $_POST['username'];
    $rawPassword = $_POST['password']; // plain password
    $role = "Trainer";

    // Store in DB without hashing
    $stmt = $conn->prepare("INSERT INTO trainer 
        (trainer_id, trainer_name, email, contact_number, date_of_birth, gender, speciality, qualification, username, password, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $trainer_id, $name, $email, $contact, $dob, $gender, $speciality, $qualification, $username, $rawPassword, $role);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Send activation email using Python
    $escapedID = escapeshellarg($trainer_id);
    $escapedUser = escapeshellarg($username);
    $escapedPass = escapeshellarg($rawPassword);
    $command = "python C:\\xampp7\\htdocs\\UNISPA\\send_activation_email_trainer.py $escapedID $escapedUser $escapedPass";
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "<script>alert('Trainer created and activation email sent.'); window.location='manage-trainer.php';</script>";
    } else {
        echo "<script>alert('Trainer created, but failed to send email.'); window.location='manage-trainer.php';</script>";
    }

    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Trainer | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body { font-family: Arial, sans-serif; background: #f3eaff; padding: 40px; display: flex; flex-direction: column; align-items: center; }
    h1 { color: #4B0082; margin-bottom: 30px; }
    form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }
    .form-group { margin-bottom: 20px; }
    label { font-weight: 600; display: block; margin-bottom: 8px; color: #333; }
    input, select { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; font-size: 1em; }
    button { background-color: #4B0082; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; }
    button:hover { background-color: #6a0dad; }
    .back-link { margin-top: 20px; text-align: center; }
    .back-link a { color: #4B0082; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>
  <h1><i class="fas fa-user-plus"></i> Create New Trainer</h1>
  <form method="POST">
    <div class="form-group">
      <label for="trainer_name">Name</label>
      <input type="text" name="trainer_name" required>
    </div>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" required>
    </div>
    <div class="form-group">
      <label for="role">Role</label>
      <select name="role" required>
        <option value="">-- Select Role --</option>
        <option value="Trainer">Trainer</option>
      </select>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="contact_number">Contact Number</label>
      <input type="text" name="contact_number" required>
    </div>
    <div class="form-group">
      <label for="date_of_birth">Date of Birth</label>
      <input type="date" name="date_of_birth" required>
    </div>
    <div class="form-group">
      <label for="gender">Gender</label>
      <select name="gender" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </div>
    <div class="form-group">
      <label for="speciality">Speciality</label>
      <input type="text" name="speciality" required>
    </div>
    <div class="form-group">
      <label for="qualification">Qualification</label>
      <input type="text" name="qualification" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit"><i class="fas fa-save"></i> Create Trainer</button>
  </form>
  <div class="back-link">
    <a href="manage-trainer.php"><i class="fas fa-arrow-left"></i> Back to Trainer List</a>
  </div>
</body>
</html>
