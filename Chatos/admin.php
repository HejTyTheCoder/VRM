<?php
session_start();
require_once "inc/classes/chatgroup.inc.php";
require_once "inc/classes/database.inc.php";
require_once "inc/classes/invitation.inc.php";
require_once "inc/classes/message.inc.php";
require_once "inc/classes/user.inc.php";
require_once "inc/classes/database.inc.php";
$database = new Database();

if (isset($_SESSION["username"])) {
    $result = $database->getUser($_SESSION["username"]);
    $user = new User($result["idu"], $result["nickname"], $result["authority"]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        table caption {
            font-weight: bold;
            margin-bottom: 10px;
        }

        form {
            width: 300px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        window.onload = function() {
            // Define the list of commands
            const commands = [
                "getusermessages",
                "deleteuserdescription",
                "getchatgroupmessages",
                "deleteinvite",
                "deletemessage",
                "deleteuser",
                "getmessages",
                "createuser",
                "changeauthority",
                "getuser",
                "getinvites",
                "setpassword",
                "getchatgroup",
                "getchatgroups",
                "deletechatgroup",
                "changechatgroupdescription",
                "createchatgroup",
                "addusertogroup",
                "deleteuserfromgroup"
            ];

            // Get the command input field
            const commandInput = document.getElementById("command");

            // Create a datalist element and set its ID
            const dataList = document.createElement("datalist");
            dataList.id = "commandList";

            // Create option elements for each command and append them to the datalist
            commands.forEach((command) => {
                const option = document.createElement("option");
                option.value = command;
                dataList.appendChild(option);
            });

            // Append the datalist to the document
            document.body.appendChild(dataList);

            // Set the datalist attribute for the command input field
            commandInput.setAttribute("list", "commandList");

        };
    </script>
</head>

<body>
    <form method="post" action="#">
        <label for="nickname">Username: </label>
        <input type="text" name="nickname" id="nickname" value="<?php if (isset($_POST['nickname'])) {
                                                                    echo ($_POST['nickname']);
                                                                } ?>">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" value="<?php if (isset($_POST['password'])) {
                                                                        echo ($_POST['password']);
                                                                    } ?>">
        <br>
        <label for="command">Command: </label>
        <input type="text" name="command" id="command" value="<?php if (isset($_POST['command'])) {
                                                                    echo ($_POST['command']);
                                                                } ?>">
        <br>
        <label for="val1">Variable: </label>
        <input type="text" name="val1" id="val1" value="<?php if (isset($_POST['val1'])) {
                                                            echo ($_POST['val1']);
                                                        } ?>">
        <br>
        <label for="val2">Variable: </label>
        <input type="text" name="val2" id="val2" value="<?php if (isset($_POST['val2'])) {
                                                            echo ($_POST['val2']);
                                                        } ?>">
        <button type="submit">Odeslat</button>
    </form>
    <?php
    if (isset($_POST['command'])) {
        $adminN = $_POST['nickname'];
        $adminP = $_POST['password'];
        $command = strtolower($_POST['command']);
        $val1 = $_POST['val1'];
        $val2 = $_POST['val2'];
        $results = array();
        $singleLine = false;
        switch ($command) {
            case 'getusermessages':
                $results = $database->admin_getUserMessages($adminN, $adminP, $val1, $val2);
                break;
            case 'deleteuserdescription':
                try {
                    $database->admin_deleteUserDescription($adminN, $adminP, $val1);
                    $results = "Deleted successfully";
                    $singleLine = true;
                } catch (Exception $e) {
                    $singleLine = true;
                    $results = "Delete Error: " . $e->getMessage();
                }
                break;
            case 'getchatgroupmessages':
                $results = $database->admin_getChatGroupMessages($adminN, $adminP, $val1, $val2);
                break;
            case 'deleteinvite':
                try {
                    $database->admin_deleteInvite($adminN, $adminP, $val1);
                    $results = "Deleted successfully";
                    $singleLine = true;
                } catch (Exception $e) {
                    $results = "Delete Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'deletemessage':
                try {
                    $database->admin_deleteMessage($adminN, $adminP, $val1);
                    $results = "Deleted successfully";
                    $singleLine = true;
                } catch (Exception $e) {
                    $results = "Delete Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'deleteuser':
                try {
                    $database->admin_deleteUser($adminN, $adminP, $val1);
                    $results = "Deleted successfully";
                    $singleLine = true;
                } catch (Exception $e) {
                    $results = "Delete Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'getmessages':
                $results = $database->admin_getMessages($adminN, $adminP, $val1);
                break;
            case 'createuser':
                try {
                    $database->admin_createUser($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "User created successfully";
                } catch (Exception $e) {
                    $results = "Error creating user: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'changeauthority':
                try {
                    $database->admin_changeAuthority($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "User authority changed successfully";
                } catch (Exception $e) {
                    $results = "Error changing user authority: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'getuser':
                $results = $database->admin_getUser($adminN, $adminP, $val1);
                break;
            case 'getinvites':
                $results = $database->admin_getInvites($adminN, $adminP, $val1);
                break;
            case 'setpassword':
                try{
                    $database->admin_resetUserPassword($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "Password changed successfully";
                }
                catch(Exception $e){
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'getchatgroup':
                $results = $database->admin_getChatGroup($adminN, $adminP, $val1);
                break;
            case 'getchatgroups':
                $results = $database->admin_getChatGroups($adminN, $adminP, $val1);
                break;
            case 'deletechatgroup':
                try {
                    $database->admin_deleteChatGroup($adminN, $adminP, $val1);
                    $singleLine = true;
                    $results = "Chat group deleted successfully";
                } catch (Exception $e) {
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'changechatgroupdescription':
                try {
                    $database->admin_changeDescription($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "Description changed successfully";
                } catch (Exception $e) {
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }                
                break;
            case 'createchatgroup':
                try {
                    $database->admin_createChatGroup($adminN, $adminP, $val1);
                    $singleLine = true;
                    $results = "Chat group created successfully";
                } catch (Exception $e) {
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'addusertogroup':
                try {
                    $database->admin_addUserToChatGroup($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "User added to group successfully";
                } catch (Exception $e) {
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            case 'deleteuserfromgroup':
                try {
                    $database->admin_deleteUserFromChatGroup($adminN, $adminP, $val1, $val2);
                    $singleLine = true;
                    $results = "User removed from group successfully";
                } catch (Exception $e) {
                    $results = "Error: " . $e->getMessage();
                    $singleLine = true;
                }
                break;
            default:
                $results = "Not a valid command";
                $singleLine = true;
                break;
        }
        if ($singleLine) {
            echo ("<p>" . $results . "</p>");
        } else {
            echo ("<table>");
            $first = true;
            foreach ($results as $row) {
                echo ("<tr>");
                if ($first) {
                    $even = false;
                    $first = false;
                    foreach ($row as $key => $cell) {
                        if ($even) {
                            $even = !$even;
                            continue;
                        }
                        $even = !$even;
                        echo ("<th>" . $key . "</th>");
                    }
                    echo ("</tr><tr>");
                }
                $even = false;
                foreach ($row as $cell) {
                    if ($even) {
                        $even = !$even;
                        continue;
                    }
                    $even = !$even;
                    echo ("<td>" . $cell . "</td>");
                }
                echo ("</tr>");
            }
            echo ("</table>");
        }
    }
    ?>
</body>

</html>