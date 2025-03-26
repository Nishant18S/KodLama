<?php
$servername = "localhost";
$username = "root";
$password = "Nishant2003@";
$dbname = "village";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$new_username = isset($_POST['username']) ? $_POST['username'] : '';

if (empty($new_username)) {
    echo json_encode(['success' => false, 'error' => 'Username cannot be empty.']);
    exit();
}

$user_id = 1; 

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
$stmt->bind_param("si", $new_username, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update username.']);
}

$stmt->close();
$conn->close();
?>
