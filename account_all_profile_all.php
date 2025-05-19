<?php include('header.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    cursor: pointer;
}


body {
    font-family: Arial, sans-serif;
    background-color: lightgray;
    color: black;
    padding: 50px;
}


.AccountContainer {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.Profile {
    margin-bottom: 20px;
    padding: 20px;
    
    background-color: white;
}

.AccountContainer h1 {
text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.Profile .ProfilePic {
    margin-top: 30px;
    width: 100px;
    height: 100px;
    margin-left: 40%;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 30px;
}

.Profile .ProfilePic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.Profile a.s {
    float: right;
    font-size: 13px;
    color: dodgerblue;
    text-decoration: none;
     margin-right: 10px;
}

.Profile a.s:hover {
    text-decoration: underline;
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
 border: solid 1.5px lightgrey;
 border-radius: 1rem;
 background: none;
 padding: 10px;
 font-size: 1rem;
 width: 100%;
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
    </style>
</head>

    <script>
     
    function goBack() {
            window.history.back();
        }
    </script>
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

$sambungan->close();
?>
    <body>
<form  method="post">
    <title>Account </title>
        <div class="AccountContainer">
            <button type='bu' onclick='event.preventDefault(); customBack();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1>Account </h1>
    <?php
    if ($result ->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($Status == 'admin'){
            echo "<div class='Profile'>";
            echo " <a class = 's' href='a_update_password'>Change Password</a>"   ;
            echo " <a class = 's' href='a_edit_account'>Edit</a>"   ;
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>";   
                
            echo "<div class='users'><label class='users1'> ID    :</label><div class='form'><input class='input' value='". $row["AdminID"]."' readonly></div><br></div>";
                
            echo "<div class='users'><label class='users1'> Name    :</label><div class='form'><input class='input' value='". $row["Fullname"]."' readonly></div><br></div>";
            echo "<div class='users'><label class='users1'> Email    :</label><div class='form'><input class='input' value='". $row["Email"]."' readonly></div><br></div>";  
            echo "<div class='users'><label class='users1'> Phone Number    :</label><div class='form'><input class='input' value='". $row["Phone_Number"]."' readonly></div><br></div>";
            }
            
            else if($Status == 'user'){
            echo "<div class='Profile'>";
            echo " <a class = 's' href='a_update_password'>Change Password</a>"   ;
            echo " <a class = 's' href='a_edit_account'>Edit</a>"   ;
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>";
            echo "<div class='users'><label class='users1'> ID    :</label><div class='form'><input class='input' value='". $row["UserID"]."' readonly></div><br></div>";
            echo "<div class='users'><label class='users1'> Name    :</label><div class='form'><input class='input' value='". $row["Fullname"]."' readonly></div><br></div>";
            echo "<div class='users'><label class='users1'> Address    :</label><div class='form'><input class='input' value='". $row["Address"]."' readonly></div><br></div>";  
            echo "<div class='users'><label class='users1'> Email    :</label><div class='form'><input class='input' value='". $row["Email"]."' readonly></div><br></div>";
            echo "<div class='users'><label class='users1'> Phone Number    :</label><div class='form'><input class='input' value='". $row["Phone_Number"]."' readonly></div><br></div>";               
            echo "<div class='users'><label class='users1'> Company Name    :</label><div class='form'><input class='input' value='". $row["Company_Name"]."' readonly></div><br></div>"; 
            }
            else if($Status == 'manager'){
            echo "<div class='Profile'>";
            echo " <a class = 's' href='a_update_password'>Change Password</a>"   ;
            echo " <a class = 's' href='a_edit_account'>Edit</a>"   ;
            echo "<div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>";
            
            echo "<div class='users'><label class='users1'> ID    :</label><div class='form'><input class='input' value='". $row["ManagerID"]."' readonly></div><br></div>";
                
            echo "<div class='users'><label class='users1'> Name    :</label><div class='form'><input class='input' value='". $row["Fullname"]."' readonly></div><br></div>";
            echo "<div class='users'><label class='users1'> Email    :</label><div class='form'><input class='input' value='". $row["Email"]."' readonly></div><br></div>";  
            echo "<div class='users'><label class='users1'> Phone Number    :</label><div class='form'><input class='input' value='". $row["Phone_Number"]."' readonly></div><br></div>";
        }
        }
    }
    else {
        echo "0 results";
    }
    ?>
    </div>
</form>
    </body>
</html>

<script>
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