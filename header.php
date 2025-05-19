<?php

require_once('bootstrap.php');
include('sambung.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$s = $_SESSION['Status'];

if(!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    $is = 0;

}
else{
    if($_SESSION['Status'] == 'user'){
    $is = 1;
    }
    else if ($_SESSION['Status'] == 'admin'){
        $is = 2;}
        else if ($_SESSION['Status'] == 'manager'){
        $is = 3;}
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Warehouse</title>
    <link rel="stylesheet" href="assets/vendors/themify-icons/css/themify-icons.css">

    <link rel="stylesheet" href="assets/css/leadmark.css">
    <style>

        .headerdrop {
            float: left;
            position: relative;
        }

        .headerdrop .headerdropbtn {
            font-size: 13px;
            font-weight: 600;
            border: none;
            outline: none;
            color: #444;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .headerdropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            z-index: 1001;
        }

        .headerdropdown-content a {
            float: none;
            color: #000;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .headerdropdown-content a:hover {
            background-color: #ddd;
        }

        .headerdrop:hover .headerdropdown-content {
            display: block;
        }

        #log, #sign {
            display: none;
            z-index: 1002;
            margin-top:50px;
            background: white;
           
        }

        .blur {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
#close, #close {
    position: absolute;
    top: 50px;
    right: 600px;
    cursor: pointer;
    font-size: 20px;
    z-index: 1003;
    color: black;
    background-color: white;
    border: none;
    outline: none;
    padding: 5px;
}
 .sub-dropdown {
            position: relative;
        }

        .sub-dropdown-content {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
            background-color: #f9f9f9;
            min-width: 160px;
            z-index: 1;
        }

        .sub-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .sub-dropdown-content a:hover {
            background-color: #ddd;
        }


        .sub-dropdown:hover .sub-dropdown-content {
            display: block;
        }       
    </style>
</head>
<body>
    <div id="main-content">
        <!-- page Navigation -->
        <nav class="navbar custom-navbar navbar-expand-md navbar-light fixed-top affix" data-spy="affix" data-offset-top="10" style="background-color:white;">
            <div class="container">
                <a class="navbar-brand" href="#">CRSST</a>
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">                     
                    <li class="nav-item">
                                <a class="nav-link" href="main">Home</a>
                            </li>
                    

                            <div class="headerdrop">
                                <li class="nav-item">
                                    <a class="nav-link headerdropbtn">More</a>
                                </li>
                                <div class="headerdropdown-content">
                  <?php if ($is == 1) { ?>  
                      <div class="sub-dropdown">
                          
                      <a >Payment </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="pays">Settlement</a>                         
                      <a href="am_history_payment">Payment History</a>  
                     </div>
                     </div>
                     <div class="sub-dropdown">
                          
                      <a >Product </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="a_add_product">Add Product</a> 
                      <a href="a_list_product">Manage Product</a> 
                         </div></div>
                      <div class="sub-dropdown">
                          
                      <a >In/Outbound </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="in_bound">Request Inbound</a>
                      <a href="retrieve ">Request Outbound</a> 
                      <a href="all_inoutbound_history">In/Outbound History</a>
                          </div></div>
                                    

                    <?php } elseif ($is == 2) { ?>      
                      <div class="sub-dropdown">
                          
                      <a >Account</a>
                          
                      <div class="sub-dropdown-content">
                      <a href="a_signup">Signup Admin Account</a>
                      <a href="m_signup">Signup Manager Account</a>
                      <a href="a_list_account">Manage Account </a>
                      <a href="a_approve">Approve Account </a>
                          </div></div>  
                      <div class="sub-dropdown">
                          
                      <a >Product </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="a_add_product ">Add Product</a> 
                      <a href="a_list_product">Manage Product</a> 
                          </div></div>
                      <div class="sub-dropdown">
                          
                      <a >In/Outbound </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="out ">Request Outbound</a> 
                      <a href="all_inoutbound_history">In/Outbound History</a>
                          </div></div>         
                      <a href="a_pay_mana">Payment History</a> 
                      <a href="chart">Data Analysis</a>
                    <?php } elseif ($is == 3) { ?> 

                      <a href="a_pay_mana">Payment History</a>   

                      <div class="sub-dropdown">
                          
                      <a >Product </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="a_add_product">Add Product</a> 
                      <a href="a_list_product">Manage Product</a>  
                          </div></div>
                      <div class="sub-dropdown">
                          
                      <a >In/Outbound </a>
                          
                      <div class="sub-dropdown-content">
                      <a href="out ">Request Outbound</a> 
                      <a href="all_inoutbound_history">In/Outbound History</a>
                          </div></div>     
                    <?php } else { ?>        
                      <a href="http://localhost/warehouse/main?login">Payment</a>
                      <a href="http://localhost/warehouse/main?login">In/Outbound List</a>
                    <?php } ?>
                                </div>
                            </div>
                            <?php if($is == 0){ ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/warehouse/main?login">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/warehouse/main?signup">Signup</a>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://localhost/warehouse/al_profile">Profile</a>
                                </li> 
                                <li class="nav-item">
                                    <a class="nav-link" href="al_logout.php">Logout</a>
                                </li>

                            <?php } ?>  
                        </ul>
                    </div>
                </div>
        
        </nav>
    </div>
    <div id="log">
        <div id="close" style="margin-left:950px; margin-top:10px" onclick="closeForm()">x</div>
        <?php include('account_all_login_all.php'); ?>
    </div>
    <div id="sign">
        <div id="close" style="margin-left:950px; margin-top:10px" onclick="closeForms()">x</div>
        <?php include('account_user_signup_user.php'); ?>
    </div>
    <script>
        function openForm() {
            document.getElementById("log").style.display = "block";
            document.getElementById("sign").style.display = "none";
            document.getElementById("main-content").classList.add("blur");
        }
        function closeForm() {
            document.getElementById("log").style.display = "none";
            document.getElementById("main-content").classList.remove("blur");
        }
        function openForms() {
            document.getElementById("sign").style.display = "block";
            document.getElementById("log").style.display = "none";
            document.getElementById("main-content").classList.add("blur");
        }
        function closeForms() {
            document.getElementById("sign").style.display = "none";
            document.getElementById("main-content").classList.remove("blur");
        }
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('login')) {
                openForm();
            } else if (urlParams.has('signup')) {
                openForms();
            }
        }
    
    </script>
</body>
</html>
