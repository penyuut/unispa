<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM trainer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Debug output to help you check what's wrong
        echo "<pre>";
        echo "Entered password: [$password]\n";
        echo "Database password: [{$row['password']}]\n";
        echo "</pre>";

        // Plain-text password check
        if ($password === $row['password']) {
            $_SESSION['trainer'] = [
                'id' => $row['trainer_id'],
                'username' => $row['username'],
                'name' => $row['trainer_name']
            ];
            header("Location: trainer-dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Trainer Login | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet"/>
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

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 40px 30px;
      width: 350px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .login-container .icon {
      font-size: 4em;
      color: #4B0082;
      margin-bottom: 10px;
    }

    h1 {
      font-size: 2em;
      color: #4B0082;
      margin-bottom: 10px;
    }

    p {
      font-size: 0.95em;
      color: #555;
      margin-bottom: 25px;
    }

    .error-msg {
      color: red;
      font-size: 0.9em;
      margin-bottom: 15px;
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
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #333;
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

    .back-link {
      margin-top: 15px;
      font-size: 0.9em;
    }

    .back-link a {
      color: #4B0082;
      text-decoration: none;
      border-bottom: 1px dotted #4B0082;
    }

    .back-link a:hover {
      border-bottom: 1px solid transparent;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="icon"><i class="fas fa-user-tie"></i></div>
    <h1>Trainer Login</h1>
    <p>Access your UniSpa dashboard</p>

    <?php if (!empty($error)): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-wrapper">
          <i class="fas fa-user"></i>
          <input type="text" id="username" name="username" placeholder="Enter Username" required />
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" placeholder="Enter Password" required />
        </div>
      </div>

      <button type="submit">Login</button>
    </form>

    <div class="back-link">
      <a href="index.html">&larr; Back to Home</a>
    </div>
  </div>
</body>
</html>

