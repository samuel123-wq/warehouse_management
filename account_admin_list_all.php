<?php
require_once('bootstrap.php');
include('sambung.php');
include('header.php');

if(!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

if($_SESSION['Status'] != 'admin'){
    echo "<script>window.location.href = 'http://localhost/warehouse/main';</script>";
}

$id = $_SESSION['ID'];
$Name = $_SESSION['Name'];

if(isset($_GET['status'])) {
    $selected_status = $_GET['status'];
} else {
    $selected_status = 'user';
}

$boo = 0;
$result = null;

if ($selected_status == 'admin') {
    $This = "SELECT * FROM admin where Active = '1'";
    $stmt = $sambungan->prepare($This);
    $stmt->execute();
    $result = $stmt->get_result();
    $boo = 1;
} elseif ($selected_status == 'user') {
    $This = "SELECT * FROM users where Active = '1'";
    $stmt = $sambungan->prepare($This);
    $stmt->execute();
    $result = $stmt->get_result();
    $boo = 2;
} elseif ($selected_status == 'manager') {
    $This = "SELECT * FROM manager where Active = '1'";
    $stmt = $sambungan->prepare($This);
    $stmt->execute();
    $result = $stmt->get_result();
    $boo = 3;
} else {
    $boo = 0;
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
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        
function delete1(userId, status){
 Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this account.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
 
            deleteAccount(userId, status);
        }
    });
        }
        
        
function deleteAccount(userId, status) {
    $.ajax({
    url: 'http://localhost/warehouse/account_admin_list_all.php?',
    method: 'GET',
    data: {
        status: status,
        ['delete' + status]: userId
    },
    success: function(response) {

        success(status);
        
    }
});
}
  
function edit1(userId, status){
 Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to edit this account.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {

            editAccount(userId, status);
        }
    });
        }
   
function editAccount(userId, status) {
    $.ajax({
    url: 'http://localhost/warehouse/account_admin_list_all.php?',
    method: 'GET',
    data: {
        status: status,
        ['edit' + status]: userId
    },
    success: function(response) {

        success1(status);
        
    }
});
}
        
