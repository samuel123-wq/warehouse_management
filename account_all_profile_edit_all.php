<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    <title>Smart Warehouse</title>

    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
        button[type='bu'] {
    background-color: white;
    border: none;
            cursor: pointer;}

body {
    font-family: Arial, sans-serif;
    background-color: lightgray;
    color: black;
}


.AccountContainer {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 80px;
}


.Profile {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 8px;
    background-color: white;
}

.AccountContainer h1 {
text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.Profile .ProfilePic {
    width: 100px;
    height: 100px;
    margin-left: 40%;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 20px;
}

.Profile .ProfilePic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.Profile a.s {
    float: right;
    font-size: 13px;
    color: #007bff;
    text-decoration: none;
}

.Profile a.s:hover {
    text-decoration: underline;
}


        

button[type="submit"] {
    background-color: #007bff;
    margin-left: 9%;
    color: #fff;
    padding: 10px 20px;
    border: none;
    width:80%;
    border-radius: 4px;
    cursor: pointer;
    align-content: center;
    text-align: center;
    margin-top: 30px;
}
        button[type="submits"] {
    background-color: #007bff;
    margin-left: -1%;
    color: #fff;
    padding: 10px 20px;
    border: none;
    width:80%;
    border-radius: 4px;
    cursor: pointer;
    align-content: center;
    text-align: center;
    margin-top: 30px;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.button-container {
    text-align: center;
}

         .Profile td {
     padding: 2px;
     vertical-align: middle;
 }
        
.users1{
background-color: white;
position: absolute;
 pointer-events: none;
 transform: translateY(1rem);
 transform: translateY(-50%) scale(0.8);
 padding: 0 .2em;
 color:grey;
    
            
        }

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

.input:focus, input:valid {
 outline: none;
 border: 1.5px solid lightgrey;
}

.input:focus ~ label, input:valid ~ label {
 transform: translateY(-50%) scale(0.8);
 background-color: #212121;
 padding: 0 .2em;
 color: #2196f3;
}

        .input:focus{
            border: 1.5px solid blue;
        }
    </style>

</head>

<?php
    require_once('bootstrap.php');
include('sambung.php');
$Status = $_SESSION['Status'];
$ids =  $_SESSION['ID'];
$Name = $_SESSION['Name'];
$boo = 0;
if ($Status == 'admin') {
    $check_query = "SELECT * FROM admin WHERE AdminID = '$ids'";
    $result = mysqli_query($sambungan, $check_query);
    $boo = 1;
} elseif ($Status == 'user') {
    $check_query = "SELECT * FROM users WHERE UserID= '$ids'";
    $result = mysqli_query($sambungan, $check_query);
    $boo = 2;
}
elseif ($Status == 'manager') {
    $check_query = "SELECT * FROM manager WHERE ManagerID= '$ids'";
    $result = mysqli_query($sambungan, $check_query);
    $boo = 3;
}   
else{
    $boo = 0;
}


?>
    <script>
function goBack() {
            window.history.back();
        }
        
function success111(status) {
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


function edit2(userId, status, f, e, p) {
    if (status == 'admin' || status == 'manager') {
        var inputName = 'value2' + userId;
        var inputName1 = 'value3' + userId;
        var inputName2 = 'value4' + userId;

        var fullname = document.getElementsByName(inputName)[0].value.toUpperCase();
        var email = document.getElementsByName(inputName1)[0].value;
        var phone = document.getElementsByName(inputName2)[0].value;

        let m = true;
        var message = "";
        if(f == fullname && e == email && p == phone){
            Swal.fire("Warning", "Nothing changed!", "warning");
        }
        else{
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
            message += "Please dont leave it blank.<br>";
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
                    editAccount111(userId, status);
                }
            });
        } else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }
    }
}

  
  function edit555(userId, status, name, add, em, ph, com){
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
        if(fullname == name && add == address && em == email && ph == phone && com == company){
            Swal.fire("Warning", "Nothing changed!", "warning");
        }
        
        else{
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
        if(!(fullname != "" && address != "" && email != "" && phone != "" && company != "")){
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
                    editAccount111(userId, status);
                }
            });
        } else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }
    }
}

   
function editAccount111(userId, status) {
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
                url: 'http://localhost/warehouse/account_all_profile_edit_all.php?',
                    method: 'POST',
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
                    success111(status);     
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
                url: 'http://localhost/warehouse/account_all_profile_edit_all.php?',
                    method: 'POST',
                data: {
                        status: status,
                        ['edit' + status]: userId,
                        editfullname: fullname,
                        editemail: email,
                        editphone: phone,
                        },
                success: function(response) {
                    success111(status);     
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
                url: 'http://localhost/warehouse/account_all_profile_edit_all.php?',
                    method: 'POST',
                data: {
                        status: status,
                        ['edit' + status]: userId,
                        editfullname: fullname,
                        editemail: email,
                        editphone: phone,
                        },
                success: function(response) {
                    success111(status);     
                    }
                });
    }
    
}
        

        function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];
            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }


        function customBack() {
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
    <body style=" background-color: lightgray;">
<form  method="post">
    <title>Account </title>
        <div class="AccountContainer">
            <button type='bu' onclick='event.preventDefault(); customBack();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1>Account </h1>
    <?php
    if ($result ->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($Status == 'admin'){
         
            ${"value1_" . $row["AdminID"]} = $row["Fullname"];
            ${"value2_" . $row["AdminID"]} = $row["Email"];
            ${"value3_" . $row["AdminID"]} = $row["Phone_Number"];            
            echo "<div class='Profile'>";
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>";     
            echo "<table><tr><td>
            <label class ='users1' for='value1_" . $row["AdminID"] . "'>   ID : </label>
            <div class='form'><input class='input' type='text' name='value1" . $row["AdminID"] . "' value='" . $row["AdminID"] . "' readonly></div></td></tr>";
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["AdminID"] . "'>Name : </label><div class='form'><input class='input' type='text' name='value2" . $row["AdminID"] . "' value='" . $row["Fullname"] . "'></div></td></tr>";
            echo "<tr><td>           
            <label class ='users1' for='value1_" . $row["AdminID"] . "'>Email : </label><div class='form'><input class='input' type='text' name='value3" . $row["AdminID"] . "' value='" . $row["Email"] . "'></div></td></tr>";
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["AdminID"] . "'>Phone Number : </label><div class='form'><input class='input' type='text' name='value4" . $row["AdminID"] . "' value='" . $row["Phone_Number"] . "'></div></td></tr>";           
            //echo "</table><div class='button-container'><button type='submits' name='editadmin' value='" . $row['AdminID'] . "'>Edit</button></div>";
                echo "</table><div class='button-container'></table><button type='submits' name='editadmin' onclick='event.preventDefault(); edit2(\"" . $row["AdminID"] . "\", \"" . $Status . "\", \"" . ${"value1_" . $row["AdminID"]} . "\", \"" . ${"value2_" . $row["AdminID"]} . "\", \"" . ${"value3_" . $row["AdminID"]} . "\");'>Edit</button></div>";

            }
            
            elseif($Status == 'user'){
            ${"value1_" . $row["UserID"]} = $row["Fullname"];
            ${"value2_" . $row["UserID"]} = $row["Address"];
            ${"value3_" . $row["UserID"]} = $row["Email"];
            ${"value4_" . $row["UserID"]} = $row["Phone_Number"];
            ${"value5_" . $row["UserID"]} = $row["Company_Name"];           
            echo "<div class='Profile'>";
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>";     
            echo "<table><tr><td><label class ='users1' for='value1_" . $row["UserID"] . "'>   ID : </label>
            <div class='form'><input class='input'  name='value1" . $row["UserID"] . "' value='" . $row["UserID"] . "' readonly></div></td></tr>";
           
            echo "<td><tr><td>            
            <label class ='users1' for='value1_" . $row["UserID"] . "'>Name : </label><div class='form'><input class='input' name='value2" . $row["UserID"] . "' value='" . $row["Fullname"] . "'></div></td></tr>";
           
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["UserID"] . "'>Address : </label><div class='form'><input class='input'  name='value3" . $row["UserID"] . "' value='" . $row["Address"] . "'></div></td></tr>";
                
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["UserID"] . "'>Email : </label><div class='form'><input class='input'  name='value4" . $row["UserID"] . "' value='" . $row["Email"] . "'></div></td></tr>";  
                
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["UserID"] . "'>Phone Number: </label><div class='form'><input class='input'  name='value5" . $row["UserID"] . "' value='" . $row["Phone_Number"] . "'></div></td></tr>";
                
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["UserID"] . "'>Company Name : </label><div class='form'><input class='input'  name='value6" . $row["UserID"] . "' value='" . $row["Company_Name"] . "'></div></td></tr></table>";
                
            //echo "<td><button type='submit' name='edituser' value='" .$row["UserID"]. "'>Edit</button></td>";
           
                //echo "<td><button type='submit' name='editmanager' onclick='event.preventDefault(); edit2(\"/" . $row["UserID"] . "/\");'>Edit</button></td>";
                echo "<td><button type='submit' name='edituser' onclick='event.preventDefault(); edit555(\"" . $row["UserID"] . "\", \"" . $Status . "\", \"" . ${"value1_" . $row["UserID"]} . "\", \"" . ${"value2_" . $row["UserID"]} . "\", \"" . ${"value3_" . $row["UserID"]} . "\", \"" . ${"value4_" . $row["UserID"]} . "\", \"" . ${"value5_" . $row["UserID"]} . "\");'>Edit</button></td>";
            }
            if($Status == 'manager'){
            ${"value1_" . $row["ManagerID"]} = $row["Fullname"];
            ${"value2_" . $row["ManagerID"]} = $row["Email"];
            ${"value3_" . $row["ManagerID"]} = $row["Phone_Number"];
            echo "<div class='Profile'>";
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>"; 
            echo "<table><tr><td>            
            <label class ='users1' for='value1" . $row["ManagerID"] . "'>ID : </label><div class='form'><input class='input' type='text' name='value1"  . $row["ManagerID"] .  "' value='" . $row["ManagerID"] . "' readonly></div></td></tr>";
            echo "<tr><td>            
            <label class ='users1' for='value2" . $row["ManagerID"] . "'>Name : </label><div class='form'><input class='input' type='text' name='value2"  . $row["ManagerID"] .  "' value='" . $row["Fullname"] . "'></div></td></tr>";
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["ManagerID"] . "'>Email : </label><div class='form'><input class='input' type='text' name='value3"  . $row["ManagerID"] .  "' value='" . $row["Email"] . "'></div></td></tr>";
            echo "<tr><td>            
            <label class ='users1' for='value1_" . $row["ManagerID"] . "'>Phone Number : </label><div class='form'><input class='input' type='text' name='value4"  . $row["ManagerID"] .  "' value='" . $row["Phone_Number"] . "'></div></td></tr></table>";           
            //echo "<td><button type='submit' name='editmanager' value='" .$row["ManagerID"]. "'>Edit</button></td>";
               //echo "<td><button type='submit' name='editmanager' onclick='event.preventDefault(); edit2(\"/" . $row["ManagerID"] . "/\");'>Edit</button></td>";
                echo "<td><button type='submit' name='editmanager' onclick='event.preventDefault(); edit2(\"" . $row["ManagerID"] . "\", \"" . $Status . "\", \"" . ${"value1_" . $row["ManagerID"]} . "\", \"" . ${"value2_" . $row["ManagerID"]} . "\", \"" . ${"value3_" . $row["ManagerID"]} . "\");'>Edit</button></td>";

                //<td><button type='submit' onclick='event.preventDefault(); change();'>Change Password</button></td>
            }
        }
    } else {
        echo "0 results";
    }
    ?>
    </div>
