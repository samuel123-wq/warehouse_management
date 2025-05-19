<?php
session_start(); 

include('sambung.php'); 
$currentDate = date('Y-m-d'); 

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

require_once('bootstrap.php'); 

$ID = $_SESSION['ID'];
$Name = $_SESSION['Name'];
$Statuss = $_SESSION['Status'];
$sql = NULL;

if ($Statuss == 'admin' || $Statuss == 'manager') {
    $sql = 'SELECT * FROM product WHERE Active = "1"';
} else if ($Statuss == 'user') {
    $sql = 'SELECT * FROM product WHERE UserID = ? and Active = "1"';
}

$stmt = $sambungan->prepare($sql);
if ($Statuss == 'user') {
    $stmt->bind_param("s", $ID); 
}
$stmt->execute(); 
$result = $stmt->get_result(); 

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    <style>
    input[type="text"] {
    width: 40%;
    height: 20%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 1px solid white;
    border-radius: 4px;
    box-sizing: border-box;
        border-radius: 20px;
    margin-left: 10px;
    background-color: white;
    color: black;
}
        

/* Button styles */
button[type="submit"] {
    background-color: black;
    margin-left: 2%;
    height: 20%;
    margin-bottom:30px;
    color: #fff;
    padding: 10px 20px;
    border: none;
    width:20%;
    border-radius: 4px;
    cursor: pointer;
    align-content: center;
    text-align: center;
    margin-top: 30px;
}

button[type="submit"]:hover {
    background-color: #1b1a1aed;
}
/* Center align the button */
.button-container {
    text-align: center;
}


    
            
        }
/* Additional styling for specific elements */
/* You can add more specific styles if needed */
.input {
 margin-bottom: 20px;
 border: solid 1.5px lightgrey;
 border-radius: 1rem;
 background: none;
 padding: 10px;
 font-size: 1rem;
 width: 230%;
height:20%;
 color: black;
 transition: border 150ms cubic-bezier(0.4,0,0.2,1);
}



        .input:focus{
            border: 1.5px solid blue;
        }
  
    </style>
    <link rel="stylesheet" type="text/css" href="stylez.css">
</head>

