<?php
require_once('bootstrap.php');
include('sambung.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
?>


<!DOCTYPE html>
<html>
<head>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>

        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup_styles.css">
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
</head>
<body>
        <div class="background-blur"></div>
    <div class="form-containers" style = "height:820px;">
        <p class="title">Register Account</p>
        <form class="form" method="post" id="registrationForm">
            <input placeholder="Name" class="input" type="text" name="Fullname" required>
            <input placeholder="Email" class="input" type="email" name="Email" required>
            <input placeholder="Company Name" class="input" type="text" name="Company_name" required>
            <input placeholder="Company Address" class="input" type="text" name="Address" required>
            <input placeholder="Phone Number" class="input" type="text" name="Phone_Number" pattern="\d{11}" title="Please input correct number" required>
            <input id="password" placeholder="Password (min. 8 characters, at least 1 uppercase, 1 lowercase, 1 number, and 1 special character)" class="input" type="password" name="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Password must contain at least one number, one uppercase letter, one lowercase letter, one special character, and be at least 8 characters long" required>
            <input placeholder="Password Confirmation" class="input" type="password" name="Confirm_Password" required>
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

            <p class="page-links">
                <span class="sign-up-link"><a href="http://localhost/warehouse/u_reset_password" style="color:teal">Forgot Password?</a></span>
            </p>
            <button class="form-btn" type="submit" onclick='event.preventDefault(); signup21();'>Register</button>
        </form>
        <p class="sign-up-label">
            Already have an account? <span class="sign-up-link"><a onclick="openForm()">Login</a></span>
        </p>
    </div>

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

            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;

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
            var hasNumber = /\d/.test(password);
            var hasAlphabet = /[a-zA-Z]/.test(password);
            return hasNumber && hasAlphabet;
        }
    
function signup21() {
    var inputName = "Fullname";
    var inputName1 = "Address";
    var inputName2 = "Phone_Number";
    var inputName3 = "Email";
    var inputName4 = "Company_name";
    var inputName5 = "Password";
    var inputName6 = "Confirm_Password";
    var m = true;
    var message = "";

    var name = document.getElementsByName(inputName)[0].value.trim();
    var address = document.getElementsByName(inputName1)[0].value.trim();
    var phone = document.getElementsByName(inputName2)[0].value.trim();
    var email = document.getElementsByName(inputName3)[0].value.trim();
    var company = document.getElementsByName(inputName4)[0].value.trim();
    var password = document.getElementsByName(inputName5)[0].value.trim();
    var confirm = document.getElementsByName(inputName6)[0].value.trim();

    // Reset border colors
    resetBorderColor(inputName);
    resetBorderColor(inputName1);
    resetBorderColor(inputName2);
    resetBorderColor(inputName3);
    resetBorderColor(inputName4);
    resetBorderColor(inputName5);
    resetBorderColor(inputName6);

    if (!/^[a-zA-Z ]+$/.test(name)) {
        if (name !== "") {
            message += "Fullname must contain only alphabetic characters.<br>";
            document.getElementsByName(inputName)[0].style.borderColor = "red";
        }
        m = false;
    }
    if (!/^\S+@\S+\.\S+$/.test(email)) {
        if (email !== "") {
            message += "Email is not in a valid format.<br>";
            document.getElementsByName(inputName3)[0].style.borderColor = "red";
        }
        m = false;
    }
    if (!/^\d{11}$/.test(phone)) {
        if (phone !== "") {
            message += "Please insert a correct phone number.<br>";
            document.getElementsByName(inputName2)[0].style.borderColor = "red";
        }
        m = false;
    }
    if (!validatePassword(password)) {
        if (password !== "") {
            message += "Password must contain at least one number, one alphabet, and be at least 8 characters long.<br>";
            document.getElementsByName(inputName5)[0].style.borderColor = "red";
        }
        m = false;
    }
    if (password !== confirm) {
        if (password !== "" && confirm !== "") {
            message += "Both passwords are not the same!<br>";
            document.getElementsByName(inputName5)[0].style.borderColor = "red";
            document.getElementsByName(inputName6)[0].style.borderColor = "red";
        }
        m = false;
    }
    //if (!(name != "" && address != "" && company != "" && email != "" && phone != "" && password != "" && confirm != "")) {
      //  message += "Please don't leave it blank.<br>";
        //m = false;
        //highlightEmptyFields([inputName, inputName1, inputName2, inputName3, inputName4, inputName5, inputName6]);
    //}

    if (m) {
        signup121(name, address, phone, email, company, password, confirm);
    } else {
        Swal.fire("Invalid Input", message, "warning");
    }
}

