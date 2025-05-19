<?php
require_once('bootstrap.php');
include('sambung.php');
//include('header');
session_start();

if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

$STA = $_SESSION['Status'];
if($STA != 'admin') {
header("location: http://localhost/warehouse/main");
    exit();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fullname = $_POST["Fullname"];
    $Date =  date("Y/m/d");
    $pass = $_POST["Password"];
    $conf = $_POST["Confirm_Password"];
    $Password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    $Email = $_POST["Email"];
    $Phone_Number = $_POST["Phone_Number"];   
    
    if ( empty($Fullname) || empty($Phone_Number) || empty($Password) || empty($Email)) {
        echo "2";   
        exit;
    }
    if ($conf != $pass) {
        echo "3";   
        exit;
    } 
    $find = "SELECT MAX(CAST(SUBSTRING(ManagerID, 2) AS UNSIGNED)) AS Latest FROM Manager";
    $result = mysqli_query($sambungan, $find);
    $late = mysqli_fetch_assoc($result);
    $latest = $late['Latest'];

    if ($latest === null) {
        $latest = 0;
    }

    $ManagerID = 'M' . str_pad($latest + 1, 2, '0', STR_PAD_LEFT);
    
  
    
    
    $exist = "SELECT * FROM manager WHERE Email=?";
    if ($stmt = $sambungan->prepare($exist)) {
        $stmt->bind_param('s', $_POST['Email']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "4>";
            exit;
        } else {
            $insert = "INSERT INTO manager(ManagerID, Fullname, Phone_Number, Date, Password, Email) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($sambungan, $insert);
            mysqli_stmt_bind_param($stmt, "ssssss", $ManagerID, $Fullname,  $Phone_Number, $Date, $Password, $Email); 

            if (mysqli_stmt_execute($stmt)) {
                echo "5";
            } else {
                echo "6";
            }
        $otp = rand(100000, 999999);

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'crsstwarehouse@gmail.com'; 
        $mail->Password = 'pfrfgjjveyrbqeyx'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('crsstwarehouse@gmail.com');
        $mail->addAddress($Email);
        $mail->isHTML(true);

        $mail->Subject = 'ManagerID';
        $mail->Body = 'Your account has been registered by the Admin. Here is your ID for login : ' . $ManagerID;

        $mail->send();   
            mysqli_stmt_close($stmt);
            mysqli_close($sambungan);
            exit;
        }
    }   
}
?>



<!DOCTYPE html>
<html>
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
    
    <style>
    body {
        height: 100vh; 
        margin: 0; 
        overflow: hidden; 
        font-family: Helvetica, Arial, sans-serif;
        background-image: url("wraehu.png");
        background-repeat: no-repeat;
        background-size: cover;
    }

    .form-containers {
        margin-top: 6%;
        width: 500px;
        height: auto;
        background-color: #fff;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        border-radius: 10px;
        box-sizing: border-box;
        padding: 20px 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
    }

    .title-container {
        display: flex;
        align-items: left;
        margin-bottom: 8px;
    }

    .title {
        text-align: center;
        font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande", "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        font-size: 28px;
        font-weight: 800;
        margin-left: 10px; 
    }

    .form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-bottom: 15px;
    }

    .input {
        border-radius: 20px;
        border: 1px solid #c0c0c0;
        outline: 0 !important;
        box-sizing: border-box;
        padding: 12px 15px;
    }

    .page-link {
        text-decoration: underline;
        margin: 0;
        text-align: end;
        color: #747474;
        text-decoration-color: #747474;
    }

    .page-link-label {
        cursor: pointer;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9px;
        font-weight: 700;
    }

    .page-link-label:hover {
        color: #000;
    }

    .form-btn {
        padding: 10px 15px;
        font-family: Helvetica, Arial, sans-serif;
        border-radius: 20px;
        border: 0 !important;
        outline: 0 !important;
        background: teal;
        color: white;
        cursor: pointer;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .form-btn:active {
        box-shadow: none;
    }

    .sign-up-label {
        margin: 0;
        font-size: 10px;
        color: #747474;
        font-family: Helvetica, Arial, sans-serif;
    }

    .sign-up-link {
        margin-left: 1px;
        font-size: 11px;
        text-decoration: underline;
        text-decoration-color: teal;
        color: teal;
        cursor: pointer;
        font-weight: 800;
        font-family: Helvetica, Arial, sans-serif;
    }

    .buttons-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        margin-top: 20px;
        gap: 15px;
    }

    .apple-login-button,
    .google-login-button {
        border-radius: 20px;
        box-sizing: border-box;
        padding: 10px 15px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 11px;
        gap: 5px;
    }

    .apple-login-button {
        background-color: #000;
        color: #fff;
        border: 2px solid #000;
    }

    .google-login-button {
        border: 2px solid #747474;
    }

    .apple-icon,
    .google-icon {
        font-size: 18px;
        margin-bottom: 1px;
    }

    button[type='bu'] {
        background-color: rgba(0, 0, 0, 0);
        border: none;
        cursor: pointer;
        transform: translateY(-8px);
        padding-right: 10px;
        margin-right: 5px;
        margin-left: 0px;
    }
    </style>
    
    <title>Smart Warehouse</title>
