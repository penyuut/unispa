<?php
session_start();
if (!isset($_SESSION['trainee'])) {
    header("Location: trainee-login.php");
    exit();
}

$traineeID = $_SESSION['trainee']['trainee_id'];

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all workshop titles
$workshopTitles = [];
$workshopResult = $conn->query("SELECT workshop_id, title FROM workshop");
while ($row = $workshopResult->fetch_assoc()) {
    $workshopTitles[$row['workshop_id']] = $row['title'];
}

$message = '';
$editMode = false;
$editLogbook = [
    'logbook_id' => '',
    'workshop_id' => '',
    'content' => ''
];

// Handle Delete
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM logbook WHERE logbook_id = ? AND trainee_id = ?");
    $stmt->bind_param("ss", $deleteId, $traineeID);
    $stmt->execute();
    $stmt->close();
    $message = "Logbook entry deleted.";
}

// Handle Edit (load data into form)
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM logbook WHERE logbook_id = ? AND trainee_id = ?");
    $stmt->bind_param("ss", $editId, $traineeID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $editLogbook = $result->fetch_assoc();
        $editMode = true;
    }
    $stmt->close();
}

// Handle form submission (create or update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workshopId = $_POST['workshop_id'] ?? '';
    $content = trim($_POST['content'] ?? '');
    $date = date('Y-m-d');
    $logbookId = $_POST['logbook_id'] ?? '';

    if ($workshopId && $content) {
        if (!empty($logbookId)) {
            // Update
            $stmt = $conn->prepare("UPDATE logbook SET workshop_id = ?, content = ?, date_submitted = ? WHERE logbook_id = ? AND trainee_id = ?");
            $stmt->bind_param("sssss", $workshopId, $content, $date, $logbookId, $traineeID);
            $stmt->execute();
            $stmt->close();
            $message = "Logbook updated successfully.";
        } else {
            // Create
            $stmt = $conn->prepare("INSERT INTO logbook (trainee_id, workshop_id, date_submitted, content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $traineeID, $workshopId, $date, $content);
            $stmt->execute();
            $stmt->close();
            $message = "Logbook submitted successfully.";
        }

        // Reset edit state
        $editMode = false;
        $editLogbook = ['logbook_id' => '', 'workshop_id' => '', 'content' => ''];
    } else {
        $message = "Please select a workshop and fill in the logbook content.";
    }
}

// Fetch all logbooks for this trainee
$logbooks = [];
$stmt = $conn->prepare("SELECT * FROM logbook WHERE trainee_id = ? ORDER BY date_submitted DESC");
$stmt->bind_param("s", $traineeID);
$stmt->execute();
$result = $stmt->get_result();
$logbooks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Logbook Submission | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #f8f8ff, #e9e4f0);
      padding: 40px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #4B0082;
    }
    .message {
      color: green;
      text-align: center;
      margin-bottom: 20px;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 20px;
    }
    form {
      margin-bottom: 40px;
    }
    label, select, textarea {
      display: block;
      width: 100%;
      margin-bottom: 15px;
    }
    textarea {
      height: 100px;
      padding: 10px;
      font-size: 1em;
    }
    .submit-btn {
      background: #4B0082;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #6a0dad;
    }
    .logbook-entry {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      background: #fafafa;
    }
    .logbook-entry h3 {
      margin: 0 0 10px;
      color: #4B0082;
    }
    .action-links a {
      margin-right: 15px;
      color: #4B0082;
      font-weight: bold;
      text-decoration: none;
    }
    .action-links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-book"></i> <?= $editMode ? 'Edit Logbook' : 'Submit Your Logbook' ?></h1>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="logbook_id" value="<?= htmlspecialchars($editLogbook['logbook_id']) ?>" />

      <label for="workshop_id">Select Workshop:</label>
      <select name="workshop_id" id="workshop_id" required>
        <option value="">-- Choose Workshop --</option>
        <?php foreach ($workshopTitles as $id => $title): ?>
          <option value="<?= $id ?>" <?= $editLogbook['workshop_id'] == $id ? 'selected' : '' ?>>
            <?= htmlspecialchars($title) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="content">Logbook Content:</label>
      <textarea name="content" id="content" required><?= htmlspecialchars($editLogbook['content']) ?></textarea>

      <button type="submit" class="submit-btn"><?= $editMode ? 'Update Logbook' : 'Submit Logbook' ?></button>
    </form>

    <h2>Your Submitted Logbooks</h2>
    <?php if ($logbooks): ?>
      <?php foreach ($logbooks as $entry): ?>
        <div class="logbook-entry">
          <h3><?= isset($workshopTitles[$entry['workshop_id']])
              ? htmlspecialchars($workshopTitles[$entry['workshop_id']])
              : 'Unknown Workshop' ?></h3>
          <p><strong>Date:</strong> <?= htmlspecialchars($entry['date_submitted']) ?></p>
          <p><strong>Content:</strong><br><?= nl2br(htmlspecialchars($entry['content'])) ?></p>
          <div class="action-links">
            <a href="?edit=<?= $entry['logbook_id'] ?>"><i class="fas fa-edit"></i> Edit</a>
            <a href="?delete=<?= $entry['logbook_id'] ?>" onclick="return confirm('Are you sure you want to delete this logbook?');"><i class="fas fa-trash"></i> Delete</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No logbooks submitted yet.</p>
    <?php endif; ?>

   <a href="trainee-dashboard.php" class="back-link">&larr; Back to Dashboard</a>
  </div>
</body>
</html>
