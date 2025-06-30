<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

// Redirect to login if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer-signin.php");
    exit();
}

$customer = $_SESSION['customer'];
$custID = $customer['custID'];

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $custType = $_POST['custType'];
    $password = $_POST['password'];

    // Handle profile picture upload
    $profilePic = $customer['profile_picture'] ?? null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/customers/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $newFilename = $custID . "_" . time() . "." . $extension;
        $targetFile = $targetDir . $newFilename;

        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);
        $profilePic = $targetFile;
    }

    // Update customer data
    $stmt = $conn->prepare("UPDATE customers SET username=?, email=?, contact_number=?, dob=?, gender=?, custType=?, password=?, profile_picture=? WHERE custID=?");
    $stmt->bind_param("sssssssss", $username, $email, $contact, $dob, $gender, $custType, $password, $profilePic, $custID);
    $stmt->execute();
    $stmt->close();

    // Update session data
    $result = $conn->query("SELECT * FROM customers WHERE custID = '$custID'");
    $_SESSION['customer'] = $result->fetch_assoc();

    echo "<script>alert('Profile updated successfully!'); window.location.href='customer-viewProfile.php';</script>";
    exit();
}

// Fetch latest profile info
$result = $conn->query("SELECT * FROM customers WHERE custID = '$custID'");
$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0c3fc, #8ec5fc);
      padding: 40px;
    }
    .container {
      max-width: 600px;
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
    }
    input, select, button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    button {
      background: #4B0082;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #6a0dad;
    }
    .profile-img {
      text-align: center;
    }
    .profile-img img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid #4B0082;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-user-edit"></i> Update Profile</h1>

    <div class="profile-img">
      <img src="<?= htmlspecialchars($customer['profile_picture'] ?? 'uploads/default.png') ?>" alt="Profile Picture">
    </div>

    <form method="post" enctype="multipart/form-data">
      <label for="username">Username</label>
      <input type="text" name="username" value="<?= htmlspecialchars($customer['username']) ?>" required>

      <label for="email">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>

      <label for="contact_number">Contact Number</label>
      <input type="text" name="contact_number" value="<?= htmlspecialchars($customer['contact_number']) ?>" required>

      <label for="dob">Date of Birth</label>
      <input type="date" name="dob" value="<?= htmlspecialchars($customer['dob']) ?>" required>

      <label for="gender">Gender</label>
      <select name="gender" required>
        <option value="Male" <?= $customer['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $customer['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
      </select>

      <label for="custType">Customer Type</label>
      <select name="custType" required>
        <option value="student" <?= $customer['custType'] === 'student' ? 'selected' : '' ?>>Student</option>
        <option value="staff" <?= $customer['custType'] === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="others" <?= $customer['custType'] === 'others' ? 'selected' : '' ?>>Others</option>
      </select>

      <label for="password">Password</label>
      <input type="text" name="password" value="<?= htmlspecialchars($customer['password']) ?>" required>

      <label for="profile_picture">Change Profile Picture</label>
      <input type="file" name="profile_picture" accept="image/*">

      <button type="submit">Save Changes</button>
    </form>
  </div>


 <div class="signup-link">
      <a href="customer-dashboard.php">&larr; Back</a>
    </div>
</body>
</html>