</head>
<body >



    
    
 <div class="form-containers">
<div class="title-container">
<button type='bu' onclick='event.preventDefault(); customBack2();'><img src='back.png' style='width: 25px; height: 25px;'></button>

      <p class="title">Register Manager Account</p>
    </div>
      <form class="form" method="post">         
      <input placeholder="Name" class="input" type="text" name="Fullname" >    
<input placeholder="Phone Number" class="input" type="text" name="Phone_Number" pattern="\d{11}" title="Please input correct number" required>

      <input placeholder="Email" class="input" type="email" name="Email"> 
   
<input id="password" placeholder="Password (min. 8 characters, at least 1 uppercase, 1 lowercase, 1 number, and 1 special character)" class="input" type="password" name="Password" pattern="(?=.\d)(?=.[a-z])(?=.[A-Z])(?=.\W).{8,}" title="Password must contain number, uppercase letter, lowercase letter, special character, and be at least 8 characters long" required>
   <input placeholder="Password Confirmation" class="input" type="Password" name="Confirm_Password">           
   <div>    
                            <p> Password must contain :</p>
            <div id="passwordRequirements" style="font-size:12px; margin-left:20px;">

                <div id="check0"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 8 characters long.</div>
                <div id="check1"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 number.</div>
                <div id="check2"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 special symbol.</div>
                <div id="check3"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 lowercase letter.</div>
                <div id="check4"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 uppercase letter.</div>
            </div>
            </div>           
          
          

        <button class="form-btn" type="submit" onclick='event.preventDefault(); signup();'>Register</button>
      </form>


    </div>

</body>
</html>