function resetBorderColor(inputName) {
    document.getElementsByName(inputName)[0].style.borderColor = "";
}

function highlightEmptyFields(fieldNames) {
    fieldNames.forEach(function(name) {
        var field = document.getElementsByName(name)[0];
        if (field.value.trim() === "") {
            field.style.borderColor = "red";
        }
    });
}

function validatePassword(password) {
    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
    return passwordPattern.test(password);
}

function signup121(name, address, phone, email, company, password, confirm) {
    $.ajax({
        url: 'http://localhost/warehouse/account_user_signup_user.php',
        method: 'POST',
        data: {
            Fullname: name,
            Address: address,
            Phone_Number: phone,
            Email: email,
            Company_Name: company,
            Password: password,
            Confirm_Password: confirm
        },
        success: function(response) {
            var numbers = response.match(/\d+$/);
            if (numbers) {
                var lastNumber = numbers[0];
                switch(lastNumber) {
                    case "1":
                        success01();
                        break;
                    case "2":
                        success11();
                        break;
                    case "3":
                        success21();
                        break;
                    case "4":
                        success31();
                        break;
                    case "5":
                        success41();
                        break;
                    case "6":
                        success121();
                        break;
                    default:
                        Swal.fire("Error", "Unexpected response from server", "error");
                }
            }
        }
    });
}

function success01() {
    Swal.fire({
        title: "Successful!",
        text: "Sign up successful! Please wait for the admin to approve your account. You will receive an email once the admin approves your account.",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'http://localhost/warehouse/main?login';
        }
    });
}

function success11() {
    Swal.fire("Warning", "Please Don't Leave It Blank!", "warning");
}

function success21() {
    Swal.fire("Warning", "Both Passwords Don't Match!", "warning");
}

function success31() {
    Swal.fire("Warning", "Email Already Exists. Please Use Another Email!", "warning");
}

function success41() {
    Swal.fire("Warning", "Sign Up Successfully! AdminID Has Sent To Your Email!", "warning");
}

function success121() {
    Swal.fire("Warning", "Sign Up Unsuccessfully!", "warning");
}
    </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fullname = $_POST["Fullname"];
    $Address = $_POST["Address"];
    $Phone_Number = $_POST["Phone_Number"];
    $Company_Name = $_POST["Company_Name"]; 
    $Date = date("Y/m/d");
    $pass = $_POST["Password"];
    $conf = $_POST["Confirm_Password"];
    $Password = password_hash($_POST["Password"], PASSWORD_DEFAULT); 
    $Email = $_POST["Email"];
    if (empty($Fullname) || empty($Address) || empty($Phone_Number) || empty($Company_Name) || empty($Password) || empty($Email)) {
        echo "2";
        exit;
    }
    
    $find = "SELECT MAX(CAST(SUBSTRING(UserID, 2) AS UNSIGNED)) AS Latest FROM users";
    $result = mysqli_query($sambungan, $find);
    $late = mysqli_fetch_assoc($result);
    $latest = $late['Latest'];
    $UserID = 'U' . str_pad($latest + 1, strlen($latest), '0', STR_PAD_LEFT);

    $exist = "SELECT * FROM users WHERE Email = ?";
    if ($stmt = $sambungan->prepare($exist)) {
        $stmt->bind_param('s', $Email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "4";
            exit;
            
        } else {
            $active = 2;
            $insert = "INSERT INTO users (UserID, Fullname, Address, Phone_Number, Company_Name, Date, Password, Email,Active) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
            $stmt = mysqli_prepare($sambungan, $insert);
            mysqli_stmt_bind_param($stmt, "sssssssss", $UserID, $Fullname, $Address, $Phone_Number, $Company_Name, $Date, $Password, $Email, $active); 
            if (mysqli_stmt_execute($stmt)) {
                echo "1";
            } else {
                echo "6";
                exit;
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

        $mail->Subject = 'Signup Account';
        $mail->Body = 'Thanks for register account from our website. Please wait for admin to approve your account. If you have any problem, you can email our admin sylvesteronglide808@gmail.com or use the live chat on the main page. ';

        $mail->send();
            mysqli_stmt_close($stmt);
            mysqli_close($sambungan);
            exit;
        }
    }   
}

?>