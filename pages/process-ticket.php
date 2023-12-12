<?php
session_start(); // Start session

$_rand_id = rand(0, 999999);

// Store form data in session vars
$_SESSION['confirmation_data'] = [
    'fname' => $_POST['fname'],
    'subject' => $_POST['subject'],
    '_rand_id' => $_rand_id,
];


// Check if post data available
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $subject = $_POST['subject'];

    // Verbindung
    $conn = mysqli_connect("localhost", "admin", "password", "ticketsystem");

    // conn debug
    if ($conn->connect_error) {
        echo "Error with connecting to DB" . $conn->connect_error;
        die("Connection failed: " . $conn->connect_error);
    }

    // Input "reinigen"
    $fname = mysqli_real_escape_string($conn, $fname);
    $lname = mysqli_real_escape_string($conn, $lname);
    $subject = mysqli_real_escape_string($conn, $subject);

    // SQL str
    $sql = "INSERT INTO ticket(id, created, subject, assigned_to, creator, last_updated, priority) VALUES (?, NOW(), ?, 'Admins', ?, NOW(), 'Normal')";

    // Submit SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $_rand_id, $subject, $fname);
    $stmt->execute();

    // Verbindungen zur DB schließen
    $stmt->close();
    $conn->close();
}



// Redirect
header("Location: ticket-sent.php");
exit();
?>
