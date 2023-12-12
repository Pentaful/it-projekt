<?php
$conn = mysqli_connect("localhost", "admin", "password", "ticketsystem");

if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $delete_query = "DELETE FROM users WHERE id = $user_id";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "User mit ID $user_id wurde entfernt.";
    } else {
        echo "Fehler beim Entfernen von User: " . mysqli_error($conn);
    }
} else {
    echo "User ID nicht gegeben.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="2;url=/pages/admin/user_panel.php" />

</head>
<body>
        
</body>
</html>