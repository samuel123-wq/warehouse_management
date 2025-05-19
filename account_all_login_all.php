<?php 
require_once('bootstrap.php');
include('sambung.php');
$FOUND = FALSE;
$INCRE = 1;

if (isset($_POST['userid']) && isset($_POST['password'])) {
    $userid = $_POST['userid'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE UserID=?";
    $result = mysqli_prepare($sambungan, $sql);
    mysqli_stmt_bind_param($result, 's', $userid);
    mysqli_stmt_execute($result);
    $get = mysqli_stmt_get_result($result);
    if(mysqli_num_rows($get) > 0) {
        $user = mysqli_fetch_assoc($get);
        if($user['Active'] == '1'){
        if(password_verify($password, $user['Password'])){
            $FOUND = TRUE;
            session_start();
            $_SESSION['ID'] = $user['UserID'];
            $_SESSION['Status'] = 'user';
            $_SESSION['num'] = $user['Phone_Number'];            
            $_SESSION['Name'] = $user['Fullname'];  
            $_SESSION['Email'] = $user['Email'];
            echo "1";
            exit();
        }
        }
        else if($user['Active'] == "2"){
            $FOUND = TRUE;
            echo "7";
            exit();
        }
        else if($user['Active'] == "3"){
            $FOUND = TRUE;
            echo "10";
            exit();
        }
        
    }

    $sqls = "SELECT * FROM admin WHERE AdminID = ?";
    $results = mysqli_prepare($sambungan, $sqls);
    mysqli_stmt_bind_param($results, 's', $userid);
    mysqli_stmt_execute($results);
    $gets = mysqli_stmt_get_result($results);
    if(mysqli_num_rows($gets) > 0) {
        $admin = mysqli_fetch_assoc($gets);
        if(password_verify($password, $admin['Password'])){
            $FOUND = TRUE;       
            session_start();
            $_SESSION['ID'] = $admin['AdminID'];
            $_SESSION['Status'] = 'admin';
            $_SESSION['Name'] = $admin['Fullname']; 
            $_SESSION['num'] = $admin['Phone_Number'];
            $_SESSION['Email'] = $admin['Email'];
            echo "1";     
            exit();
        }
        }

    $sqlss = "SELECT * FROM manager WHERE ManagerID=?";
    $resultss = mysqli_prepare($sambungan, $sqlss);  
    mysqli_stmt_bind_param($resultss, 's', $userid);
    mysqli_stmt_execute($resultss);
    $getss = mysqli_stmt_get_result($resultss);
    if(mysqli_num_rows($getss) > 0) {
        $manager = mysqli_fetch_assoc($getss);
        if(password_verify($password, $manager['Password'])){
            $FOUND = TRUE;
            session_start();
            $_SESSION['ID'] = $manager['ManagerID'];
            $_SESSION['Status'] = 'manager';
            $_SESSION['Name'] = $manager['Fullname'];   
            $_SESSION['Email'] = $manager['Email'];                
            $_SESSION['num'] = $manager['Phone_Number'];
            echo "1";      
            exit();
        }
    }

    if($FOUND != TRUE) {
        echo "4";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    <title>Smart Warehouse</title>
    <style>
    .sign-up-links {
  margin-left: 1px;
  font-size: 11px;
  text-decoration: underline;
  text-decoration-color: teal;
  color: teal;
  cursor: pointer;
  font-weight: 800;
    font-family: Helvetica, Arial, sans-serif;
}
        
.sign-up-label {
  margin: 0;
  font-size: 10px;
  color: #747474;
    font-family: Helvetica, Arial, sans-serif;
}
.page-links {
  text-decoration: underline;
  margin: 0;
  text-align: end;
  color: #747474;
  text-decoration-color: #747474;
}
    </style>
    <title>Login Pages</title>
</head>
<body>
    <div class="form-container">
        
        <p class="title">Welcome back</p>
        <form class="form" method="post">

            <input placeholder="ID" class="input" type="text" name="userid">
            <input type="password" class="input" placeholder="Password" name="password">
            <p class="page-links">
                <span class="sign-up-links"><a href="http://localhost/warehouse/u_reset_password" style="color:teal">Forgot Password?</a></span>
            </p>
            <button class="form-btn" type="submit" onclick='event.preventDefault(); login();'>Log in</button>
        </form>
        <p class="sign-up-label">
            Don't have an account?<span class="sign-up-link"><a onclick="openForms()">Sign up</a></span>
        </p>
    </div>
    </body></html>

<script>
function login() {
        var inputName = "userid";
        var inputName1 = "password";

        var user = document.getElementsByName(inputName)[0].value;
        var pass = document.getElementsByName(inputName1)[0].value;
        
        if(user == "" || pass == ""){
            Swal.fire("Warning", "Don't Leave It Blank!", "Warning");
        }
        
        else{
            login1(user, pass);
            }
    }

function login1(userid1, pass) {
            $.ajax({
                url: 'http://localhost/warehouse/account_all_login_all.php?',
                    method: 'POST',
                data: {
                        userid: userid1,
                        password: pass
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
                        else if(lastNumber == "7"){
                            success1311();
                        }
                        else if(lastNumber == "10"){
                            success1411();
                        }
                        else{
                            success12();
                        }
           }    
                    }
                });
    
}   
    
function success() {
    Swal.fire({
        title: "Successful!",
        text: "Welcome to CRSST Warehouse Website!",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location='http://localhost/warehouse/main';
        }
    });
}
    
function success1311(){
    Swal.fire("Warning", "Admin haven't approve this account yet!", "warning");
}
   
function success1411(){
    Swal.fire("Warning", "This account has been rejected by admin!", "warning");
}
    
function success12(){
    Swal.fire("Warning", "Wrong Password Or UserID!", "warning");
}
</script>

