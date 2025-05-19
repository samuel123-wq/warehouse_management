<?php
require_once('bootstrap.php');
include('sambung.php');
include('header.php');

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/index.php?login");
    exit();
}

$ids = $_SESSION['ID'];
$Name = $_SESSION['Name'];
$sta = $_SESSION['Status'];

$selected_status = isset($_GET['status']) ? $_GET['status'] : 'Inbound';

$boo = 0;
$sql = '';

if ($sta == 'admin' || $sta == 'manager') {
    if ($selected_status == 'Inbound') {
        $sql = "SELECT p.*, pa.* FROM product p JOIN pallet pa ON p.ProductID = pa.ProductID WHERE pa.Inbound_date IS NOT NULL"; 
    } elseif ($selected_status == 'Outbound') {
        $sql = "SELECT p.*, pa.* FROM product p JOIN pallet pa ON p.ProductID = pa.ProductID WHERE pa.Outbound_date IS NOT NULL"; 
    }
} elseif ($sta == 'user') {
    if ($selected_status == 'Inbound') {
        $sql = "SELECT p.*, pa.* FROM product p JOIN pallet pa ON p.ProductID = pa.ProductID WHERE p.UserID = ? AND pa.Inbound_date IS NOT NULL"; 
    } elseif ($selected_status == 'Outbound') {
        $sql = "SELECT p.*, pa.* FROM product p JOIN pallet pa ON p.ProductID = pa.ProductID WHERE p.UserID = ? AND pa.Outbound_date IS NOT NULL"; 
    }
}

