<?php
session_start(); // Start session

// Check if  data is available
if (!isset($_SESSION['confirmation_data'])) {
    // Redirect to the home page if data is missing
    header("Location: /index.html"); // Adjust the actual home page URL
    exit();
}

// Retrieve and unset session data
$data = $_SESSION['confirmation_data'];
unset($_SESSION['confirmation_data']);
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <title>Ticket erstellt - Ticketsystem MSO</title>
    <link rel="icon" type="image/x-icon" href="/media/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="refresh" content="5;url=/index.html" />
  </head>
  <body>
    <script>    
      var timestamp = new Date().getTime();
      document.write('<script src="/sidebar/script.js?v=' + timestamp + '"><\/script>');
    </script>

    <div id="sidebarContainer"></div>

    <div id="main-sent" class="main">
      <!-- Hallo <?php echo $_POST["fname"]; ?><br>
      Ihr Anliegen <i><?php echo $_POST["subject"]; ?></i> ist nun mit der Nummer <?php echo $_rand_id; ?> versehen und die IT wird sich demnächst mit Ihrem Problem auseinandersetzen.
    -->
    Ihr Ticket ist nun erfolgreich erstellt worden. Die IT wird sich mit ihrem Problem demnächst auseinandersetzen.
    </div> 
  </body>
</html>
