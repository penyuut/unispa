<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

if (!isset($_SESSION['new_customer'])) {
    header("Location: customer-signup.php");
    exit();
}

$newCustomer = $_SESSION['new_customer'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $custID = $newCustomer['custID'];
    $username = $newCustomer['username'];
    $email = $newCustomer['email'];
    $password = $newCustomer['password'];

    $custType = $_POST['custType'];
    $contact = $_POST['contact_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("INSERT INTO customers (custID, username, custType, email, contact_number, dob, password, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $custID, $username, $custType, $email, $contact, $dob, $password, $gender);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['new_customer']);
    header("Location: customer-signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Complete Your Profile | UniSpa</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      padding: 40px;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
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
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      background-color: #4B0082;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Complete Your Profile</h1>
    <form method="post">
      <label for="custType">Customer Type</label>
      <select name="custType" required>
        <option value="">-- Select --</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
        <option value="others">Others</option>
      </select>

      <label for="contact_number">Contact Number</label>
      <input type="text" name="contact_number" placeholder="e.g. 012-3456789" required />

      <label for="dob">Date of Birth</label>
      <input type="date" name="dob" required />

      <label for="gender">Gender</label>
      <select name="gender" required>
        <option value="">-- Select --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <button type="submit">Submit Profile</button>
    </form>
  </div>
</body>
</html>
