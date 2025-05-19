<?php
session_start();
require_once('bootstrap.php');
include("sambung.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
?>

<script>
    function kick(){
        var inputName = "Usernames";
        var inputName1 = "email";
        m = true;
        var message = "";
        var name = document.getElementsByName(inputName)[0].value.toUpperCase();
        var email = document.getElementsByName(inputName1)[0].value.toUpperCase();
        
        if (!/^\S+@\S+\.\S+$/.test(email)) {
            if(email == ""){m=false;}
            else{
            message += "Email is not in a valid format.\<br>";
            document.getElementsByName(inputName1)[0].style.borderColor = "red";
            m = false;}
        }
        if(name == "" || email == ""){
            document.getElementsByName(inputName1)[0].style.borderColor = "red";
            document.getElementsByName(inputName)[0].style.borderColor = "red";
            message += "Please dont leave it blank.\<br>";
            m = false;
        }
        
        if(m==true){
            //alert('heare');
        $.ajax({
                url: 'http://localhost/warehouse/account_user_reset_password_user.php?',
                    method: 'POST',
                data: {
                        submit: "1",
                        email: email,
                        Usernames: name
                        },
                success: function(response) {
                    var numbers = response.match(/\d+$/);
                    console.log(response);
                    if (numbers) {
                            var lastNumber = numbers[0];
                            if(lastNumber == "1"){
                                    otp();
                                }
                    else{
                            notsuccess();
                    }
                }    
                    }
                });
    }
        else{
            Swal.fire("Invalid Input", message, "warning");
        }
    }
    
    function otp() {
    Swal.fire({
        title: "Successful!",
        text: "OTP has sent to your email.",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location='http://localhost/warehouse/OTP';
        }
    });
}
    
    function notsuccess() {
    Swal.fire({
        title: "Unsuccessful!",
        text: "The provided UserID does not own the entered email. Please try again.",
        icon: "warning",
        showConfirmButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            //window.location='http://localhost/warehouse/OTP';
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
    <title>Smart Warehouse</title>
    <style>
        body {
            background-color: rgba(0,0,0,0.04);
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
     <link rel="stylesheet" href="loader.css">   
    
</head>
    
<body>

<div class="form-container">
            <button type='bu' onclick='event.preventDefault(); customBack();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1 class="title">Reset Password</h1>
    
    <form class="form" method="post">         
        <input placeholder="ID" class="input" type="text" name="Usernames" required>         
        <input placeholder="Email" class="input" type="email" name="email" required> 
        
         

       <button type="submit" class="form-btn" name="submit" onclick='event.preventDefault(); kick();'>Next</button>
    </form>
    <?php
    include('loading.html');
    ?>
</div>



</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $email = $_POST["email"];
    $ID = $_POST["Usernames"];
    $check_query = "SELECT * FROM Users WHERE UserID=? AND Email=?";
    
    $stmt = $sambungan->prepare($check_query);
    $stmt->bind_param("ss", $ID, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $otp = rand(100000, 999999);

        

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'crsstwarehouse@gmail.com';
            $mail->Password = 'pfrfgjjveyrbqeyx';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('crsstwarehouse@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $mail->Subject = 'OTP for Password Reset';
            $mail->Body = 'Your OTP is: ' . $otp;

            $mail->send();

            $_SESSION['otp'] = $otp;
            $_SESSION['ID'] = $ID;
            $_SESSION['email'] = $email;

            echo "1";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "2";
    }
    $stmt->close();
    $sambungan->close();
    exit();
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