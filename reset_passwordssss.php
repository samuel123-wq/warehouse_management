<?php
require_once('bootstrap.php');
include("sambung.php");
//include('header.php');
session_start();  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

?>


<script>
    function kick(){
        var inputName = "otp";
        var name = document.getElementsByName(inputName)[0].value;
        $.ajax({
                url: 'http://localhost/warehouse/reset_passwordssss.php?',
                    method: "POST",
                data: {
                        submit: "1",
                        otp: name
                        },
            
                success: function(response) {
                    var numbers = response.match(/\d+$/);
                    console.log(response);
                    if (numbers) {
                            var lastNumber = numbers[0];
                            if(lastNumber == "1"){
                                    success();
                                }else if(lastNumber=="2"){
                                 notsuccess();
                                 }
                        else{
                            notsuccess1();
                        }
                }    
                    }
                });
    }
    
    function success() {
    Swal.fire({
        title: "Successful!",
        text: "OTP has been successfully validated",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) { window.location='http://localhost/warehouse/passwordchanged.php';
        }
    });
}
    
    function notsuccess() {
    Swal.fire({
        title: "Unsuccessful!",
        text: "Incorrect OTP! Please try again",
        icon: "warning",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
    
        function notsuccess1() {
    Swal.fire({
        title: "Unsuccessful!",
        text: "Unable to get session otp!",
        icon: "warning",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
</script>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    
    <title>Smart Warehouse</title>
    <style>
        body {
            background-color: rgba(0, 0, 0, 0.04);
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 350px;
            background-color: #fff;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-radius: 10px;
            padding: 20px 30px;
            margin: 50px auto;
        }

        .title {
            text-align: center;
            font-size: 28px;
 
            margin-bottom: 30px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .input {
            border-radius: 20px;
            border: 1px solid #c0c0c0;
            padding: 12px 15px;
            flex: 1; 
        }

        .buttons-container {
            display: flex;
            gap: 10px;
        }

        #send_otp {
            flex-shrink: 0; 
            background-color: teal;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 15px;
            cursor: pointer;
        }

        #send_otp:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        #otpContainer {
            display: flex;
            align-items: center; 
        }

        #otpContainer label {
            font-weight: bold;
            margin-right: 10px;
        }

        .form-btn {
            background-color: teal;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 15px;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .form-btn:active {
            box-shadow: none;
        }
        button[type='bu'] {
            background-color: rgba(0,0,0,0);
            border: none;
            cursor: pointer;
            }

    </style>
</head>
<body>

<div class="form-container">
            <button type='bu' onclick='event.preventDefault(); customBack125();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1 class="title">Reset Password</h1>
    
    <form class="form" method="post">               

            <input placeholder="OTP " type="text" id="otp" name="otp" class="input" required>



        <button type="submit" class="form-btn" name="submit" onclick='event.preventDefault(); kick();'>Next</button>
    </form>
    <?php
    include('loading.html');
    ?>
</div>



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
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
if(isset($_POST["submit"])){
    if(isset($_SESSION['otp'])){
        $enteredOTP = $_POST["otp"];
        $otp = $_SESSION['otp'];
        
        if ($enteredOTP == $otp) {
        echo "1";
        } else {
            echo "2";
        }
    }
    else{
        echo "3";
    }
}
}

?>
