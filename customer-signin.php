<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
        $_SESSION['customer'] = $customer;
        header("Location: customer-dashboard.php");
        exit();
    } else {
        $loginError = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer Login | UniSpa</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e9e4f0, #d3cce3);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .signin-container {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 500px;
    }

    h1 {
      text-align: center;
      color: #4B0082;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      color: #4B0082;
    }

    input {
      width: 90%;
      padding: 10px 10px 10px 35px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 5px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #4B0082;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #6a0dad;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }

    .signup-link {
      text-align: center;
      margin-top: 15px;
      font-size: 0.9em;
    }

    .signup-link a {
      color: #4B0082;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="signin-container">
    <h1><i class="fas fa-user-circle"></i> Customer Login</h1>
    <form method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-wrapper">
          <i class="fas fa-user"></i>
          <input type="text" name="username" id="username" placeholder="Enter your username" required />
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="Enter your password" required />
        </div>
      </div>

      <button type="submit">Login</button>
    </form>

    <?php if ($loginError): ?>
      <div class="error"><?= $loginError ?></div>
    <?php endif; ?>

    <div class="signup-link">
      Don't have an account? <a href="customer-signup.php">Sign up here</a>
    </div>
  </div>
</body>
</html>
