<?php
require_once('bootstrap.php');
include('sambung.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure that user session data is set
if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/al_login");
    exit();
}

$ID = $_SESSION['ID']; 
$Name = $_SESSION['Name'];
$Status = $_SESSION['Status'];

$selected_status = $Status;

// Query based on status
if ($selected_status == 'admin') {
    $This = "SELECT pa.*, p.Product_Name, p.UserID, p.ProductID, b.Block_Name, IF(pa.Outbound_date IS NULL, 0, 1) AS has_outbound_date
        FROM pallet pa 
        JOIN product p ON pa.ProductID = p.ProductID 
        JOIN users u ON p.UserID = u.UserID
        JOIN block b ON b.BlockID = pa.BlockID";      
} elseif ($selected_status == 'user') {
    $This = "SELECT pa.*, p.Product_Name, p.UserID, p.ProductID, b.Block_Name, IF(pa.Outbound_date IS NULL, 0, 1) AS has_outbound_date
        FROM pallet pa 
        JOIN product p ON pa.ProductID = p.ProductID 
        JOIN users u ON p.UserID = u.UserID
        JOIN block b ON b.BlockID = pa.BlockID
        WHERE u.UserID = ?";
}

$stmt = $sambungan->prepare($This);
if ($selected_status == 'user') {
    $stmt->bind_param("s", $ID);
}
$stmt->execute();
$result = $stmt->get_result();

// Handle POST request for AJAX SKU storage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sku'])) {
        $_SESSION['sku'] = $_POST['sku'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'SKU not set']);
    }
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Warehouse</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="stylez.css">
    <style>
            button[name = "pay"]{
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
            button[name = "pay"]:hover{
                background-color: palegreen;
                box-shadow: 0.15em 0.15em black;
}
    
    </style>
</head>
    <script>
        function request5566(sku, s2, product, id) {
            if (s2 == 1) {
                Swal.fire("Warning", "The product has been requested for outbound!", "warning");
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to request outbound.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, request it!',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        OutboundRequest88(sku, product, id);
                    }
                });
            }
        }

        function OutboundRequest88(Sku, product, id) {
            $.ajax({
                url: 'http://localhost/warehouse/inoutbound_user.php',
                method: 'GET',
                data: {
                    pallet: Sku,
                    prod: product,
                    block: id
                },
                success: function(response) {
                    success90();
                },
                error: function() {
                    Swal.fire('Error', 'There was an issue processing your request.', 'error');
                }
            });
        }

        function success90() {
            Swal.fire({
                title: "Successful!",
                text: "Request Outbound Successfully",
                icon: "success",
                showConfirmButton: true 
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        function paymentzzz(sku, s2, product) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to do payment.',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an AJAX call to store SKU in session
                    $.ajax({
                        type: 'POST',
                        url: 'inoutbound_user.php',
                        data: { sku: sku },
                        success: function(response) {
                            // Redirect to the new page after storing SKU in session
                            window.location.href = "new2.php";
                        },
                        error: function() {
                            Swal.fire('Error', 'There was an issue processing your request.', 'error');
                        }
                    });
                }
            });
        }
    </script>
