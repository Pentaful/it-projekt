<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// redirect
if (isset($_SESSION["username"])) {
    echo "Session variable set: " . $_SESSION["username"];
    header("Location: user_panel.php");
    exit();
}

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    $conn = mysqli_connect("localhost", "admin", "password", "ticketsystem");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // validate hashed pw
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // verify hashed pw
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                header("Location: user_panel.php");
                exit();
            } else {
                $error_message = "Invalid login";
            }
        } else {
            $error_message = "Invalid login";
        }

        mysqli_stmt_close($stmt);
    } else {
        // error handler
        die("Error in prepared statement: " . mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MSO-Ticketsystem</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div id="sidebarContainer"></div>
    <!-- <div id="topbarContainer"></div> -->

    <script>    
        var timestamp = new Date().getTime();
        document.write('<script src="/sidebar/script.js?v=' + timestamp + '"><\/script>');
        // document.write('<script src="/topbar/script.js?v=' + timestamp + '"><\/script>');
    </script>
    <!-- Sidebar -->
    <main id="main">
        <h2>Login</h2>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;padding: 10px;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Benutzer:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </main>
</body>
</html>