</form>
    </body>
</html>
<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){ 
    if(isset($_POST["editadmin"])) {
        $userID = $_POST["editadmin"];
        $value1 = $_POST["editfullname"];
        $value2 = $_POST["editemail"];
        $value3 = $_POST["editphone"];
        if(${"value1_" . $userID} != $value2 or ${"value2_" . $userID} != $value3 or ${"value3_" . $userID} != $value4){
            $update_query = "UPDATE admin SET Fullname=?, Email=?, Phone_Number=? WHERE AdminID=?";
            $stmt = $sambungan->prepare($update_query);
            $stmt->bind_param("ssss", $value1, $value2, $value3, $userID);
            if ($stmt->execute()) {
                echo "<script>alert('Edit Successful')</script>";
                echo "<script>window.location.reload();</script>";
            } else {
                echo "<script>alert('Edit Unsuccessful')</script>";
            }
            $stmt->close();
        }
    }
elseif(isset($_POST["edituser"])) {
    $userID = $_POST["edituser"];
    $value1 = $_POST["editfullname"];
    $value2 = $_POST["editaddress"];
    $value3 = $_POST['editemail'];
    $value4 = $_POST["editphone"];
    $value5 = $_POST["editcompany"];
    
        $update = "UPDATE users SET Company_Name=?, Fullname=?, Address=?, Phone_Number=?, Email=? WHERE UserID=?";
        $binding = $sambungan->prepare($update);
        $binding->bind_param("ssssss", $value5, $value1, $value2, $value4, $value3, $userID);
        
        if ($binding->execute()) {
            echo "<script>alert('Edit Successful')</script>";
            echo "<script>window.location.reload();</script>";
            echo "<script>window.location='http://localhost/warehouse/al_profile'</script>";
        } else {
            echo "<script>alert('Edit Unsuccessful')</script>";
        }
        $binding->close();
        $sambungan->close();
    }

elseif(isset($_POST["editmanager"])) {
    $userID = $_POST["editmanager"];
    $value1 = $_POST["editfullname"];
    $value2 = $_POST["editemail"];
    $value3 = $_POST["editphone"];
    
    if(${"value1_" . $userID} != $value2 or ${"value2_" . $userID} != $value3 or ${"value3_" . $userID} != $value4){
        $update = "UPDATE manager SET Fullname=?, Email=?, Phone_Number=? WHERE ManagerID=?";
        $binding = $sambungan->prepare($update);
        $binding->bind_param("ssss", $value1, $value2, $value3, $userID);
        
        if ($binding->execute()) {
            echo "<script>alert('Edit Successful')</script>";
            echo "<script>window.location.reload();</script>";
        } else {
            echo "<script>alert('Edit Unsuccessful')</script>";
        }
        $binding->close();
        $sambungan->close();
    }
}

}
include('header.php');
?>