if ($sql) {
    $statement = $sambungan->prepare($sql);
    if ($sta == 'user') {
        $statement->bind_param("i", $ids);
    }
    $statement->execute();
    $result = $statement->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Warehouse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="stylez.css">

</head>
    <script>
    document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});
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
            
            <h1 style="font-size:1.9rem;">In/outbound History</h1>
            <div class="input-group" style="margin-right:270px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>
                <div class="export__file">
                    <label for="status">Type:</label>
                    <select id="status" name="status" onchange="this.form.submit()" style="background-color: #fff5;">
                        <option value="Inbound" <?php if ($selected_status == 'Inbound') echo 'selected'; ?>>Inbound</option>
                        <option value="Outbound" <?php if ($selected_status == 'Outbound') echo 'selected'; ?>>Outbound</option>
                    </select>
                </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <?php
                        if ($sta == 'user' and $selected_status == "Outbound") { ?>
                            <th> Sku </th>
                            <th> Product Name </th>
                            <th> Weight (KG)</th>

                            <th> Inbound Date </th>
                            <th> Outbound Date </th>
                            <th> Outbound Status </th>
                        <?php } 
                        elseif ($sta == 'user' and $selected_status == "Inbound") { ?>
                            <th> Sku </th>
                            <th> Product Name </th>
                            <th> Weight (KG)</th>

                            <th> Inbound Date </th>
                            <th> Inbound Status </th>     
                        <?php }
                         elseif (($sta == 'admin' or $sta == 'manager') and $selected_status == "Inbound") { ?>
                            <th> Sku </th>
                            <th> User ID </th>
                            <th> Product Name </th>
                            <th> Weight (KG)</th>

                            <th> Inbound Date </th>
                            <th> Inbound Status </th>
                        <?php }
                             elseif(($sta == 'admin' or $sta == 'manager') and $selected_status == "Outbound") { ?>
                            <th> Sku </th>
                            <th> User ID </th>
                            <th> Product Name </th>
                            <th> Weight (KG)</th>

                            <th> Inbound Date </th>
                            <th> Outbound Status </th>
                            <th> Outbound Date </th> 
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
           <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if($sta == 'user' and $selected_status == "Outbound"){
                        echo "<tr>";
                        echo "<td>" . $row["Sku"] . "</td>";
                        echo "<td>" . $row["Product_Name"] . "</td>";
                        echo "<td>" . $row["Pallet_weight"] . "</td>";

                        echo "<td>" . $row["Inbound_date"] . "</td>";
                        echo "<td>" . ($row["Outbound_date"] ? $row["Outbound_date"] : "N/A") . "</td>"; 
                            
                        $s1 = $row["Sku"];
                        $s2 = 0;
                        $This1 = "SELECT Action FROM task where Sku = ?";
                        $stmt1 = $sambungan->prepare($This1);
                        $stmt1->bind_param("s", $s1); 
                        $stmt1->execute();
                        $result1 = $stmt1->get_result(); 

                        if ($result1->num_rows > 0) {
                            while ($row1 = $result1->fetch_assoc()) {
                                if ($row1["Action"] == "Retrieve" and $s2 == 0) {

                                            echo "<td>" . ($row["Outbound_date"] ? "Complete" : "In Progress") . "</td>"; 
                                    $s2 = 1;
                                        }
                                    }
                                    if ($s2 == 0) {
                                                echo "<td> N/A </td>";
                                            }
                                } else {
                            echo "<td> N/A </td>";
                                }
                            
                        }
                        elseif($sta == 'user' and $selected_status == "Inbound"){
                        echo "<tr>";
                        echo "<td>" . $row["Sku"] . "</td>";
                        echo "<td>" . $row["Product_Name"] . "</td>";
                        echo "<td>" . $row["Pallet_weight"] . "</td>";

                        echo "<td>" . ($row["Inbound_date"] ? $row["Inbound_date"] : "N/A") . "</td>"; 
                        echo "<td>" . ($row["Inbound_date"] ? "Complete" : "In Progress") . "</td>"; 
                        }
                        elseif(($sta == 'admin' or $sta == 'manager') and $selected_status == "Inbound"){
                        echo "<tr>";
                        echo "<td>" . $row["Sku"] . "</td>";
                        echo "<td>" . $row["UserID"] . "</td>";
                        echo "<td>" . $row["Pallet_weight"] . "</td>";
                        echo "<td>" . $row["Product_Name"] . "</td>";
                        echo "<td>" . ($row["Inbound_date"] ? $row["Inbound_date"] : "N/A") . "</td>"; 
                        echo "<td>" . ($row["Inbound_date"] ? "Complete" : "In Progress") . "</td>"; 
                            
                        }
                        elseif(($sta == 'admin' or $sta == 'manager') and $selected_status == "Outbound"){
                        echo "<tr>";
                        echo "<td>" . $row["Sku"] . "</td>";
                        echo "<td>" . $row["UserID"] . "</td>";
                        echo "<td>" . $row["Product_Name"] . "</td>";
                        echo "<td>" . $row["Pallet_weight"] . "</td>";

                        echo "<td>" . $row["Inbound_date"] . "</td>";
                        echo "<td>" . ($row["Outbound_date"] ? $row["Outbound_date"] : "N/A") . "</td>"; 
                        $s1 = $row["Sku"];
                        $s2 = 0;
                        $This1 = "SELECT Action FROM task where Sku = ?";
                        $stmt1 = $sambungan->prepare($This1);
                        $stmt1->bind_param("s", $s1); 
                        $stmt1->execute();
                        $result1 = $stmt1->get_result(); 

                        if ($result1->num_rows > 0) {
                            while ($row1 = $result1->fetch_assoc()) {
                                if ($row1["Action"] == "Retrieve" and $s2 == 0) {
                                            echo "<td>" . ($row["Outbound_date"] ? "Complete" : "In Progress") . "</td>"; 
                                    $s2 = 1;
                                        }
                                    }
                                    if ($s2 == 0) {
                                                echo "<td> N/A </td>";
                                            }
                                } else {
                            echo "<td> N/A </td>";
                                }
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>0 results</td></tr>";
                }
                
                
                
                ?>
                </tbody>
            </table>
        </section>
    </main>
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
    </script>
    </form>
</body>

</html>
<?php
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

</script>