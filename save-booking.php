<?php
// 1. Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "unispa"; // your database name
$conn = new mysqli($host, $username, $password, $database);

// 2. Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 3. Get form values
$customer_name = $_POST['customer_name'];
$service_type = $_POST['service_type'];
$service_name = $_POST['service_name'];
$booking_date = $_POST['booking_date'];
$booking_time = $_POST['booking_time'];

// 4. Insert into database
$sql = "INSERT INTO bookings (customer_name, service_type, service_name, booking_date, booking_time)
        VALUES ('$customer_name', '$service_type', '$service_name', '$booking_date', '$booking_time')";

if ($conn->query($sql) === TRUE) {
  echo "Booking saved successfully!";
  // You can also redirect: header("Location: booking-confirmation.html");
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
