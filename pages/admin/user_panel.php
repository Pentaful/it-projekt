<?php
// Connect to the database
$conn = mysqli_connect("localhost", "admin", "password", "ticketsystem");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted to add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_username"]) && isset($_POST["new_password"])) {
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_query = "INSERT INTO users (username, password) VALUES ('$new_username', '$hashed_password')";
    $insert_result = mysqli_query($conn, $insert_query);

    if (!$insert_result) {
        die("Insert failed: " . mysqli_error($conn));
    }
}

// SQL query to fetch user data
$sql = "SELECT id, username FROM users";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <script>    
        var timestamp = new Date().getTime();
        document.write('<script src="/sidebar/script.js?v=' + timestamp + '"><\/script>');
        document.write('<script src="/topbar/script.js?v=' + timestamp + '"><\/script>');
    </script>
    <div id="topbarContainer"></div>

    <div id="main" class="main">
        <div id="sidebarContainer"></div>

        <h2>User Management</h2>

        <h3>Benutzer:</h3>
        <ul>
            <?php
            // Loop through the result set and output user data
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>{$row['username']} - <a href='remove_user.php?id={$row['id']}'>Remove</a></li>";
            }
            ?>
        </ul>

        <h3>Benutzer hinzufügen:</h3>
        <form action="" method="post">
            <label for="new_username">Username:</label>
            <input type="text" id="new_username" name="new_username" required>

            <label for="new_password">Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <input type="submit" value="Add User">
        </form>
    </div>

    <?php
    mysqli_close($conn);
    ?>
</body>
</html>


