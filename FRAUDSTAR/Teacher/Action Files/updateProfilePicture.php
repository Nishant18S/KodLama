<?php

$servername = "localhost";
$username = "root"; 
$password = "Nishant2003@"; 
$dbname = "village"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    
    $userId = intval($_POST['userId']);
    
    
    $targetDir = "uploads/"; 
    $fileName = basename($_FILES["profile_picture"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(array("status" => "error", "message" => "Invalid file type."));
        exit;
    }

    
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        
        $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $fileName, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Profile picture updated successfully."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Database update failed."));
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "File upload failed."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "No file uploaded or file upload error."));
}

$conn->close();
?>
