<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('bootstrap.php');
include('sambung.php');
session_start();

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/al_login");
    exit();
}
if($_SESSION['Status'] != 'user'){
    header("Location: http://localhost/warehouse/main");
    exit();  
}
if (isset($_GET['ajax'])) {
    fetchData($sambungan, $_SESSION['ID']);
    exit();
}

function fetchData($conn, $ids) {
    $check_query = "
        SELECT 
            p.Sku, 
            p.UserID as PaymentUserID, 
            p.Time, 
            p.Date, 
            p.Flag, 
            p.Total, 
            p.Totaltax,
            p.PaymentID, 
            u.UserID, 
            u.Fullname 
        FROM payment p
        JOIN users u ON p.UserID = u.UserID
        WHERE p.Flag = 1 AND p.UserID = '$ids'
    ";

    $result = mysqli_query($conn, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<table>
                <thead>
                    <tr>
                        <th> Payment ID </th>
                        <th> ID </th>
                        <th> Name </th> 
                        <th> Sku </th>
                        <th> Time </th>
                        <th> Date </th>
                        <th> Status </th>
                        <th> Total </th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['PaymentID']}</td>
                    <td>{$row['UserID']}</td>
                    <td>{$row['Fullname']}</td>
                    <td>{$row['Sku']}</td>
                    <td>{$row['Time']}</td>
                    <td>{$row['Date']}</td>
                    <td>" . ($row['Flag'] == 1 ? 'Paid' : 'Unpaid') . "</td>
                    <td>RM {$row['Totaltax']}</td>
                </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<table>
                <thead>
                    <tr>
                        <th> Payment ID </th>
                        <th> ID </th>
                        <th> Name </th> 
                        <th> Sku </th>
                        <th> Time </th>
                        <th> Date </th>
                        <th> Status </th>
                        <th> Total </th>
                    </tr>
                </thead>
                <tbody>';
        echo '<tr><td colspan="8">No results</td></tr>';
    }
}

$sambungan->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Warehouse</title>
    <link rel="stylesheet" type="text/css" href="stylez.css">
    <script type="text/javascript">
        function loadTable() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                if (this.status == 200) {
                    document.querySelector('.table__body').innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "?ajax=1", true);
            xhttp.send();
        }

        setInterval(loadTable, 1000);

        document.addEventListener('DOMContentLoaded', function() {
            loadTable();
        });
    </script>
</head>

<body>
    <main class="table" id="customers_table" style="margin-top: 90px;">
        <section class="table__header">
            <style>
                button[type='bu'] {
                    background-color: rgba(0, 0, 0, 0);
                    border: none;
                    cursor: pointer;
                }
            </style>
            <button type='bu' onclick='event.preventDefault(); customBack1();'>
                <img src='back.png' style='width: 25px; height: 25px;'>
            </button>
            <h1 style="font-size:1.9rem;">Payment History</h1>
            <div class="input-group" style="margin-right:450px;">
                <input id='search' type="search" placeholder="Search Data..." style="padding-top:5px;">
            </div>
        </section>
        <section class="table__body">

        </section>
    </main>
    <script>
        function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }

        function customBack1() {
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
                    sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
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
    </script>
    <script>
        const search = document.querySelector('#search');
        search.addEventListener('input', searchTable);

        function searchTable() {
            const search_data = search.value.toLowerCase();
            const table_rows = document.querySelectorAll('tbody tr');

            table_rows.forEach((row, i) => {
                const payment_id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const user_id = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const isMatch = payment_id.includes(search_data) || user_id.includes(search_data) || name.includes(search_data);

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
include('header.php');
include('loading.html');
?>

