<?php
header("Content-Type: application/json");

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

$cancelPassword = "1234"; // 🔒 Mật khẩu hủy bàn

// 🟢 LẤY DANH SÁCH BÀN ĐÃ ĐẶT
if (isset($_GET["action"]) && $_GET["action"] === "get") {
  $result = $conn->query("SELECT table_id FROM bookings");
  $tables = [];
  while ($row = $result->fetch_assoc()) {
    $tables[] = $row;
  }
  echo json_encode($tables);
  $conn->close();
  exit;
}

// 🟡 XỬ LÝ POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST["action"] ?? "";

  // 🟢 ĐẶT BÀN
  if ($action === "book") {
    $table_id = $_POST["table_id"] ?? "";
    $name = $_POST["name"] ?? "";
    $phone = $_POST["phone"] ?? "";
    $date = $_POST["date"] ?? "";
    $time = $_POST["time"] ?? "";

    if (!$table_id || !$name || !$phone || !$date || !$time) {
      echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
      exit;
    }

    if (!preg_match("/^[0-9]{9,11}$/", $phone)) {
      echo json_encode(["success" => false, "message" => "Invalid phone number."]);
      exit;
    }

    $today = date("Y-m-d");
    if ($date < $today) {
      echo json_encode(["success" => false, "message" => "Date cannot be in the past."]);
      exit;
    }

    if ($time < "14:00" || $time > "22:30") {
      echo json_encode(["success" => false, "message" => "Booking time must be between 14:00 and 22:30."]);
      exit;
    }

    $check = $conn->prepare("SELECT * FROM bookings WHERE table_id = ? AND date = ?");
    $check->bind_param("ss", $table_id, $date);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      echo json_encode(["success" => false, "message" => "This table is already booked."]);
      exit;
    }

    $stmt = $conn->prepare("INSERT INTO bookings (table_id, name, phone, date, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $table_id, $name, $phone, $date, $time);

    if ($stmt->execute()) {
      echo json_encode(["success" => true, "message" => "Booking confirmed!"]);
    } else {
      echo json_encode(["success" => false, "message" => "Error while booking."]);
    }
  }

  // 🔴 HỦY BÀN
  elseif ($action === "cancel") {
    $table_id = $_POST["table_id"] ?? "";
    $passwordInput = $_POST["password"] ?? "";

    if ($passwordInput !== $cancelPassword) {
      echo json_encode(["success" => false, "message" => "Incorrect password."]);
      exit;
    }

    $stmt = $conn->prepare("DELETE FROM bookings WHERE table_id = ?");
    $stmt->bind_param("s", $table_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      echo json_encode(["success" => true, "message" => "Booking cancelled."]);
    } else {
      echo json_encode(["success" => false, "message" => "No booking found for this table."]);
    }
  }

  else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
  }
}
$conn->close();
?>
