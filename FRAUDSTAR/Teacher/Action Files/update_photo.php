<?php
$servername = "localhost";
$username = "root";
$password = "Nishant2003@";
$dbname = "village";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $fileSize = $_FILES['photo']['size'];
    $fileType = $_FILES['photo']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    if (in_array($fileExtension, $allowedExtensions) && $fileSize < $maxFileSize) {
        $uploadFileDir = './uploads/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $user_id = 1; 
            $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $newFileName, $user_id);

            if ($stmt->execute()) {
                echo $dest_path; 
            } else {
                echo "Database update failed.";
            }

            $stmt->close();
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "Invalid file type or file too large.";
    }
} else {
    echo "No file uploaded or upload error.";
}

$conn->close();
?>
