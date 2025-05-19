           <?php
    //include('header.php');
session_start();
require_once('bootstrap.php');
include("sambung.php");
    ?>




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

        #passwordRequirements {
            font-size: 10px;
        }

        .requirement {
            display: flex;
            align-items: center;
        }

        .requirement svg {
            margin-right: 5px;
        }

        button[type='bu'] {
            background-color: rgba(0,0,0,0);
            border: none;
            cursor: pointer;
            }
    </style>
    <script>
    function validatePassword(password) {

            if (password.length < 8) {
                return false;
            }
            var hasNumber = /\d/.test(password);
            var hasAlphabet = /[a-zA-Z]/.test(password);
            return hasNumber && hasAlphabet;
        }
        
        function reset12(){
            var first = "password1";
            var second = "password2";
            var pass = document.getElementsByName(first)[0].value;
            var pass2 = document.getElementsByName(second)[0].value;
            
            
            if(pass == "" || pass2 == ""){
            Swal.fire("Warning", "Please Don't Leave It Blank!", "warning");
            if(pass == ""){
            document.getElementsByName(first)[0].style.borderColor = "red";
            }
            if(pass2 == ""){
                document.getElementsByName(second)[0].style.borderColor = "red";
                document.getElementsByName(first)[0].style.borderColor = "red";
            }
                
        }
            
        else{
        if(pass != pass2){
            Swal.fire("Warning", "The New Password and Confirm Password Is Not The Same!", "warning");
            document.getElementsByName(first)[0].style.borderColor = "red";
            document.getElementsByName(second)[0].style.borderColor = "red";
        }
        else if(!validatePassword(pass))
            {
            Swal.fire("Warning", "Password must contain at least one number, one alphabet, and be at least 8 characters long.");
            document.getElementsByName(first)[0].style.borderColor = "red";
            document.getElementsByName(second)[0].style.borderColor = "red";
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
            changepassword(pass, pass2);
        }
    });
        }
    }
                 
        }
        
        
        
function changepassword(pass, pass2) {
    //alert(pass+pass2);
    $.ajax({
    url: 'http://localhost/warehouse/passwordchanged.php',
    method: 'POST',
    data: {
        submit: "1",
        password : pass,
        password2 : pass2 
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
            else if(lassNumber == "2"){
                success1();
            }
            else{
                success2();
            }
           }
     },

        
});
}
    
function success() {
    //alert(dats);
    Swal.fire({
        title: "Successful!",
        text: "Change Password Successfully",
        icon: "success",
        showConfirmButton: true 
    }).then((result) => {
        if (result.isConfirmed) {
 window.location='http://localhost/warehouse/index.php?login';
        }
    });
}

function success1() {
    Swal.fire("Warning", "Change Password Unsuccessfully!", "warning");
    //window.history.back();
}
        
function success2() {
    Swal.fire("Warning", "Session Variable No Set. Please Try Again!!", "warning");
    //window.location='http://localhost/warehouse/index.php?login';
}
    </script>
</head>
<body>
<div class="form-container" id="ResetForm">
            <button type='bu' onclick='event.preventDefault(); customBack125();'><img src='back.png' style='width: 25px; height: 25px;'></button>
    <h1 class="title">Reset Password</h1>
    <form class="form" method="post" id="registrationForm">
        <input placeholder="New Password" id="password1" type="password" class="input" name="password1" required>
        <input placeholder="Confirmation Password" type="password" class="input" name="password2" required>
        <div id="passwordRequirements" style="font-size:12px; margin-left:20px;">

                <div id="check0"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 8 characters long.</div>
                <div id="check1"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 number.</div>
                <div id="check2"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 special symbol.</div>
                <div id="check3"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 lowercase letter.</div>
                <div id="check4"><i class="far fa-check-circle"></i><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> At least 1 uppercase letter.</div>
            </div>
        <button type="submit" class="form-btn" name="submit"
                onclick="event.preventDefault(); reset12();">Reset Password</button>
 
    </form>
</div>
</body>
</html>

<script>

        function check() {
            var input = document.getElementById("password1").value;
            input = input.trim();
            document.getElementById("password1").value = input;

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
    
        document.getElementById("password1").addEventListener("input", check);
    
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
if($_SERVER["REQUEST_METHOD"]=="POST"){ 
if(isset($_POST["submit"])){
    if(isset($_POST["password"]) && isset($_POST["password2"])) {
        $email = $_SESSION['email'];
        $ID = $_SESSION['ID'];
        $Pass = $_POST["password"];
        $Pass2 = $_POST["password2"];
        $Pp = password_hash($_POST["password"], PASSWORD_DEFAULT);
        if ($Pass == $Pass2) {
            $sql = "UPDATE Users SET Password='$Pp' WHERE UserID='$ID' AND Email='$email'";
            $result = mysqli_query($sambungan, $sql);

            if ($result) {
                echo "1";
            } else {
                echo "2";
            }
        } else {
            echo "2";
        }
    } else {
        echo "3";
    }
}
}
?>