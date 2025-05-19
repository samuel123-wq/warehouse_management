<?php
require_once('bootstrap.php');
include('sambung.php');
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status']) || $_SESSION['Status'] != 'admin') {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

if($_SESSION['Status'] == 'user'){
    echo "<script>window.location.href = 'http://localhost/warehouse/main';</script>";
}

$id = $_SESSION['ID'];
$Name = $_SESSION['Name'];


$selected_status = isset($_GET['status']) ? $_GET['status'] : 'Pending';

if ($selected_status == 'Pending') {
    $query = "SELECT * FROM users WHERE Active = '2'";
} elseif ($selected_status == 'Active') {
    $query = "SELECT * FROM users WHERE Active = '1'";
} elseif ($selected_status == 'Reject') {
    $query = "SELECT * FROM users WHERE Active = '3'";
} else {
    $query = "SELECT * FROM users";
}

$stmt = $sambungan->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Warehouse</title>

    <style>
  
        input[type="text"] {
            width: 100%;
            padding: 5px;
            font-size: 12px;
            box-sizing: border-box;
            border: 1px solid transparent;
            border-bottom: 1px solid black;
            border-radius: 0px;
            background-color: transparent;
            color: black;
        }
        input[type="text"]:focus {
            border: none;
            border-bottom: 2px solid blue;
            outline: none; 
        }
        select {
            margin-bottom: 10px;
        }
        button {
            font-family: sans-serif;
            font-size: 14px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 6px;
        }
        button[name="edit1"], button[name="delete"] {
            width: 100%;
            border-radius: 6px;
        }
        button[name="edit1"] {
            background-color: green;
            border-radius: 6px;
        }
        button[name="delete"] {
            background-color: red;
            border-radius: 6px;
        }
        button:hover {
            background-color: palegreen;
            box-shadow: 0.15em 0.15em black;
            border-radius: 6px;
        }
        button[name="delete"]:hover {
            background-color: palevioletred;
            box-shadow: 0.15em 0.15em #5566c2;
            
        }
        button[type='bu'] {
            background-color: rgba(0, 0, 0, 0);
            border: none;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="stylez.css">
</head>
<body>
    <form method="get">
        <main class="table" id="customers_table" style="margin-top: 90px;">
            <section class="table__header">
                <button type='bu' onclick='event.preventDefault(); customBack6();'><img src='back.png' style='width: 25px; height: 25px;'></button>
                <h1>Approve Account</h1>
                <div class="input-group" style="margin-right: 270px;">
                    <input id="search" type="search" placeholder="Search Data..." style="padding-top: 5px;">
                </div>
                <div class="export__file">
                    <label for="status">Status:</label>
                    <select id="status" name="status" onchange="this.form.submit()">
                        <option value="Active" <?php if ($selected_status == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Pending" <?php if ($selected_status == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Reject" <?php if ($selected_status == 'Reject') echo 'selected'; ?>>Rejected</option>
                    </select>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Company Name</th>
                            <th>Address</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["UserID"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Fullname"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Phone_Number"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Company_Name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Address"]) . "</td>";

                                if ($selected_status == 'Pending') {
                                    echo "<td><button name='edit1' onclick='event.preventDefault(); manageAccount(\"" . htmlspecialchars($row["UserID"]) . "\", true);'>Approve</button></td>";
                                    echo "<td><button name='delete' onclick='event.preventDefault(); manageAccount(\"" . htmlspecialchars($row["UserID"]) . "\", false);'>Reject</button></td>";
                                } elseif ($selected_status == 'Active') {
                                    echo "<td><button name='delete' onclick='event.preventDefault(); manageAccount(\"" . htmlspecialchars($row["UserID"]) . "\", false);'>Reject</button></td>";
                                } elseif ($selected_status == 'Reject') {
                                    echo "<td><button name='edit1' onclick='event.preventDefault(); manageAccount(\"" . htmlspecialchars($row["UserID"]) . "\", true);'>Approve</button></td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No results found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </form>

    <script>
        function manageAccount(userId, isApproved) {
            const actionText = isApproved ? 'approve' : 'reject';
            const confirmationMessage = `You are about to ${actionText} this account.`;
            const confirmButtonText = isApproved ? 'Yes, approve it!' : 'Yes, reject it!';

            Swal.fire({
                title: 'Are you sure?',
                text: confirmationMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    updateAccountStatus(userId, isApproved);
                }
            });
        }

        function updateAccountStatus(userId, isApproved) {
            const status = isApproved ? 1 : 3;

            $.ajax({
                url: 'http://localhost/warehouse/account_admin_access.php',
                method: 'GET',
                data: {
                    status: status,
                    approveuser: userId
                },
                success: function(response) {
                    Swal.fire({
                        title: "Successful!",
                        text: `Account ${isApproved ? 'approved' : 'rejected'} successfully`,
                        icon: "success",
                        showConfirmButton: true 
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: `Failed to ${isApproved ? 'approve' : 'reject'} the account.`,
                        icon: "error",
                        showConfirmButton: true 
                    });
                }
            });
        }

        function customBack6() {
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];
            if (pageHistory.length > 1) {
                const currentPage = pageHistory.pop();
                let previousPage = pageHistory.pop();
                while (getBaseUrl(currentPage) === getBaseUrl(previousPage) && pageHistory.length > 0) {
                    previousPage = pageHistory.pop();
                }
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
                if (getBaseUrl(currentPage) !== getBaseUrl(previousPage)) {
                    window.location.href = previousPage;
                } else if (pageHistory.length > 0) {
                    window.location.href = pageHistory.pop();
                } else {
                    window.history.back();
                }
            } else {
                window.history.back();
            }
        }

        function getBaseUrl(url) {
            return url.split('?')[0];
        }

        const search = document.querySelector('#search'),
            table_rows = document.querySelectorAll('tbody tr');

        search.addEventListener('input', searchTable);

        function searchTable() {
            const search_data = search.value.toLowerCase();
            table_rows.forEach((row, i) => {
                const row_data = Array.from(row.querySelectorAll('td')).map(td => td.textContent.toLowerCase());
                const isMatch = row_data.some(data => data.includes(search_data));
                row.classList.toggle('hide', !isMatch);
                row.style.setProperty('--delay', i / 25 + 's');
            });

            document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
                visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
            });
        }
    </script>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["approveuser"]) && isset($_GET["status"])) {
    $userID = $_GET["approveuser"];
    $status = (int)$_GET["status"]; 

    $update = "UPDATE users SET Active = ? WHERE UserID = ?";
    $se = "SELECT Email FROM users WHERE UserID = ?";
    
    $binding = $sambungan->prepare($update);
    $binding->bind_param("is", $status, $userID);
    
    $bin = $sambungan->prepare($se);
    $bin->bind_param("s", $userID);

    if ($binding->execute() && $bin->execute()) {
        $result = $bin->get_result();
        $row = $result->fetch_assoc();
        $email = $row['Email'];

        $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'crsstwarehouse@gmail.com'; 
            $mail->Password = 'pfrfgjjveyrbqeyx'; 
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('crsstwarehouse@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $mail->Subject = 'Account';
            $mail->Body = $status == 1 
                ? 'Thanks for registering an account on our website. Your account has been approved. You can now log in with this ID: ' . $userID . '.' 
                : 'Your account registration has been rejected due to incomplete information. Please contact out admin (No: +601130991764 or Email: sylvesteronglide808@gmail.com)';

            $mail->send();
            echo "success";
    } else {
        echo "<script>alert('Approval Unsuccessful');</script>";
    }
    $binding->close();
    $sambungan->close();
}
?>

<?php
include('loading.html');
include('header.php');
?>
