<?php
session_start();

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match');</script>";
  } else {
    // Auto-generate next customer ID
    $result = $conn->query("SELECT custID FROM customers ORDER BY custID DESC LIMIT 1");
    $latestID = ($result->num_rows > 0) ? $result->fetch_assoc()['custID'] : 'C000';
    $newID = 'C' . str_pad((int)substr($latestID, 1) + 1, 3, '0', STR_PAD_LEFT);

    // Store in session before going to details form
    $_SESSION['new_customer'] = [
      'custID' => $newID,
      'username' => $username,
      'email' => $email,
      'password' => $password
    ];

    header("Location: customer-details.php");
    exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .signin-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 40px 30px;
      width: 350px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .signin-container h1 {
      font-size: 2em;
      color: #4B0082;
      margin-bottom: 10px;
      text-align: center;
    }

    .signin-container p {
      font-size: 0.95em;
      text-align: center;
      color: #555;
      margin-bottom: 25px;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .form-group {
      width: 100%;
      max-width: 280px;
      margin-bottom: 15px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #4B0082;
    }

    .input-wrapper input {
      width: 100%;
      padding: 10px 10px 10px 35px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 0.95em;
      background-color: #f9f9f9;
      outline: none;
    }

    .input-wrapper input:focus {
      border-color: #4B0082;
      background-color: #fff;
    }

    button {
      background: #4B0082;
      color: white;
      padding: 12px 0;
      width: 100%;
      max-width: 280px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #6a0dad;
    }

    .signup-link {
      margin-top: 15px;
      text-align: center;
      font-size: 0.9em;
    }

    .signup-link a {
      color: #4B0082;
      text-decoration: none;
      border-bottom: 1px dotted #4B0082;
    }

    .signup-link a:hover {
      border-bottom: 1px solid transparent;
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <h1>Join UniSpa</h1>
    <p>Create your account below</p>
    <form method="post">
      <div class="form-group">
  <label for="username">Username</label>
  <div class="input-wrapper">
    <i class="fas fa-user"></i>
    <input type="text" id="username" name="username" placeholder="Choose a username" required />
  </div>
</div>


  <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-wrapper">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" name="email" placeholder="Email" required />
        </div>
      </div>


      <div class="form-group">
        <label for="password">Enter Password</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" placeholder="Password" required />
        </div>
      </div>

      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required />
        </div>
      </div>

      <button type="submit">Sign Up</button>
    </form>
    <div class="signin-link">
      Already have an account? <a href="customer-signin.php">Sign In</a>
    </div>
  </div>
</body>
</html>