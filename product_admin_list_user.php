<?php
require_once('bootstrap.php');
include('sambung.php');
include('header.php');


if(!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

$ID = $_SESSION['ID']; 
$Name = $_SESSION['Name'];
$Status = $_SESSION['Status'];

if(isset($_GET['Status'])) {
    $selected_status = $_GET['Status'];
} else {
    $selected_status = 'user';
}
$selected_status = $Status;
if ($selected_status == 'admin' or $selected_status == 'manager') {
    $This = "SELECT * FROM product where Active = '1'";
    $stmt = $sambungan->prepare($This);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($selected_status == 'user') {
    $This = "SELECT * FROM product where Userid = '$ID' and Active = '1'";
    $stmt = $sambungan->prepare($This);
    $stmt->execute();
    $result = $stmt->get_result();
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
    input[type="text"] {
    width: 100%;
    padding: 5px;
    font-size: 12px;
    box-sizing: border-box;
    border: 1px solid transparent;
    border-bottom: 1px solid black;
    border-radius: 0px;
    background-color:transparent;
        color:black;
}
            input[type="text"]:focus{
                border: none;
                border-bottom: 2px solid blue;
                outline: none; 
            }
            select{
                margin-bottom:10px;
            }
            
            button[name = "edit"]{
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
            button[name = "delete"]{
                font-family: sans-serif;
                font-size: 14px;
                background-color: red;
                height: 40px;
                width: 70%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border-radius: 6px;
            }
            button[name = "edit1"]{
                font-family: sans-serif;
                font-size: 14px;
                background-color: green;
                height: 40px;
                width: 110%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border-radius: 6px;
            }
            button[name = "edit1"]:hover{
                background-color: palegreen;
                box-shadow: 0.15em 0.15em black;
}
            button[name = "edit"]:hover{
                background-color: palegreen;
                box-shadow: 0.15em 0.15em black;
}
            button[name = "deletes"]{
                font-family: sans-serif;
                font-size: 14px;
                background-color: red;
                height: 40px;
                width: 75%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border-radius: 6px;
            }
            button[name = "deletes"]:hover{
                background-color: palevioletred;
                box-shadow: 0.15em 0.15em #5566c2;
}
            button[name = "delete"]:hover{
                background-color: palevioletred;
                box-shadow: 0.15em 0.15em #5566c2;
}    

h1, .h1 {
  font-size: 1.9rem;
}
    
    </style>
</head>
     <script>
function edit4(productid1, productname1, pp) {
        //alert(pp);
        var inputName = 'value1' + productid1;      
        var name = document.getElementsByName(inputName)[0].value;
        let m = true;
        var message = "";
        
        if(pp != 0){
            message += "You are not allow to change the product name after inbound/outbound product by using this name!<br>";
            var fullname1 = document.getElementsByName(inputName)[0];
            fullname1.style.borderColor = "red";
            m = false;
        }
        if (name == "" && pp == 0) {
            message += "Don't Leave It Blank!<br>";
            var fullname1 = document.getElementsByName(inputName)[0];
            fullname1.style.borderColor = "red";
            m = false;}
        if(name != "" && name == productname1 && pp==0){
           message += "It doesn't changed!<br>";
            var fullname1 = document.getElementsByName(inputName)[0];
            fullname1.style.borderColor = "red";
            m = false; 
        }
    
        if (m == true) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to edit this product.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    editproduct(productid1, name);
                }
            });
        } else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }
        
function editproduct(id, name){
    $.ajax({
                url: 'http://localhost/warehouse/product_admin_list_user.php?',
                    method: 'GET',
                data: {
                        editproduct: id,
                        productname: name
                        },
                success: function(response) {
                    success1();
                    }
                });
}
        
        
function delete4(productid) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this product.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteproduct(productid);
                }
            });

    }
        