<body>
    <form method="post">
        <main class="table" id="customers_table" style="margin-top: 90px;">
            <section class="table__header">
                 <style>

        button[type='bu'] {
            background-color: rgba(0,0,0,0);
            border: none;
            cursor: pointer;
            }
    
            </style>
                <button type='bu' onclick='event.preventDefault(); customBack1();'><img src='back.png' style='width: 25px; height: 25px;'></button>
                <h1 style=" font-size: 1.9rem;">Add Product</h1>
                <div class="input-group" style="margin-right:70px;">
                    <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">
                </div>
                <div class="export__file">
                    <?php if ($Statuss == 'user') { ?>
                        <input style='background-color:#adadac' placeholder="Enter Product Name" class="input-field" type="text" name="Product_Name" required>
                        <button type="submit" onclick='event.preventDefault(); add("user");'>Add</button>
                    <?php } elseif ($Statuss == 'admin' || $Statuss == 'manager') { ?>
                        <input style='background-color:#adadac; width:150px;' placeholder="Enter User ID" class="input-field" type="text" name="userID" required>
                        <input style='background-color:#adadac; width:170px;' placeholder="Enter Product Name" class="input-field" type="text" name="Product_Name" required>
                        <button type="submit" onclick='event.preventDefault(); add("admin");'>Add</button>
                    <?php } ?>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th> Product ID </th>
                            <th> Product Name </th>
                            <?php if ($Statuss == 'admin' || $Statuss == 'manager') { ?>
                                <th> User ID </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["ProductID"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Product_Name"]) . "</td>";
                                if ($Statuss == 'admin' || $Statuss == 'manager') {
                                    echo "<td>" . htmlspecialchars($row["UserID"]) . "</td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </form>
</body>

</html>

<script>
        function add(status) {
            if (status == 'user') {
                var name = document.getElementsByName('Product_Name')[0].value;
                if (name == "") {
                    Swal.fire("Warning", "Don't Leave It Blank!");
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to add a new product.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, add it!',
                        cancelButtonText: 'No, cancel',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            add1(name, status);
                        }
                    });
                }
            } else if (status == 'admin' || status == 'manager') {
                var name = document.getElementsByName('Product_Name')[0].value;
                var id = document.getElementsByName('userID')[0].value;
                if (name == "" || id == "") {
                    Swal.fire("Warning", "Don't Leave It Blank!");
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to add a new product.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, add it!',
                        cancelButtonText: 'No, cancel',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            add22(name, status, id);
                        }
                    });
                }
            }
        }

        function add1(name, status) {
            $.ajax({
                url: 'http://localhost/warehouse/product_add_admin_user.php',
                method: 'POST',
                data: {
                    productname: name,
                    status: status
                },
                success: function(response) {
                    //success2222();
                    var numbers = response.match(/\d+$/);
                    
                    if (numbers) {
                        var lastNumber = numbers[0];
                        console.log(numbers)
                        if (lastNumber == "2") {
                            success2222();
                            
                        } else {
                            success12222();
                        }
                    }
                }
            });
        }

        function add22(name, status, user) {
            $.ajax({
                url: 'http://localhost/warehouse/product_add_admin_user.php',
                method: 'POST',
                data: {
                    productname: name,
                    status: status,
                    userid: user
                },
                success: function(response) {
                    var numbers = response.match(/\d+$/);
                    
                    if (numbers) {
                        var lastNumber = numbers[0];
                        console.log(numbers)
                        if (lastNumber == "2") {
                            success2222();
                        }
                        else if(lastNumber == "10"){
                            success12222();
                        }
                        else {
                           success122();
                        }
                    }
                }
            });
        }

        function success2222() {
            Swal.fire({
                title: "Successful!",
                text: "Product added successfully.",
                icon: "success",
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        function success122() {
            Swal.fire("Warning", "UserID doesn't exist!", "warning");
        }
    
    function success12222() {
            Swal.fire("Warning", "Product already exist!", "warning");
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
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['status'] == 'user') {
        if (!empty($_POST["productname"])) {
    $productname = $_POST['productname'];
    $sql2 = 'SELECT * FROM product WHERE UserID = ? and Product_Name = ? and Active = "1"';
    $stmt2 = $sambungan->prepare($sql2);
    $stmt2->bind_param("ss", $ID, $_POST["productname"]);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
                echo "10";
        exit();
    } 
            else {
            $add = "INSERT INTO product (Product_Name, Date, UserID) VALUES (?, CURRENT_DATE, ?)";
            $stmt = mysqli_prepare($sambungan, $add);
            mysqli_stmt_bind_param($stmt, "ss", $_POST["productname"], $ID);
            if (mysqli_stmt_execute($stmt)) {
                echo "2";
            } 
        } 
        }
    }
    

    elseif ($_POST['status'] == 'admin' || $_POST['status'] == 'manager') {
        if (!empty($_POST["productname"]) && !empty($_POST["userid"])) {
            $ids = $_POST["userid"];
            $productname = $_POST['productname'];
            $sql2 = 'SELECT * FROM product WHERE UserID = ? and Product_Name = ? and Active = "1"';
            $stmt2 = $sambungan->prepare($sql2);
            $stmt2->bind_param("ss", $ids, $productname);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
                echo "10";
        exit();
    } 
            else{
            $thisQuery = "SELECT * FROM users WHERE UserID = ?";
            $stmt = $sambungan->prepare($thisQuery);
            $stmt->bind_param("s", $ids);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $add = "INSERT INTO product (Product_Name, Date, UserID) VALUES (?, CURRENT_DATE, ?)";
                $stmt = mysqli_prepare($sambungan, $add);
                mysqli_stmt_bind_param($stmt, "ss", $_POST["productname"], $_POST["userid"]);
                if (mysqli_stmt_execute($stmt)) {
                    echo "2";
                    exit;
                } 
                
            } 
            }
        } 
    }
    $stmt2->close();
    $sambungan->close();
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
<?php include("header.php"); ?>