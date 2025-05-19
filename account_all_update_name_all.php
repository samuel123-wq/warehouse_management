<?php
require_once('bootstrap.php');
include('sambung.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

$ID = $_SESSION['ID'];
$Name = $_SESSION['Name'];
$Status = $_SESSION['Status'];

if (isset($_GET['ajax'])) {
    fetchData($sambungan, $ID, $Status);
    exit();
}

function fetchData($conn, $ID, $selected_status) {
    $selected_status = $_GET['Status'] ?? 'user';

    if ($selected_status == 'user') {
        $query = "SELECT task.Sku, product.Product_Name, task.Lane, task.Date, task.Forklift_check, task.PS_check, task.AGV_check, task.SC_check, task.TaskID 
                  FROM task 
                  INNER JOIN product ON product.ProductID = task.ProductID 
                  WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $ID);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<table id='taskTable'>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Task ID</th>
                        <th>Product</th>
                        <th>Block</th>
                        <th>Request Inbound Date</th>
                        <th>Completion Percentage</th>
                    </tr>
                </thead>
                <tbody>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $completionPercentage = calculateCompletion($row);
                echo "<tr>
                        <td>" . ($row["Sku"] ?: "N/A") . "</td>
                        <td>" . $row["TaskID"] . "</td>
                        <td>" . $row["Product_Name"] . "</td>
                        <td>" . ($row["Lane"] == 1 ? "A" : "B") . "</td>
                        <td>" . $row["Date"] . "</td>
                        <td>" . $completionPercentage . "%</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }
        echo "</tbody></table>";
    }
}

function calculateCompletion($row) {
    $checks = ['Forklift_check', 'PS_check', 'AGV_check', 'SC_check'];
    $completed = 0;
    foreach ($checks as $check) {
        if ($row[$check] == 1) {
            $completed++;
        }
    }
    return ($completed / count($checks)) * 100;
}
?>

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Table</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="stylez.css">
    <style>
        button[name="pay"] {
            font-family: sans-serif;
            font-size: 14px;
            background-color: green;
            height: 40px;
            width: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 6px;
        }
        button[name="pay"]:hover {
            background-color: palegreen;
            box-shadow: 0.15em 0.15em black;
        }
        .d {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff8;
            font-size: 16px;
            color: #333;
            outline: none;
        }
    </style>
</head>
<body>
    <form method="get">
        <main class="table" id="customers_table" style="margin-top: 90px;">
            <section class="table__header">
                <style>
                    button[type='bu'] {
                        background-color: rgba(0, 0, 0, 0);
                        border: none;
                        cursor: pointer;
                    }
                </style>
                <button type='bu' onclick='event.preventDefault(); customBack123();'><img src='back.png' style='width: 25px; height: 25px;'></button>
                <h1> Request Inbound</h1>
                <div class="input-group" style="margin-right: 450px;">
                    <input id="search" type="search" placeholder="Search Data..." style="padding-top: 5px;">
                </div>
            </section>
            <section class="table__body">
                <div id="ta">
                    <!-- Table will be loaded here -->
                </div>
            </section>
            <div class="ttable">
                <label for="status123" style="color: white;">Select Product to Inbound:</label>
                <select id="status123" name="status123" style="padding: 5px; border: 1px solid #ccc; border-radius: 5px; background-color: #fff8; font-size: 16px; color: #333; outline: none;">
                    <?php
                    $query = "SELECT * FROM product WHERE UserID = ? and Active = '1'";
                    $stmt1 = $sambungan->prepare($query);
                    $stmt1->bind_param("s", $ID);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    if ($result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            echo "<option class='p' value='" . $row1["ProductID"] . "'>" . $row1["Product_Name"] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button class="d" type="button" onclick="event.preventDefault(); Erequest6661();">Request Inbound</button>
            </div>
        </main>
    </form>

    <script>
        function Erequest6661() {
            var selectElement = document.getElementById("status123");
            var selectedValue = selectElement.value;
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to request inbound.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, request it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    InboundRequest6661(selectedValue);
                }
            });
        }

        function InboundRequest6661(product1) {
            $.ajax({
                url: 'http://localhost/warehouse/account_all_update_name_all.php?',
                method: 'GET',
                data: { Prod1: product1 },
                success: function(response) {
                    success666();
                }
            });
        }

        function success666() {
            Swal.fire({
                title: "Successful!",
                text: "Request Inbound Successfully",
                icon: "success",
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        function loadTable() {
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("ta").innerHTML = this.responseText;
                    searchTable(); // Ensure search functionality is applied after table is loaded
                }
            };
            xhttp.open("GET", "?ajax=1", true);
            xhttp.send();
        }

        window.onload = function() {
            loadTable();
            setInterval(loadTable, 1000);
            document.getElementById('search').addEventListener('input', searchTable);
        };
        
function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }


        function customBack123() {
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
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["Prod1"])) {
    $value1 = $_GET["Prod1"];
    $randomNumber = rand(1, 2);
    $update = "INSERT INTO task (Action, ProductID, Date, Time, Lane) VALUES ('Store', ?, CURRENT_DATE, CURRENT_TIME, ?)";
    $stmt = $sambungan->prepare($update);
    $stmt->bind_param("ss", $value1, $randomNumber);
    if ($stmt->execute()) {
        echo "Requested For Inbound successful";
    } else {
        echo "Requested For Inbound Unsuccessful";
    }
    $stmt->close();
    $sambungan->close();
}
?>