function deleteproduct(id){
    $.ajax({
                url: 'http://localhost/warehouse/product_admin_list_user.php?',
                    method: 'GET',
                data: {
                        deleteproduct: id
                        },
                success: function(response) {
                    success();     
                    }
                });}
    
    function success1() {
    Swal.fire({
        title: "Successful!",
        text: "Edit Successfully",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
    function success() {
    Swal.fire({
        title: "Successful!",
        text: "Delete Product Successfully",
        icon: "success",
        showConfirmButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
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

            <h1>Manage Product</h1>
            <div class="input-group" style="margin-right:451px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>

        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                    <?php
                    if ($selected_status == 'admin' or $selected_status == 'manager') {?>
                        <th> User ID </th>
                        <th> Product ID </th>
                        <th> Product Name </th>
                        <th> Added Date </th>
                        <th> </th>
                        <th> </th>
                    <?php } 
                    elseif ($selected_status == 'user') { ?>
                        <th> Product ID </th>
                        <th> Product Name </th>
                        <th> Added Date </th>
                        <th> </th>
                        <th> </th>
                     
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>

<?php
$i = 0;
$j = 0;
$j1 = 1;
$j2 = 2;
$j3 = 3;
$j4 = 4;
$j5 = 5;
$j6 = 6;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        if ($selected_status == 'admin' or $selected_status == 'manager') {
            $pp = $row["ProductID"];
            $This1 = "SELECT * FROM task where ProductID = '$pp'";
            $stmt = $sambungan->prepare($This1);
            $stmt->execute();
            $result1 = $stmt->get_result();
            
            ${"value2_" . $row["ProductID"]} = $row["Product_Name"];

            echo "<td>". $row["UserID"] . "</td>";    
            echo "<td>". $row["ProductID"] ."</td>";
            echo "<td><input type='text' name='value1"  . $row["ProductID"] .  "' value='" . $row["Product_Name"] . "' ></td>";
            echo "<td>". $row["Date"] ."</td>";
            //echo "<td><button type='submit' name='edit' value='" .$row["ProductID"]. "'>Edit</button></td>";
            //echo "<td><button type='submit' name='deletes' value='" .$row["ProductID"]. "'>Delete</button></td>";
            
            echo "<td><button name = 'edit' onclick='event.preventDefault(); edit4(\"" . $row["ProductID"] . "\", \"" . $row["Product_Name"] . "\", \"" . $result1->num_rows . "\");'>Edit</button></td>";
            
            echo "<td><button name = 'deletes' onclick='event.preventDefault(); delete4(\"" . $row["ProductID"] . "\");'>Delete</button></td>";
            
        } elseif ($selected_status == 'user') {
            $pp = $row["ProductID"];
            $This1 = "SELECT * FROM task where ProductID = '$pp'";
            $stmt = $sambungan->prepare($This1);
            $stmt->execute();
            $result1 = $stmt->get_result();
            
            ${"value2_" . $row["ProductID"]} = $row["Product_Name"];
            echo "<td>" . $row["ProductID"] .  "</td>";           
            echo "<td><input type='text' name='value1"  . $row["ProductID"] .  "' value='" . $row["Product_Name"] . "' ></td>";
            echo "<td>". $row["Date"] ."</td>";
            //echo "<td><button type='submit' name='edit' value='" .$row["ProductID"]. "'>Edit</button></td>";
            //echo "<td><button type='submit' name='deletes' value='" .$row["ProductID"]. "'>Delete</button></td>";
            
            echo "<td><button name = 'edit' onclick='event.preventDefault(); edit4(\"" . $row["ProductID"] . "\", \"" . $row["Product_Name"] . "\", \"" . $result1->num_rows . "\");'>Edit</button></td>";
            
            echo "<td><button name = 'deletes' onclick='event.preventDefault(); delete4(\"" . $row["ProductID"] . "\");'>Delete</button></td>";
                
        }
        echo "</tr>";
        $i++;
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
                const product_name_data = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const isMatch = sku_data.includes(search_data) || product_name_data.includes(search_data);


                row.classList.toggle('hide', !isMatch);
                row.style.setProperty('--delay', i / 25 + 's');
            });

            document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
                visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
            });
        }
    </script>
    </form>
</body>

</html>


<?php 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["deleteproduct"])){
        $update = "UPDATE product SET Active = '0' WHERE ProductID=?";
            $binding = $sambungan->prepare($update);
  
            $binding->bind_param("s", $_GET["deleteproduct"]);
            
            if ($binding->execute()) {
               exit();
            } else {
                echo "<script>alert('Delete Product Unsuccessful')</script>";
            }
        
            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    
    
    elseif(isset($_GET["editproduct"])) {
        $userID = $_GET["editproduct"];
        $value2 = $_GET["productname"];
        
            $update = "UPDATE product SET Product_Name = ? WHERE ProductID = ?";
            $binding = $sambungan->prepare($update);
            $binding->bind_param("ss", $value2, $userID);
            
            if ($binding->execute()) {
            } else {
                echo "<script>alert('Update Product Name Unsuccessful')</script>";
            }

            $binding->close();
            $stmt->close();
            $sambungan->close();
    }

    }
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