<script>  

        function check() {
            var input = document.getElementById("password").value;
            input = input.trim();
            document.getElementById("password").value = input;

           if (input.length >= 8) {
                document.getElementById("check0").innerHTML = '<i class="far fa-check-circle" style="color: green;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></i> At least 8 characters long.';
            } else {
                document.getElementById("check0").innerHTML = '<i class="far fa-check-circle" style="color: red;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i> At least 8 characters long.';
            }

            if (input.match(/[0-9]/)) {
                document.getElementById("check1").innerHTML = '<i class="far fa-check-circle" style="color: green;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></i> At least 1 number.';
            } else {
                document.getElementById("check1").innerHTML = '<i class="far fa-check-circle" style="color: red;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i> At least 1 number.';
            }

            if (input.match(/[!@#$%^&*(),.?":{}|<>]/)) {
                document.getElementById("check2").innerHTML = '<i class="far fa-check-circle" style="color: green;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></i> At least 1 special symbol.';
            } else {
                document.getElementById("check2").innerHTML = '<i class="far fa-check-circle" style="color: red;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i> At least 1 special symbol.';
            }

            if (input.match(/[a-z]/)) {
                document.getElementById("check3").innerHTML = '<i class="far fa-check-circle" style="color: green;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></i> At least 1 lowercase letter.';
            } else {
                document.getElementById("check3").innerHTML = '<i class="far fa-check-circle" style="color: red;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i> At least 1 lowercase letter.';
            }

            if (input.match(/[A-Z]/)) {
                document.getElementById("check4").innerHTML = '<i class="far fa-check-circle" style="color: green;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></i> At least 1 uppercase letter.';
            } else {
                document.getElementById("check4").innerHTML = '<i class="far fa-check-circle" style="color: red;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i> At least 1 uppercase letter.';
            }
        
            if (input.length >= 8) {
                document.getElementById("check0").style.color = "green";
                
            } else {
                document.getElementById("check0").style.color = "red";
            }
        
            if(input.match(/[0-9]/i)){
                document.getElementById('check1').style.color = "green";
            }
        else {
                document.getElementById("check1").style.color = "red";
            }
            if(input.match(/[!@#$%^&*(),.?":{}|<>]/)){
                document.getElementById("check2").style.color = "green";
            }
        else {
                document.getElementById("check2").style.color = "red";        
        
        }
             if(input.match(/[a-z]/) ){
                document.getElementById("check3").style.color = "green";
            }
        else {
                document.getElementById("check3").style.color = "red";        
        
        }           
              if(input.match(/[A-Z]/) ){
                document.getElementById("check4").style.color = "green";
            }
        else {
                document.getElementById("check4").style.color = "red";        
        
        }            
            
        }
        document.getElementById("password").addEventListener("input", check);
        
        document.getElementById("registrationFormssss").addEventListener("submitssss", function(event) {
            var password = document.getElementsByName("Password")[0].value;
            var confirmPassword = document.getElementsByName("Confirm_Password")[0].value;

            var passwordPattern = /^(?=.\d)(?=.[a-z])(?=.[A-Z])(?=.\W).{8,}$/;

            if (!passwordPattern.test(password)) {
                alert("Password must contain at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.");
                event.preventDefault();
            } else if (password !== confirmPassword) {
                alert("Passwords do not match.");
                event.preventDefault(); 
            }
        }); 
function validatePassword(password) {

            if (password.length < 8) {
                return false;
            }
            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/.test(password);
            return passwordPattern;
        }
    
function signup() {
        var inputName = "Fullname";
        var inputName1 = "Phone_Number";
        var inputName2 = "Email";
        var inputName3 = "Password";
        var inputName4 = "Confirm_Password";
        let m = true;
        var message = "";
        var name = document.getElementsByName(inputName)[0].value;
        var phone = document.getElementsByName(inputName1)[0].value;
        var email = document.getElementsByName(inputName2)[0].value;
        var password = document.getElementsByName(inputName3)[0].value;
        var confirmpass = document.getElementsByName(inputName4)[0].value;
    
        if (!/^[a-zA-Z ]+$/.test(name.trim())) {
            if(name == ""){m = false;}
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
            document.getElementsByName(inputName1)[0].style.borderColor = "red";
            m = false;}
        }
    
        if(!validatePassword(password)){
            if(password == ""){m=false;}
            else{
            message += "Password must contain at least one number, one alphabet, one special character, and be at least 8 characters long.<br>";
            document.getElementsByName(inputName3)[0].style.borderColor = "red";
            document.getElementsByName(inputName4)[0].style.borderColor = "red";
            m = false;}
        }
    
        if(name == "" || email == "" || phone == "" || password == "" || confirmpass == ""){
            message += "Please dont leave it blank.<br>";
            m = false;
            if(name == ""){
                var fullname1 = document.getElementsByName(inputName)[0];
                fullname1.style.borderColor = "red";
            }
            if(password == ""){
                document.getElementsByName(inputName3)[0].style.borderColor = "red";
            }
            if(email == ""){
                document.getElementsByName(inputName2)[0].style.borderColor = "red";
            }
            if(phone == ""){
                document.getElementsByName(inputName1)[0].style.borderColor = "red";
            }
            if(confirmpass == ""){
                document.getElementsByName(inputName4)[0].style.borderColor = "red";
            }
        }
    
       if(m == true){
        signup1(name, phone, email, password, confirmpass);
       }
    else {
            Swal.fire("Invalid Input", message, "warning");
        }
    }

function signup1(name, phone, email, password, confirmpass) {
            $.ajax({
                url: 'http://localhost/warehouse/m_signup?',
                    method: 'POST',
                data: {
                        Fullname: name,
                        Phone_Number: phone,
                        Email: email,
                        Password: password,
                        Confirm_Password: confirmpass
                        },
                success: function(response) {
                    console.log(response);
                    console.log(response);
                    var numbers = response.match(/\d+$/);
                    //alert(numbers);
                    console.log(numbers);
                    if (numbers) {
                        var lastNumber = numbers[0];
                        if(lastNumber == "1"){
                            success();
                        }
                        else if(lastNumber == "2"){
                            success1();
                        }
                        else if(lastNumber == "3"){
                            success2();
                        }
                        else if(lastNumber == "4"){
                            success3();
                        }
                        else if(lastNumber == "5"){
                            success();
                        }
                        else if(lastNumber == "6"){
                            success12();
                        }
           }    
                    }
                });
    
}   
    
function success() {
    Swal.fire({
        title: "Successful!",
        text: "Sign Up Successfully! ManagerID Has Sent To Your Email!",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location='http://localhost/warehouse/m_signup';
        }
    });
}

function success1(){
    Swal.fire("Warning", "Please Don't Leave It Blank!", "warning");
}

function success2(){
    Swal.fire("Warning", "Both Password Doesn't Same!", "warning");
}

function success3(){
    Swal.fire("Warning", "Email Already Exist. Please Use Another Email!", "warning");
}
   
function success4(){
    Swal.fire("Warning", "Sign Up Successfully! AdminID Has Sent To Your Email!", "warning");
}
function success12(){
    Swal.fire("Warning", "Sign Up Unsuccessfully!", "warning");
}
</script>

<script>
function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];
            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }
        function customBack2() {
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