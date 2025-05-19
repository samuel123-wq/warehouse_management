<?php
require_once('bootstrap.php');
include('sambung.php');

    session_start();

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}
if($_SESSION['Status'] != 'user'){
    header("Location: http://localhost/warehouse/main");
    exit(); 
}
$ID = $_SESSION['ID']; 
$Name = $_SESSION['Name'];
$Status = $_SESSION['Status'];

$selected_status = $Status;

    $This = "SELECT pa.*, p.Product_Name, p.UserID, p.ProductID, b.Block_Name, IF(pa.Outbound_date IS NULL, 0, 1) AS has_outbound_date
        FROM pallet pa 
        JOIN product p ON pa.ProductID = p.ProductID 
        JOIN users u ON p.UserID = u.UserID
        JOIN block b ON b.BlockID = pa.BlockID
        WHERE pa.Outbound_date IS NOT NULL AND u.UserID = ?";



$stmt = $sambungan->prepare($This);
if ($selected_status == 'user') {
    $stmt->bind_param("s", $ID);
}
$stmt->execute();
$result = $stmt->get_result();

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
    document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});
    </script>
     <script>
        function request(sku, s2, product, id) {
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
                        OutboundRequest(sku, product, id);
                    }
                });
            }
        }

        function OutboundRequest(Sku, product, id) {
            $.ajax({
                url: 'http://localhost/warehouse/u_inoutbound?',
                method: 'GET',
                data: {
                    pallet: Sku,
                    prod: product,
                    block: id
                },
                success: function(response) {
                    success();
                },
                error: function() {
                    Swal.fire('Error', 'There was an issue processing your request.', 'error');
                }
            });
        }

        function success() {
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

        function payment(sku, s2, product) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to do payment.',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
         
                    $.ajax({
                        type: 'POST',
                        url: 'pays',
                        data: { sku: sku },
                        success: function(response) {
                            window.location.href = "card";
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
            <button type='bu' onclick='event.preventDefault(); customBack1();'><img src='back.png' style='width: 25px; height: 25px;'></button>
            <h1 
  style="font-size: 1.9rem;">Payment</h1>
            <div class="input-group" style="margin-right:450px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>

        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>


                            <th> Sku </th>
                            <th> Product  </th>
                            <th> Block </th>
                            <th> Inbound Date </th>
                            <th> Outbound Date </th>
                            <th> Total </th>   
    
                    </tr>
                </thead>
                <tbody>
                     <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sku = $row["Sku"];
                            $flag = 0;

                            if ($row["Outbound_date"] != NULL) {
                                $flagQuery = "SELECT Flag , Totaltax FROM payment WHERE Sku = ?";
                                $flagStmt = $sambungan->prepare($flagQuery);
                                $flagStmt->bind_param("s", $sku);
                                $flagStmt->execute();
                                $flagResult = $flagStmt->get_result();
                                $flagRow = $flagResult->fetch_assoc();
                                $flag = $flagRow['Flag'] ?? 0;  
                                if ($flag == 1){
                                    continue;
                                }
                                else{
                                    $Total = $flagRow['Totaltax'];
                                }
                                echo "</tr>";
                            }


                                ${"value1_" . $sku} = $row["ProductID"];
                                ${"value2_" . $sku} = "No";
                                echo "<td>" . $sku . "</td>";
                                echo "<td>" . $row["Product_Name"] . "</td>";
                                echo "<td>" . $row["Block_Name"] . "</td>";
                                echo "<td>" . $row["Inbound_date"] . "</td>";
     

                                echo "<td>" . $row["Outbound_date"] . "</td>";
                                $kiras = $Total;
                                $kira = number_format($kiras, 2);
                                echo "<td> RM" . $kira . "</td>"; 
                                $s1 = $row["Sku"];
                                $s2 = 0;
                                $This1 = "SELECT Action FROM task WHERE Sku = ?";
                                $stmt1 = $sambungan->prepare($This1);
                                $stmt1->bind_param("s", $s1);
                                $stmt1->execute();
                                $result1 = $stmt1->get_result();
                                if ($row["Outbound_date"] == NULL) {
                                    echo "<td><button type='button' onclick='event.preventDefault(); request(\"" . $row["Sku"] . "\", \"" . $s2 . "\", \"" . $row["ProductID"] . "\", \"" . $row["BlockID"] . "\");'>OutBound <img src='out.png' alt='No' style='margin-left:10px;'></button></td>";
                                } elseif ($flag == 0) {
                                    echo "<td><button type='button' name = 'pay' onclick='event.preventDefault(); payment(\"" . $row["Sku"] . "\", \"" . $s2 . "\", \"" . $row["ProductID"] . "\");'>Payment </button></td>";
                                } else {
                                    echo "<td>Payment Not Available</td>";
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
 include('header.php');
?>
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
    </script>