function success1(status) {
    Swal.fire({
        title: "Successful!",
        text: "Edit Account Successfully",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            //window.location.href = 'http://localhost/warehouse/al_update_email?status='+status;
            location.reload();
        }
    });
}

        
function success(status) {
    Swal.fire({
        title: "Successful!",
        text: "Delete Account Successfully",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            //window.location.href = 'http://localhost/warehouse/al_update_email?status='+status;
            location.reload();
        }
    });
}

    
function edit2(userId, status) {
    if (status == 'user') {
        var inputName = 'value2' + userId;
        var inputName1 = 'value3' + userId;
        var inputName2 = 'value4' + userId;
        var inputName3 = 'value5' + userId;
        var inputName4 = 'value6' + userId;

        var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
        var address = document.getElementsByName(inputName1)[0].value.toUpperCase();
        var email = document.getElementsByName(inputName2)[0].value;
        var phone = document.getElementsByName(inputName3)[0].value;
        var company = document.getElementsByName(inputName4)[0].value.toUpperCase();
        let m = true;
        var message = "";
        if (!/^[a-zA-Z ]+$/.test(fullname.trim())) {
            if(fullname == ""){m = false;}
            else{
            message += "Fullname must contain only alphabetic characters.<br>";
            var fullname1 = document.getElementsByName(inputName)[0];
            fullname1.style.borderColor = "red";
            m = false;}
        }
        if (!/^\S+@\S+\.\S+$/.test(email)) {
            if(email ==""){m=false;}
            else{
            message += "Email is not in a valid format.\<br>";
            document.getElementsByName(inputName2)[0].style.borderColor = "red";
            m = false;}
        }
        if(!/^\d{11}$/.test(phone)){
            if(phone == ""){m=false;}
            else{
            message += "Please insert correct phone number.<br>";
            document.getElementsByName(inputName3)[0].style.borderColor = "red";
            m = false;}
        }
        if(fullname == "" || address == "" || email == "" || phone == "" || company == ""){
            message += "Please dont leave it blank.\<br>";
            m = false;
            if(fullname == ""){
                var fullname1 = document.getElementsByName(inputName)[0];
                fullname1.style.borderColor = "red";
            }
            if(address == ""){
                document.getElementsByName(inputName1)[0].style.borderColor = "red";
            }
            if(email == ""){
                document.getElementsByName(inputName2)[0].style.borderColor = "red";
            }
            if(phone == ""){
                document.getElementsByName(inputName3)[0].style.borderColor = "red";
            }
            if(company == ""){
                document.getElementsByName(inputName4)[0].style.borderColor = "red";
            }
        }

        if (m == true) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to edit this account.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    editAccount1(userId, status);
                }
            });
        } else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }
    
    
    
    else if (status == 'admin' || status == 'manager') {
        var inputName = 'value2' + userId;
        var inputName1 = 'value3' + userId;
        var inputName2 = 'value4' + userId;

        var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
        var email = document.getElementsByName(inputName1)[0].value;
        var phone = document.getElementsByName(inputName2)[0].value;

        let m = true;
        var message = "";
        if (!/^[a-zA-Z ]+$/.test(fullname.trim())) {
            if(fullname == ""){m = false;}
            else{
            message += "Fullname must contain only alphabetic characters.<br>";
            var fullname1 = document.getElementsByName(inputName)[0];
            fullname1.style.borderColor = "red";
            m = false;}
        }
        if (!/^\S+@\S+\.\S+$/.test(email)) {
            if(email == ""){m=false;}
            else{
            message += "Email is not in a valid format.\<br>";
            document.getElementsByName(inputName1)[0].style.borderColor = "red";
            m = false;}
        }
        if(!/^\d{11}$/.test(phone)){
            if(phone == ""){m=false;}
            else{
            message += "Please insert correct phone number.<br>";
            document.getElementsByName(inputName2)[0].style.borderColor = "red";
            m = false;}
        }
        if(fullname == "" || email == "" || phone == ""){
            message += "Please dont leave it blank.\<br>";
            m = false;
            if(fullname == ""){
                var fullname1 = document.getElementsByName(inputName)[0];
                fullname1.style.borderColor = "red";
            }
            if(email == ""){
                document.getElementsByName(inputName1)[0].style.borderColor = "red";
            }
            if(phone == ""){
                document.getElementsByName(inputName2)[0].style.borderColor = "red";
            }
        }

        if (m == true) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to edit this account.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    editAccount1(userId, status);
                }
            });
        } else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }
    
}

   
function editAccount1(userId, status) {
    if(status == 'user'){
            var inputName = 'value2' + userId;
            var inputName1 = 'value3' + userId;
            var inputName2 = 'value4' + userId;
            var inputName3 = 'value5' + userId;
            var inputName4 = 'value6' + userId;
         
            var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
            var address = document.getElementsByName(inputName1)[0].value.toUpperCase();
            var email = document.getElementsByName(inputName2)[0].value;
            var phone = document.getElementsByName(inputName3)[0].value;
            var company = document.getElementsByName(inputName4)[0].value.toUpperCase();
            //alert(fullname+address+email+phone+company);
        
            $.ajax({
                url: 'http://localhost/warehouse/account_admin_list_all.php?',
                    method: 'GET',
                data: {
                        status: status,
                        ['edit' + status]: userId,
                        editfullname: fullname,
                        editaddress: address,
                        editemail: email,
                        editphone: phone,
                        editcompany: company
                        },
                success: function(response) {
                    success1(status);     
                    }
                });
    }
    else if(status == 'admin'){
            var inputName = 'value2' + userId;
            var inputName1 = 'value3' + userId;
            var inputName2 = 'value4' + userId;
            var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
            var email = document.getElementsByName(inputName1)[0].value;
            var phone = document.getElementsByName(inputName2)[0].value;
            $.ajax({
                url: 'http://localhost/warehouse/account_admin_list_all.php?',
                    method: 'GET',
                data: {
                        status: status,
                        ['edit' + status]: userId,
                        editfullname: fullname,
                        editemail: email,
                        editphone: phone,
                        },
                success: function(response) {
                    success1(status);     
                    }
                });
    }
    else if(status == 'manager'){
            var inputName = 'value2' + userId;
            var inputName1 = 'value3' + userId;
            var inputName2 = 'value4' + userId;
            var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
            var email = document.getElementsByName(inputName1)[0].value;
            var phone = document.getElementsByName(inputName2)[0].value;
            $.ajax({
                url: 'http://localhost/warehouse/account_admin_list_all.php?',
                    method: 'GET',
                data: {
                        status: status,
                        ['edit' + status]: userId,
                        editfullname: fullname,
                        editemail: email,
                        editphone: phone,
                        },
                success: function(response) {
                    success1(status);     
                    }
                });
    }
    
}
        
