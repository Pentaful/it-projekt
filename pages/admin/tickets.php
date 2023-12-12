<?php
    session_start();

    if (!isset($_SESSION["username"])) {
        echo json_encode(["error" => "User not logged in"]);
        exit();
    }

    $conn = mysqli_connect("localhost", "admin", "password", "ticketsystem");

    if (!$conn) {
        echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
        exit();
    }

    if (isset($_GET["action"]) && $_GET["action"] === "delete" && isset($_GET["id"])) {
        $ticketId = mysqli_real_escape_string($conn, $_GET["id"]);
        $sql = "DELETE FROM ticket WHERE id = $ticketId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["success" => "Ticket deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error deleting ticket: " . mysqli_error($conn)]);
        }

        exit();
    }

    if (isset($_GET["action"]) && $_GET["action"] === "reassign" && isset($_GET["id"]) && isset($_GET["assignee"])) {
        $ticketId = mysqli_real_escape_string($conn, $_GET["id"]);
        $newAssignee = mysqli_real_escape_string($conn, $_GET["assignee"]);

        $sql = "UPDATE ticket SET assigned_to = '$newAssignee' WHERE id = $ticketId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["success" => "Ticket reassigned successfully"]);
        } else {
            echo json_encode(["error" => "Error reassigning ticket: " . mysqli_error($conn)]);
        }

        exit();
    }

    if (isset($_GET["action"]) && $_GET["action"] === "change_priority" && isset($_GET["id"]) && isset($_GET["priority"])) {
        $ticketId = mysqli_real_escape_string($conn, $_GET["id"]);
        $newPriority = mysqli_real_escape_string($conn, $_GET["priority"]);

        $sql = "UPDATE ticket SET priority = '$newPriority' WHERE id = $ticketId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["success" => "Ticket priority changed successfully"]);
        } else {
            echo json_encode(["error" => "Error changing ticket priority: " . mysqli_error($conn)]);
        }

        exit();
    }

    $sql = "SELECT id, created, creator, last_updated, subject, priority, assigned_to FROM ticket";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
        exit();
    }

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datenbank - Ticketsystem MSO</title>
    <link rel="icon" type="image/x-icon" href="/media/favicon.ico">
    <link rel="stylesheet" href="../styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body>
    <script>    
        var timestamp = new Date().getTime();
        document.write('<script src="/sidebar/script.js?v=' + timestamp + '"><\/script>');
        document.write('<script src="/topbar/script.js?v=' + timestamp + '"><\/script>');
        <script>
            $(document).ready(function() {
        let isDragging = false;
        let startY, startTop;

        function handleDragStart(e) {
            isDragging = true;
            startY = e.clientY;
            startTop = e.target.offsetTop;
        }

        function handleDragMove(e) {
            if (isDragging) {
                const deltaY = e.clientY - startY;
                const newTop = startTop + deltaY;

                e.target.style.top = `${newTop}px`;
            }
        }

        function handleDragEnd() {
            isDragging = false;
        }

        $("tr").on("mousedown", function(e) {
            handleDragStart(e.originalEvent);
            $(document).on("mousemove", handleDragMove);
            $(document).on("mouseup", function() {
                $(document).off("mousemove", handleDragMove);
                $(document).off("mouseup", handleDragEnd);
            });
        });
    });
    </script>
    
    <div class="main-tickets">
        <div id="topbarContainer"></div>
        <div id="sidebarContainer"></div>   

            <table>
                <tr>
                    <th>ID</th>
                    <th>Erstellt am</th>
                    <th>Ersteller</th>
                    <th>Anliegen</th>
                    <th>Priorität</th>
                    <th>Zugeteilt an</th>
                    <th>Aktionen</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['created']}</td>";
                    echo "<td>{$row['creator']}</td>";
                    echo "<td>{$row['subject']}</td>";
                    echo "<td>{$row['priority']}</td>";
                    echo "<td>{$row['assigned_to']}</td>";
                    echo "<td>";
                    echo "<button onclick=\"performAction('delete', {$row['id']})\">Delete</button>";
                    echo "<button onclick=\"reassignTicket({$row['id']})\">Reassign</button>";
                    echo "<button onclick=\"changePriority({$row['id']})\">Change Priority</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
    </div>

    <?php
        mysqli_close($conn);
    ?>

    <script>
        function performAction(action, ticketId, param = null) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    console.log("Response:", xhr.responseText);

                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.success);
                            location.reload();
                        } else if (response.error) {
                            alert(response.error);
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        alert("Error performing action");
                    }
                }
            };

            var url = `tickets.php?action=${action}&id=${ticketId}${param ? `&${param}` : ''}`;
            xhr.open("GET", url, true);
            xhr.send();
        }
        
        function reassignTicket(ticketId) {
            var newAssignee = prompt("Enter the username to reassign the ticket:");
            if (newAssignee !== null) {
                performAction('reassign', ticketId, `assignee=${encodeURIComponent(newAssignee)}`);
            }
        }

        function changePriority(ticketId) {
            var newPriority = prompt("Choose the new priority:\nNiedrig, Normal, Hoch", "Niedrig");
            if (newPriority !== null) {
                newPriority = newPriority.charAt(0).toUpperCase() + newPriority.slice(1).toLowerCase();
                performAction('change_priority', ticketId, `priority=${encodeURIComponent(newPriority)}`);
            }
        }

    </script>
</body>
</html>
