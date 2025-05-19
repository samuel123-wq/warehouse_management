<?php include('header.php');?>

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
    cursor: pointer;
}

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
    margin-top: 120px;
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
input[type="text"] {
    width: 150%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 1px solid lightgrey;
    border-radius: 4px;
    box-sizing: border-box;
    margin-left: 10px;
    background-color: rgba(255, 255, 255, 0);
    color: black;
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
    $vv = "";
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
    function validatePassword(password) {
            if (password.length < 8) {
                return false;
            }
            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
            return passwordPattern.test(password);
        }
        
    function change(){
        document.getElementsByName('old')[0].style.borderColor = "black";
        document.getElementsByName('new')[0].style.borderColor = "black";
        document.getElementsByName('confirm')[0].style.borderColor = "black";
        var pass = document.getElementsByName('old')[0].value;
        var pass1 = document.getElementsByName('new')[0].value;
        var pass2 = document.getElementsByName('confirm')[0].value;
        if(pass == "" || pass1 == "" || pass2 == ""){
            Swal.fire("Warning", "Please Don't Leave It Blank!", "warning");
            if(pass == ""){
            document.getElementsByName('old')[0].style.borderColor = "red";
            }
            if(pass1 == ""){
                document.getElementsByName('new')[0].style.borderColor = "red";
            }
            if(pass2 == ""){
                document.getElementsByName('confirm')[0].style.borderColor = "red";
            }
        }
        else{
        if(pass1 == pass){
            Swal.fire("Warning", "The New Password and Old Password Is The Same!", "warning");
            document.getElementsByName('new')[0].style.borderColor = "red";
            document.getElementsByName('confirm')[0].style.borderColor = "red";
        }
        else if(pass1 != pass2){
            Swal.fire("Warning", "The New Password and Confirm Password Is Not The Same!", "warning");
            document.getElementsByName('new')[0].style.borderColor = "red";
            document.getElementsByName('confirm')[0].style.borderColor = "red";
        }
        else if(!validatePassword(pass1))
            {
            Swal.fire("Warning", "Password must contain at least one number, one alphabet, one special character and be at least 8 characters long.");
            document.getElementsByName('new')[0].style.borderColor = "red";
            document.getElementsByName('confirm')[0].style.borderColor = "red";
            }
        else{
        Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to change the password.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes,  change it!',
        cancelButtonText: 'No, cancel',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            changepassword(pass, pass1, pass2);
        }
    });
        }
    }
    }
    
        function confirmOutbound(sku) {
            if (confirm('Are you sure you want to change the password?')) {
                window.location.href = 'http://localhost/warehouse/u_inoutbound?editadmin=' + sku;
            }
        }
    
function changepassword(pass, pass1, pass2) {
    $.ajax({
    url: 'http://localhost/warehouse/update_password.php',
    method: 'GET',
    data: {
        pass : pass,
        pass1 : pass1,
        pass2 : pass2 
    },
     success: function(response) {
         console.log(response);
          console.log(response);
          var numbers = response.match(/\d+$/);
         console.log(numbers);
          if (numbers) {
            var lastNumber = numbers[0];
            if(lastNumber == "1"){
                success();
            }
            else{
                success1();
            }
           }
        
     },
        error: function(xhr, status, error) {
            success1();
        }

        
});
}
    
function success() {


    Swal.fire({
        title: "Successful!",
        text: "Change Password Successfully",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}

function success1() {
    document.getElementsByName('old')[0].style.borderColor = "red";
    Swal.fire("Warning", "Old Password Incorrect!", "warning");
}
        function goBack() {
            window.history.back();
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

    
    <body>
<form  method="get">
    <title>Account </title>
        <div class="AccountContainer">
            <button type='bu' onclick='event.preventDefault(); customBack();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1>Account </h1>
            
    <?php
    if ($result ->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($Status == 'admin'){
            $value1 = $row["AdminID"];
            $value2 = $row["Password"];     

            }  
            elseif($Status == 'user'){
            $value1 = $row["UserID"];
            $value2 = $row["Password"]; 
                
            }
            if($Status == 'manager'){
            $value1 = $row["ManagerID"];
            $value2 = $row["Password"]; 
        }
    }} else {
        echo "0 results";
    }
    ?>
            
    <div class='Profile'>
            <div class='ProfilePic'><img src='ios.png' alt='Profile Picture'></div>    
            <table>
            <td><tr><td>            
            <label class="users1" for='old'>Old Password : </label><div class="form"><input type='password' name='old' class="input"></div></td></tr>
                
            <tr><td>            
                <label class="users1" for='new'>New Password : </label><div class="form"><input type='password' name='new' class="input"></div></td></tr>
                
            <tr><td>            
                <label class="users1" for='confirm'>Confirm Password : </label><div class="form"><input type='password' id ='confirm' name='confirm' class="input"></div></td></tr></table>   
            <td><button type='submit' onclick='event.preventDefault(); change();'>Change Password</button></td>
        
           
    </div>
    </div>
</form>
    </body>
</html>
<?php
if($_SERVER["REQUEST_METHOD"]=="GET"){ 
if(isset($_GET["pass"])) {
    $value4 = $_GET['pass'];
    $value5 = $_GET['pass1'];
    $value6 = $_GET['pass2'];
    $Password = $value4;
    $pass = password_hash("2", PASSWORD_DEFAULT);
    $newpass = password_hash($value5, PASSWORD_DEFAULT);
    
    //echo json_encode($result);
    //echo "<script>document.getElementById('confirm').value = 'x';</script>";
     //echo '<input type="text" id="username" name="username" value="' . $value1 . '">';
    //echo "<script>success();</script>";
    if(password_verify( $Password,$value2)){
        if($Status == 'user'){
        $update = "UPDATE users SET Password='$newpass' WHERE UserID='$value1'";
        }
        else if($Status == 'admin'){
            $update = "UPDATE admin SET Password='$newpass' WHERE AdminID='$value1'";
        }
        else if($Status == 'manager'){
            $update = "UPDATE manager SET Password='$newpass' WHERE ManagerID='$value1'";
        }
        $binding = $sambungan->prepare($update);
        $binding->execute();
        echo "1";}
    else{
        echo "2";

    }
    //echo $value2;
    //echo $Password;
    //$update = "UPDATE users SET Email='1234567890' WHERE UserID='U01'";
    //$binding = $sambungan->prepare($update);
    //$binding->execute();
    //echo "Error: Invalid request method";
    //echo json_encode(array('value' => $value4));
    
}
    //echo "Received values:";
}
?>