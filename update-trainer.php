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
    echo "Trainer ID not provided.";
    exit();
}

$trainer_id = $_GET['id'];

// Fetch current trainer data
$stmt = $conn->prepare("SELECT * FROM trainer WHERE trainer_id = ?");
$stmt->bind_param("s", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Trainer not found.";
    exit();
}

$trainer = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_name = $_POST['trainer_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $speciality = $_POST['speciality'];
    $qualification = $_POST['qualification'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $stmt = $conn->prepare("UPDATE trainer SET trainer_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, speciality=?, qualification=?, username=?, role=?, password=? WHERE trainer_id=?");
        $stmt->bind_param("sssssssssss", $trainer_name, $email, $contact_number, $date_of_birth, $gender, $speciality, $qualification, $username, $role, $password, $trainer_id);
    } else {
        $stmt = $conn->prepare("UPDATE trainer SET trainer_name=?, email=?, contact_number=?, date_of_birth=?, gender=?, speciality=?, qualification=?, username=?, role=? WHERE trainer_id=?");
        $stmt->bind_param("ssssssssss", $trainer_name, $email, $contact_number, $date_of_birth, $gender, $speciality, $qualification, $username, $role, $trainer_id);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: manage-trainer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Trainer | UniSpa</title>
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
    .note { font-size: 0.9em; color: #666; margin-top: -10px; margin-bottom: 15px; }
    .back-link { margin-top: 20px; text-align: center; }
    .back-link a { color: #4B0082; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>
  <h1><i class="fas fa-user-edit"></i> Update Trainer</h1>
  <form method="POST">
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="trainer_name" required value="<?= htmlspecialchars($trainer['trainer_name']) ?>">
    </div>

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" required value="<?= htmlspecialchars($trainer['username']) ?>">
    </div>

    <div class="form-group">
      <label>Role</label>
      <select name="role" required>
        <option value="Trainer" <?= $trainer['role'] == 'Trainer' ? 'selected' : '' ?>>Trainer</option>
      </select>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" required value="<?= htmlspecialchars($trainer['email']) ?>">
    </div>

    <div class="form-group">
      <label>Contact Number</label>
      <input type="text" name="contact_number" required value="<?= htmlspecialchars($trainer['contact_number']) ?>">
    </div>

    <div class="form-group">
      <label>Date of Birth</label>
      <input type="date" name="date_of_birth" required value="<?= htmlspecialchars($trainer['date_of_birth']) ?>">
    </div>

    <div class="form-group">
      <label>Gender</label>
      <select name="gender" required>
        <option value="Male" <?= $trainer['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $trainer['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
      </select>
    </div>

    <div class="form-group">
      <label>Speciality</label>
      <input type="text" name="speciality" required value="<?= htmlspecialchars($trainer['speciality']) ?>">
    </div>

    <div class="form-group">
      <label>Qualification</label>
      <input type="text" name="qualification" required value="<?= htmlspecialchars($trainer['qualification']) ?>">
    </div>

    <div class="form-group">
      <label>New Password (optional)</label>
      <input type="password" name="password" placeholder="Leave blank to keep current password">
      <div class="note">Leave this field empty if you do not want to change the password.</div>
    </div>

    <button type="submit"><i class="fas fa-save"></i> Save Changes</button>
  </form>

  <div class="back-link">
    <a href="manage-trainer.php"><i class="fas fa-arrow-left"></i> Back to Trainer List</a>
  </div>
</body>
</html>