<body>
        <form method="get">
    <main class="table" id="customers_table" style="margin-top : 90px;">
        <section class="table__header">
             <style>

        button[type='bu'] {
            background-color: rgba(0,0,0,0);
            border: none;
            cursor: pointer;
            }

            </style>
            <button type='bu' onclick='event.preventDefault(); customBack125();'><img src='back.png' style='width: 25px; height: 25px;'></button>
            
            <h1 style="font-size:1.9rem;">Request Outbound</h1>
            <div class="input-group" style="margin-right:270px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>

        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <?php
                        if ($selected_status == 'admin') { ?>
                            <th> Sku </th>
                            <th> User ID </th>
                            <th> Product  </th>
                            <th> Block </th>
                            <th> Inbound Date </th>
                            <th> Outbound Date </th>
                            <th> Outbound Request </th>
                        <?php } 
                        elseif ($selected_status == 'user') { ?>
                            <th> Sku </th>
                            <th> Product  </th>
                            <th> Block </th>
                            <th> Inbound Date </th>
                            <th> Outbound Date </th>
                            <th> Outbound Request </th>   
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                 <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sku = $row["Sku"];
                            $flag = 0;

                            if ($row["Outbound_date"] != NULL) {
                                $flagQuery = "SELECT Flag FROM payment WHERE Sku = ?";
                                $flagStmt = $sambungan->prepare($flagQuery);
                                $flagStmt->bind_param("s", $sku);
                                $flagStmt->execute();
                                $flagResult = $flagStmt->get_result();
                                $flagRow = $flagResult->fetch_assoc();
                                $flag = $flagRow['Flag'] ?? 0;  
                                if ($flag == 1){
                                    continue;
                                }
                                echo "</tr>";
                            }

                           
                            if ($selected_status == 'admin') {
                                ${"value1_" . $sku} = $row["ProductID"];
                                ${"value2_" . $sku} = "No";
                                echo "<td>" . $sku . "</td>";
                                echo "<td>" . $row["UserID"] . "</td>";
                                echo "<td>" . $row["Product_Name"] . "</td>";
                                echo "<td>" . $row["Block_Name"] . "</td>";
                                echo "<td>" . $row["Inbound_date"] . "</td>";
                                if ($row["Outbound_date"] == NULL) {
                                    echo "<td>N/A</td>";
                                } else {
                                    echo "<td>" . $row["Outbound_date"] . "</td>";
                                }

                                $s1 = $row["Sku"];
                                $s2 = 0;
                                $This1 = "SELECT Action FROM task WHERE Sku = ?";
                                $stmt1 = $sambungan->prepare($This1);
                                $stmt1->bind_param("s", $s1);
                                $stmt1->execute();
                                $result1 = $stmt1->get_result();

                                if ($result1->num_rows > 0) {
                                    while ($row1 = $result1->fetch_assoc()) {
                                        if ($row1["Action"] == "Retrieve" && $s2 == 0) {
                                            echo "<td> Yes </td>";
                                            ${"value2_" . $row["Sku"]} = "Yes";
                                            $s2 = 1;
                                        }
                                    }
                                    if ($s2 == 0) {
                                        echo "<td> No </td>";
                                    }
                                } else {
                                    echo "<td> No </td>";
                                }
                                if ($flag == 0) {
                                    echo "<td><button type='button' onclick='confirmOutbound88(" . $row["Sku"] . ")'>Request OutBound</button></td>";
                                } else {
                                    echo "<td>Payment/Outbound Not Available</td>";
                                }
                            } elseif ($selected_status == 'user') {
                                ${"value1_" . $sku} = $row["ProductID"];
                                ${"value2_" . $sku} = "No";
                                echo "<td>" . $sku . "</td>";
                                echo "<td>" . $row["Product_Name"] . "</td>";
                                echo "<td>" . $row["Block_Name"] . "</td>";
                                echo "<td>" . $row["Inbound_date"] . "</td>";
                                if ($row["Outbound_date"] == NULL) {
                                    echo "<td>N/A</td>";
                                } else {
                                    echo "<td>" . $row["Outbound_date"] . "</td>";
                                }

                                $s1 = $row["Sku"];
                                $s2 = 0;
                                $This1 = "SELECT Action FROM task WHERE Sku = ?";
                                $stmt1 = $sambungan->prepare($This1);
                                $stmt1->bind_param("s", $s1);
                                $stmt1->execute();
                                $result1 = $stmt1->get_result();

                                if ($result1->num_rows > 0) {
                                    while ($row1 = $result1->fetch_assoc()) {
                                        if ($row1["Action"] == "Retrieve" && $s2 == 0) {
                                            ${"value2_" . $row["Sku"]} = "Yes";
                                            echo "<td><img src='tick1.png' alt='Yes'></td>";
                                            $s2 = 1;
                                        }
                                    }
                                    if ($s2 == 0) {
                                        echo "<td><img src='wrong1.png' alt='No'></td>";
                                    }
                                } else {
                                    echo "<td><img src='wrong1.png' alt='No'></td>";
                                }
                                
                                if ($row["Outbound_date"] == NULL) {
                                    echo "<td><button type='button' name= 'pay' onclick='event.preventDefault(); request5566(\"" . $row["Sku"] . "\", \"" . $s2 . "\", \"" . $row["ProductID"] . "\", \"" . $row["BlockID"] . "\");'>OutBound </button></td>";
                                } elseif ($flag == 0) {
                                    echo "<td><button type='button' name ='pay' onclick='event.preventDefault(); paymentzzz(\"" . $row["Sku"] . "\", \"" . $s2 . "\", \"" . $row["ProductID"] . "\");'>Payment </button></td>";
                                } else {
                                    echo "<td>Payment/Outbound Not Available</td>";
                                }
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>0 results</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

            </main>
    </form>
</body>

</html>
<?php
 include('header.php');
?>
<script>
       const search = document.querySelector('#search'),
            table_rows = document.querySelectorAll('tbody tr');

        search.addEventListener('input', searchTable);

        function searchTable() {
            const search_data = search.value.toLowerCase();

            table_rows.forEach((row, i) => {
                const sku_data = row.querySelector('td:first-child').textContent.toLowerCase();
                const product_name_data = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const isMatch = sku_data.includes(search_data) || product_name_data.includes(search_data);

                row.classList.toggle('hide', !isMatch);
                row.style.setProperty('--delay', i / 25 + 's');
            });

            document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
                visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
            });
        }
        function customBack125() {
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
function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }
</script>
<?php 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["pallet"]) && isset($_GET["prod"]) && isset($_GET["block"])) {
        $ID = $_GET["pallet"];
        $value1 = $_GET["prod"];
        $value2 = $_GET["block"];
        $update = "INSERT INTO task (Action, ProductID, Date, Time, Lane, Sku) VALUES ('Retrieve', ?, CURRENT_DATE, CURRENT_TIME, ?, ?)";
        $binding = $sambungan->prepare($update);
        $binding->bind_param("sss", $value1, $value2, $ID);
        if ($binding->execute()) {
            exit();
        } else {
            echo "<script>alert('Requested For OutBound Unsuccessful')</script>";
        }

        $binding->close();
        $stmt->close();
        $sambungan->close();
    }
}
?>