var fullnameInput = document.getElementsByName("value2U8")[0];
fullnameInput.addEventListener("input", function() {
    var fullnameValue = fullnameInput.value;
    if (!/^[a-zA-Z]+$/.test(fullnameValue)) {
        Swal.fire("Invalid Input", "Fullname must contain only alphabetic characters", "warning");
    }
});

        
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
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

    <link rel="stylesheet" type="text/css" href="stylez.css">

</head>
    <script>
    document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});
    </script>
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
                width: 90%;
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
        
        button[type='bu'] {
        background-color: rgba(0, 0, 0, 0);
        border: none;
        cursor: pointer;
        
    }
    </style>
<body>
        <form method="get">
    <main class="table" id="customers_table" style="margin-top : 90px;">
        <section class="table__header">
            <button type='bu' onclick='event.preventDefault(); customBack6();'><img src='back.png' style='width: 25px; height: 25px;'></button>
            <h1>Manage Account</h1>
            <div class="input-group" style="margin-right:270px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>
                <div class="export__file">
            <label for="status">Type:</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="user" <?php if ($selected_status == 'user') echo 'selected'; ?>>User</option>
                <option value="manager" <?php if ($selected_status == 'manager') echo 'selected'; ?>>Manager</option>
                <option value="admin" <?php if ($selected_status == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
                </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                    <?php

                    if ($selected_status == 'admin') {
                        echo "<th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th></th><th></th>";
                    } elseif ($selected_status == 'user') {
                        echo "<th>ID</th><th>Name</th><th>Address</th><th>Email</th><th>Phone Number</th><th>Company Name</th><th></th><th></th>";
                    } elseif ($selected_status == 'manager') {
                        echo "<th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th></th><th></th>";
                    }
                    ?>
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
        if ($selected_status == 'admin') {
            if($id != $row["AdminID"]){
            ${"value1_" . $row["AdminID"]} = $row["Fullname"];
            ${"value2_" . $row["AdminID"]} = $row["Email"];
            ${"value3_" . $row["AdminID"]} = $row["Phone_Number"];
            
            echo "<td>" . $row["AdminID"] . "</td>";
            echo "<td><input type='text' name='value2"  . $row["AdminID"] .  "' value='" . $row["Fullname"] . "'></td>";
            echo "<td><input type='text' name='value3"  . $row["AdminID"] .  "' value='" . $row["Email"] . "'></td>";
            echo "<td><input type='text' name='value4"  . $row["AdminID"] .  "' value='" . $row["Phone_Number"] . "'></td>";           
            //echo "<td><button type='submit' name='editadmin' value='" .$row["AdminID"]. "'>Edit</button></td>";
            //echo "<td><button type='submit' name='deleteadmin' value='" .$row["AdminID"]. "'>Delete</button></td>";
            
            //echo "<td><button onclick='if(!confirm(\"Are you sure you want to edit this account?\")){window.location.reload();return false;}' type='submit' name='editadmin' value='" .$row["AdminID"]. "'>Edit</button></td>";

            //echo "<td><button onclick='return confirm(\"Are you sure you want to delete this account?\")' type='submit' name='deleteadmin' value='" .$row["AdminID"]. "'>Delete</button></td>";
            
             echo "<td><button name = 'edit' onclick='event.preventDefault(); edit2(\"" . $row["AdminID"] . "\", \"" . $selected_status . "\");'>Edit</button></td>";
            
            echo "<td><button name = 'delete' onclick='event.preventDefault(); delete1(\"" . $row["AdminID"] . "\", \"" . $selected_status . "\");'>Delete</button></td>";
            }
        } elseif ($selected_status == 'user') {
            ${"value1_" . $row["UserID"]} = $row["Fullname"];
            ${"value2_" . $row["UserID"]} = $row["Address"];
            ${"value3_" . $row["UserID"]} = $row["Email"];
            ${"value4_" . $row["UserID"]} = $row["Phone_Number"];
            ${"value5_" . $row["UserID"]} = $row["Company_Name"];
            echo "<td>" . $row["UserID"] . "</td>";
            echo "<td><input type='text' name='value2" . $row["UserID"] . "' value='" . $row["Fullname"] . "'></td>";
            echo "<td><input type='text' name='value3" . $row["UserID"] . "' value='" . $row["Address"] . "'></td>";
            echo "<td><input type='text' name='value4" . $row["UserID"] . "' value='" . $row["Email"] . "'></td>";           
            echo "<td><input type='text' name='value5" . $row["UserID"] . "' value='" . $row["Phone_Number"] . "'></td>";
            echo "<td><input type='text' name='value6" . $row["UserID"] . "' value='" . $row["Company_Name"] . "'></td>";
            //echo "<td><button type='submit' name='edituser' value='" .$row["UserID"]. "'>Edit</button></td>";
            //echo "<td><button type='submit' name='deleteuser' value='" .$row["UserID"]. "'>Delete</button></td>";
            
            //echo "<td><button onclick='if(!confirm(\"Are you sure you want to edit this account?\")){window.location.reload();return false;}' type='submit' name='edituser' value='" .$row["UserID"]. "'>Edit</button></td>";

            //echo "<td><button onclick='return confirm(\"Are you sure you want to delete this account?\")' type='submit' name='deleteuser' value='" .$row["UserID"]. "'>Delete</button></td>";
            
            //echo "<td><button onclick='event.preventDefault(); edit1(\"" . $row["UserID"] . "\", \"" . $selected_status . "\", \"" . $selected_status . "\", \"" . $selected_status . "\", \"" . $selected_status . "\", \"" . $selected_status . "\", \"" . $selected_status . "\", \"" . $selected_status . "\",\"" . $selected_status . "\");'>Edit Account</button></td>";
            
           // echo '<td><button onclick="event.preventDefault(); edit2(\'' . $row["UserID"] . '\'';
            //echo ', document.getElementsByName(\'value2' . $row["UserID"] . '\')[0].value';
            //echo ', document.getElementsByName(\'value3' . $row["UserID"] . '\')[0].value';
            //echo ', document.getElementsByName(\'value4' . $row["UserID"] . '\')[0].value';
            //echo ', document.getElementsByName(\'value5' . $row["UserID"] . '\')[0].value';
            //echo ', document.getElementsByName(\'value6' . $row["UserID"] . '\')[0].value);">Edit Account</button></td>';

            echo "<td><button name = 'edit1' onclick='event.preventDefault(); edit2(\"" . $row["UserID"] . "\", \"" . $selected_status . "\");'>Edit</button></td>";
            
            //echo "<td><button name = 'delete1' onclick='event.preventDefault(); delete1(\"" . $row["UserID"] . "\", \"" . $selected_status . "\");'>Delete</button></td>";
            
            echo "<td><button name = 'deletes' onclick='event.preventDefault(); delete1(\"" . $row["UserID"] . "\", \"" . $selected_status . "\");'>Delete</button></td>";
            
                
        } elseif ($selected_status == 'manager') {        
            
            ${"value1_" . $row["ManagerID"]} = $row["Fullname"];
            ${"value2_" . $row["ManagerID"]} = $row["Email"];
            ${"value3_" . $row["ManagerID"]} = $row["Phone_Number"];
            
            echo "<td>" . $row["ManagerID"] . "</td>";
            echo "<td><input type='text' name='value2"  . $row["ManagerID"] .  "' value='" . $row["Fullname"] . "'></td>";
            echo "<td><input type='text' name='value3"  . $row["ManagerID"] .  "' value='" . $row["Email"] . "'></td>";
            echo "<td><input type='text' name='value4"  . $row["ManagerID"] .  "' value='" . $row["Phone_Number"] . "'></td>";           
            //echo "<td><button type='submit' name='editmanager' value='" .$row["ManagerID"]. "'>Edit</button></td>";
            //echo "<td><button type='submit' name='deletemanager' value='" .$row["ManagerID"]. "'>Delete</button></td>";
            
            //echo "<td><button onclick='if(!confirm(\"Are you sure you want to edit this account?\")){window.location.reload();return false;}' type='submit' name='editmanager' value='" .$row["ManagerID"]. "'>Edit</button></td>";

            //echo "<td><button onclick='return confirm(\"Are you sure you want to delete this account?\")' type='submit' name='deletemanager' value='" .$row["ManagerID"]. "'>Delete</button></td>";
            
            echo "<td><button name=edit onclick='event.preventDefault(); edit2(\"" . $row["ManagerID"] . "\", \"" . $selected_status . "\");'>Edit</button></td>";
            
            echo "<td><button name=delete onclick='event.preventDefault(); delete1(\"" . $row["ManagerID"] . "\", \"" . $selected_status . "\");'>Delete</button></td>";
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

    </form>
</body>

</html>
<?php 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["deleteuser"])){
        $update = "UPDATE users SET Active = '0' WHERE UserID=?";
            $binding = $sambungan->prepare($update);
            
            $binding->bind_param("s", $_GET["deleteuser"]);
            
            if ($binding->execute()) {
                //echo "<script>alert('Delete Successful')</script>";
                //echo "<script>window.location.href = 'http://localhost/warehouse/a_list_account?status=user';</script>";
                exit();
            } else {
                echo "<script>alert('Delete Unsuccessful')</script>";
            }
            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    
    elseif(isset($_GET["deleteadmin"])){
            $update = "UPDATE admin SET Active = '0' WHERE AdminID=?";
            $binding = $sambungan->prepare($update);
            $binding->bind_param("s", $_GET["deleteadmin"]);
            
            if ($binding->execute()) {
                //echo "<script>alert('Delete Successful')</script>";
                //echo "<script>window.location.href = 'http://localhost/warehouse/a_list_account?status=admin';</script>";
                exit();
            } else {
                echo "<script>alert('Delete Unsuccessful')</script>";
            }
            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    elseif(isset($_GET["deletemanager"])){
        $update = "UPDATE manager SET Active = '0' WHERE ManagerID=?";
            $binding = $sambungan->prepare($update);
            
            
            $binding->bind_param("s", $_GET["deletemanager"]);
            
            if ($binding->execute()) {
                //echo "<script>alert('Delete Successful')</script>";
                //echo "<script>window.location.href = 'http://localhost/warehouse/a_list_account?status=manager';</script>";
                exit();
            } else {
                echo "<script>alert('Delete Unsuccessful')</script>";
            }
            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    
    elseif(isset($_GET["edituser"])) {
        $userID = $_GET["edituser"];
        $value1 = $_GET["editfullname"];
        $value2 = $_GET["editaddress"];
        $value3 = $_GET["editemail"];
        $value4 = $_GET["editphone"];
        $value5 = $_GET["editcompany"];

        $update = "UPDATE users SET Company_Name=?, Fullname=?, Address=?, Phone_Number=?, Email=? WHERE UserID=?";
        $binding = $sambungan->prepare($update);

 
        $binding->bind_param("ssssss", $value5, $value1, $value2, $value4, $value3, $userID);

        if ($binding->execute()) {
            exit();
        } else {
            echo "<script>alert('Edit Unsuccessful')</script>";
        }
        $binding->close();
        $stmt->close();
        $sambungan->close();
}

    elseif(isset($_GET["editadmin"])) {
        $userID = $_GET["editadmin"];
        $value1 = $_GET["editfullname"];
        $value2 = $_GET["editemail"];
        $value3 = $_GET["editphone"];
            $update = "UPDATE admin SET Fullname=?, Email=?, Phone_Number=? WHERE AdminID=?";
            $binding = $sambungan->prepare($update);
            $binding->bind_param("ssss", $value1, $value2, $value3, $userID);
            
            if ($binding->execute()) {
                exit();
            } else {
                echo "<script>alert('Edit Unsuccessful')</script>";
            }

            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    elseif(isset($_GET["editmanager"])) {
        $userID = $_GET["editmanager"];
        $value1 = $_GET["editfullname"];
        $value2 = $_GET["editemail"];
        $value3 = $_GET["editphone"];
        
            $update = "UPDATE manager SET Fullname=?, Email=?, Phone_Number=? WHERE ManagerID=?";
            $binding = $sambungan->prepare($update);
            $binding->bind_param("ssss", $value1, $value2, $value3, $userID);
            
            if ($binding->execute()) {
                exit();
            } else {
                echo "<script>alert('Edit Unsuccessful')</script>";
            }
            $binding->close();
            $stmt->close();
            $sambungan->close();
    }
    elseif(isset($_GET["delete"])) {
        $userID_to_delete = $_GET["delete"];
        

    }
}
?>
<script>
        const search = document.querySelector('#search'),
            table_rows = document.querySelectorAll('tbody tr');

        search.addEventListener('input', searchTable);

        function searchTable() {
            const search_data = search.value.toLowerCase();

            table_rows.forEach((row, i) => {
                const sku_data = row.querySelector('td:first-child').textContent.toLowerCase();
                const isMatch = sku_data.includes(search_data);

                row.classList.toggle('hide', !isMatch);
                row.style.setProperty('--delay', i / 25 + 's');
            });

            document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
                visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
            });
        }
</script>

