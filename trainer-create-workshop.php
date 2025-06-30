<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}

$trainer_id = $_SESSION['trainer']['id'];
$error = '';
$success = '';

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $location = $_POST['location'] ?? '';
    $capacity = $_POST['capacity'] ?? '';

    if ($title && $description && $date && $location && $capacity) {
        $stmt = $conn->prepare("INSERT INTO workshop (trainer_id, title, description, date, location, capacity) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $trainer_id, $title, $description, $date, $location, $capacity);
        if ($stmt->execute()) {
            $success = "Workshop created successfully!";
        } else {
            $error = "Failed to create workshop.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Workshop | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f3ff;
      padding: 40px;
    }

    form {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #4B0082;
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #333;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    textarea {
      resize: vertical;
    }

    button {
      background-color: #4B0082;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      margin-top: 20px;
      cursor: pointer;
      font-size: 1em;
      display: block;
      width: 100%;
    }

    button:hover {
      background-color: #6a0dad;
    }

    .message {
      text-align: center;
      font-size: 1em;
      margin-bottom: 15px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: #4B0082;
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>
<body>

<h1><i class="fas fa-plus-circle"></i> Create New Workshop</h1>

<?php if ($success): ?>
  <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php elseif ($error): ?>
  <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
  <label for="title">Workshop Title</label>
  <input type="text" name="title" id="title" required>

  <label for="description">Description</label>
  <textarea name="description" id="description" rows="4" required></textarea>

  <label for="date">Date</label>
  <input type="date" name="date" id="date" required>

  <label for="location">Location</label>
  <input type="text" name="location" id="location" required>

  <label for="capacity">Capacity</label>
  <input type="number" name="capacity" id="capacity" min="1" required>

  <button type="submit"><i class="fas fa-save"></i> Create Workshop</button>
</form>

<div class="back-link">
  <a href="trainer-manage-workshop.php"><i class="fas fa-arrow-left"></i> Back to Workshop List</a>
</div>

</body>
</html>
