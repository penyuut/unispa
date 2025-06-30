<?php
session_start();

// OPTIONAL: Redirect if not staff
// if (!isset($_SESSION['staff'])) {
//     header("Location: staff-login.php");
//     exit();
// }

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Create
if (isset($_POST['add'])) {
    $id = $_POST['serviceID'];
    $name = $_POST['service_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $duration = $_POST['duration'];
    $promo = $_POST['promo_details'];

    $stmt = $conn->prepare("INSERT INTO services (serviceID, service_name, category, price, description, duration, promo_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsis", $id, $name, $category, $price, $desc, $duration, $promo);
    $stmt->execute();
    $stmt->close();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['serviceID'];
    $name = $_POST['service_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $duration = $_POST['duration'];
    $promo = $_POST['promo_details'];

    $stmt = $conn->prepare("UPDATE services SET service_name=?, category=?, price=?, description=?, duration=?, promo_details=? WHERE serviceID=?");
    $stmt->bind_param("ssdssss", $name, $category, $price, $desc, $duration, $promo, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: staff-services.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM services WHERE serviceID=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: staff-services.php");
    exit();
}

$services = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Spa Services | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #e9e4f0, #d3cce3);
      padding: 40px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    h1, h2 {
      text-align: center;
      color: #4B0082;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    table th, table td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    table th {
      background-color: #f3f0fa;
    }
    select, input, textarea, button {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      font-size: 1em;
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
    .edit-btn {
      background-color: #2e8b57;
      color: white;
      padding: 4px 8px;
      text-decoration: none;
      font-size: 0.85em;
      width: 48%;
      text-align: center;
    }
    .edit-btn:hover {
      background-color: #3cb371;
    }
    .del-btn {
      background-color: #cc0000;
      color: white;
      padding: 4px 8px;
      font-size: 0.85em; 
      text-decoration: none;
      width: 48%;
      text-align: center;
    }
    .del-btn:hover {
      background-color: #e60000;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Staff Manage Spa Services</h1>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Description</th>
          <th>Duration</th>
          <th>Promo</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $services->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['serviceID']) ?></td>
            <td><?= htmlspecialchars($row['service_name']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td>RM <?= number_format($row['price'], 2) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['duration'] ?> mins</td>
            <td><?= htmlspecialchars($row['promo_details']) ?></td>
            <td>
              <a href="staff-services.php?edit=<?= urlencode($row['serviceID']) ?>" class="edit-btn">Edit</a>
<a href="staff-services.php?delete=<?= urlencode($row['serviceID']) ?>" onclick="return confirm('Are you sure you want to delete this service?')" class="del-btn">Delete</a>

            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <?php if (isset($_GET['edit'])):
      $editID = $_GET['edit'];
      $edit = $conn->query("SELECT * FROM services WHERE serviceID='$editID'")->fetch_assoc();
    ?>
      <h2>Edit Service</h2>
      <form method="post">
        <input type="hidden" name="serviceID" value="<?= $edit['serviceID'] ?>">
        <input type="text" name="service_name" value="<?= $edit['service_name'] ?>" required>
        <select name="category" required>
          <option value="">Select Category</option>
          <?php
          $categories = [
            "Facial Treatments",
            "Message Therapies",
            "Nail & Foot Care",
            "Muslimah Hair Cut & Hair Spa (Women)",
            "Barber & Hair Spa (Men)",
            "Makeup"
          ];
          foreach ($categories as $cat) {
              $selected = ($edit['category'] === $cat) ? 'selected' : '';
              echo "<option value='$cat' $selected>$cat</option>";
          }
          ?>
        </select>
        <input type="number" step="0.01" name="price" value="<?= $edit['price'] ?>" required>
        <textarea name="description"><?= $edit['description'] ?></textarea>
        <input type="number" name="duration" value="<?= $edit['duration'] ?>" required>
        <input type="text" name="promo_details" value="<?= $edit['promo_details'] ?>">
        <button type="submit" name="update">Update Service</button>
      </form>
    <?php else: ?>
      <h2>Add New Service</h2>
      <form method="post">
        <input type="text" name="serviceID" placeholder="Service ID (e.g. S001)" required>
        <input type="text" name="service_name" placeholder="Service Name" required>
        <select name="category" required>
          <option value="">Select Category</option>
          <option value="Facial Treatments">Facial Treatments</option>
          <option value="Message Therapies">Message Therapies</option>
          <option value="Nail & Foot Care">Nail & Foot Care</option>
          <option value="Muslimah Hair Cut & Hair Spa (Women)">Muslimah Hair Cut & Hair Spa (Women)</option>
          <option value="Barber & Hair Spa (Men)">Barber & Hair Spa (Men)</option>
          <option value="Makeup">Makeup</option>
        </select>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="duration" placeholder="Duration (minutes)" required>
        <input type="text" name="promo_details" placeholder="Promo Details (optional)">
        <button type="submit" name="add">Add Service</button>
      </form>
    <?php endif; ?>

    <div class="signup-link">
      <a href="staff-dashboard.php">&larr; Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
