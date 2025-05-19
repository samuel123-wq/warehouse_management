<?php
include('sambung.php');
session_start(); 

if (!isset($_SESSION['sku'])) {
    die("Session 'sku' is not set. Please ensure you have logged in correctly.");
}
$ss = $_SESSION['sku'];

$cost = 0;
$total = 0;
$totals = 0;
$percent10 = 0;
$percent6 = 0;


$sql = "SELECT * FROM payment WHERE Sku = '$ss' and Flag = 0";

$sqls = "SELECT Inbound_date, Outbound_date FROM pallet WHERE Sku = '$ss' ";

if ($sambungan->connect_error) {
    die("Connection failed: " . $sambungan->connect_error);
}

$stmt = $sambungan->prepare($sql);
if ($stmt === false) {
    die("Failed to prepare statement: " . $sambungan->error);
}

$stmt->execute(); 
$result = $stmt->get_result();

$stmts = $sambungan->prepare($sqls);
$stmts->execute(); 
$results = $stmts->get_result();

$store = array();

if ($results->num_rows > 0) {

    $rows = $results->fetch_assoc();
        $inboundDates = new DateTime($rows['Inbound_date']);

        $outboundDates = new DateTime($rows['Outbound_date']);

        $day = $inboundDates->diff($outboundDates)->days;
    
} else {
   
}


if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $store[] = $row;
    }
} else {
   
}


$stmt->close();
$sambungan->close();
$PAY=[];

$arry = count($store);
for ($i = 0; $i < $arry; $i++) {
    if (isset($store[$i]['PaymentID'])) {
        $cost += $store[$i]['Total'];               
        $paymentid = $store[$i]['PaymentID'];
        $PAY = $store[$i]['PaymentID'];
    }
    
}

$_SESSION['PAY'] = $PAY;
$percent6 = $cost * 0.06;
$total = $cost + $percent6;
$percent10 = $total * 0.10;
$totals = $percent10 + $total;

if(isset($_POST['check'])){
    $_SESSION['Price'] = $totals;
}
    $_SESSION['Price'] = $totals;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Warehouse</title>

    <style>

* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: 'Open Sans', sans-serif;
	color: #242424;
	font-weight: 600;
}

#wrapper {
	display: table;
	table-layout: fixed;
	width: 100%;
	height: 100vh;
}

.container1 {
	background-color: white;
	float: none;
	display: table-cell;
	vertical-align: middle;
	width: 33.333%;
}

.container2 {
	background-color: #102c53;
	float: none;
	display: table-cell;
	vertical-align: middle;
	width: 66.666%;
}

.order {
	width: 80%;
	height: auto;
	margin: 0 auto;
}

.order h2 {
	font-size: 1.8em;
	text-align: center;
	margin-bottom: 10%;
}

.item {
	width: 100%;
	height: auto;
	background-color: white;
	box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
	margin-bottom: 10%;
	overflow: hidden;
	position: relative;
}

.item:last-of-type {
	margin-bottom: 0;
}

.item img {
	float: left;
	margin-right: 3%;
}

.item .info {
	padding: 3%;
}

.item .quantity {
	font-size: 0.8em;
}

.item .price {
	background-color: #3FB158;
	position: absolute;
	padding: 1% 2%;
	color: white;
	bottom: 5%;
	right: 2%;
}

hr {
	border-top: 1px solid #A8A8A8;
}

.ship, .total {
	margin: 10% 0;
	text-align: right;
}

.total {
	font-size: 1.5em;
}

.checkout {
	width: 90%;
	margin: 0 auto;
}

.checkout p {
	display: inline-flex;
	flex-direction: row;
	margin-right: 4%;
}

.checkout p, .checkout i {
	color: white;
	font-size: 1.6em;
}

.checkout i {
	margin-right: 4%;
}

.checkout p:last-of-type, .checkout i:nth-of-type(3) {
	opacity: 0.5;
}

.payment {
    background-color: white;
    width: 100%;
    height: auto;
    background-image: url('file:///C:/Users/GIGABYTE/Desktop/FYP/warehouse-layout-tips.webp');
    background-repeat: no-repeat;
    background-position: right;
    background-size: 50%;
    margin-top: 3.8%;
}

.infos {
	width: 50%;
	padding: 3% 5% 0 5%;
}

.infos h2 {
	color: #102c53;
	font-size: 1.8em;
	margin-bottom: 10%;
}

.visa, .mastercard, .paypal {
	box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
	width: 25%;
	height: auto;
	background-color: white;
	cursor: pointer;
	margin-right: 5%;
	margin-bottom: 10%;
}

.paypal {
	opacity: 0.5;
	transition: 0.3s ease-in-out;
}

.mastercard:hover,
.paypal:hover, .visa:hover {
	opacity: 1;
}

.paypal {
	margin-right: 0;
}

.title {
	color: #242424 !important;
	opacity: 1 !important;
	font-size: 1em !important;
}

input, select {
	border: none;
	padding: 2%;
	box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
	margin-top: 2%;
}

input:focus,
select:focus {
	outline: none;
}

.number {
	width: 20%;
	margin-right: 5.3%;
	margin-bottom: 10%;
}

.number:last-of-type {
	margin-right: 0;
}

.cardHolderName {
	margin-bottom: 10%;
}

.cardHolderName input {
	width: 100%;
}

select {
	margin-right: 2%;
}

select:last-of-type {
	margin-right: 0;
}

.expiration, .security {
	margin-bottom: 10%;
}

.security input {
	width: 25%;
}

button {
	background-color: #102c53;
	width: 100%;
	padding: 5%;
	border: none;
	color: white;
	cursor: pointer;
	transition: 0.3s ease-in-out;
	margin-bottom: 4%;
}

button:hover {
	background-color: #0e3469;
}

.active {
    opacity: 1 !important;
}

.inactive {
    opacity: 0.5 !important;
}

    </style>

</head>

<body>
    <div id="wrapper">
        <div class="container1">
            <div class="order">
                <h2>Your order summary</h2>
                 

                <div class="item">
                    <!--<img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/1978060/balle.png' alt=''>-->
                    <div class="info">
                        <h4>Goods Details: Pencil Case</h4><br>
                        <p class="quantit">Number of day: <?php echo $day+1; ?> </p><br>
                        <p class="price-per-pallet">Price per pallet per day: RM 50.00</p><br>
                        <p class="subtotal">Subtotal: RM <?php echo number_format($cost, 2); ?></p><br>
                        <p class="tax">Tax (6%): RM <?php echo number_format($percent6, 2); ?> </p><br>
                    </div> <!-- .info -->
                </div> <!-- .item -->
                
                
                
                <h4 class="ship">Processing Fee (10%): RM <?php echo number_format($percent10, 2) ?></h4>
                <hr>
                <h3 class="total">TOTAL: RM <?php echo number_format($totals, 2) ?></h3>
            </div> <!-- .order -->
        </div> <!-- .container1 -->
        
        <div class="container2">
            <div class="checkout">
                <p><i class="fas fa-check-circle"></i></p>
                <p><i class="fas fa-check-circle"></i></p>
                <p><i class="fas fa-check-circle"></i></p>
                
                <div class="payment">
                    <div class="content">
                        <div class="infos">
                            <div class="method">
                                <h2>Choose a payment method</h2>
                                <img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/1978060/visa.png' alt='' class="visa">
                                <img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/1978060/mastercard.png' alt='' class="mastercard">
                                <a href="paymentpp.php">
                                    <img src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/1978060/paypal.png' alt='' class="paypal">
                                </a>
                            </div> <!-- .method -->
                            <div class="cardNumber">
                                <p class="title">Credit card number</p><br>
                                <input type="text" class="number" maxlength="4">
                                <input type="text" class="number" maxlength="4">
                                <input type="text" class="number" maxlength="4">
                                <input type="text" class="number" maxlength="4">							
                            </div> <!-- .cardNumber -->
                            <div class="cardHolderName">
                                <p class="title">Card holder name</p>
                                <input type="text">
                            </div> <!-- cardHolderName -->
                            <div class="expiration">
                                <p class="title">Expiration date</p>
                                <select>
                                    <option>Month</option>
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                                
                                <select>
                                    <option>Year</option>
                                    <option>2024</option>
                                    <option>2025</option>
                                    <option>2026</option>
                                    <option>2027</option>
                                    <option>2028</option>
                                    <option>2029</option>
                                </select>
                            </div> 
                            <div class="security">
                                <p class="title" >CVV</p>
                                <input type="text" maxlength="3">
                            </div>
                            <button onclick="confirmCheckout()" id="check">Checkout</button>
                            
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div>
    </div> 
                <?php
    include("loading.html");
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->

    
<!--
    <script>
        function confirmCheckout() {
          swal({
            title: "Are you sure?",
            text: "Once confirmed, you will not be able to cancel this transaction!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willConfirm) => {
            if (willConfirm) {
              swal("Your transaction has been confirmed!", {
                icon: "success",
            }).then(() => {
                window.location.href = "receipt.html"; // Redirect to receipt.htm
            });
              // Here you can add the code to perform the checkout
            } else {
              swal("Your transaction has been cancelled.");
            }
          });
        }
        </script>-->

        <script>
            function confirmCheckout() {
              const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                  confirmButton: "btn btn-success",
                  cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
              });
            
              swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "Once confirmed, you will not be able to cancel this transaction!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, confirm!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
              })
              .then((result) => {
                if (result.isConfirmed) {
                  swalWithBootstrapButtons.fire({
                    title: "Confirmed!",
                    text: "Your transaction has been confirmed! Redirecting to receipt page...",
                    icon: "success"
                    }).then(() => {
                        window.location.href = "receipt?receipt"; 

                  });
                } else if (
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your transaction has been cancelled!",
                    icon: "error"
                  });
                }
              });
            }
            </script>

    <script>

        var cardNumberInputs = document.getElementsByClassName('number');
        for (var i = 0; i < cardNumberInputs.length; i++) {
            cardNumberInputs[i].addEventListener('input', function() {
                if (this.value.length === 4) {
                    for (var j = 0; j < cardNumberInputs.length; j++) {
                        if (cardNumberInputs[j] === this) {
                            if (j < cardNumberInputs.length - 1) {
                                cardNumberInputs[j + 1].focus();
                            }
                            break;
                        }
                    }
                }
            });

            

            cardNumberInputs[i].addEventListener('keydown', function(e) {
                if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                    (e.keyCode == 65 && e.ctrlKey === true) || 
                    (e.keyCode >= 35 && e.keyCode <= 39)) {

                        return;
                }
   
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });


            

            var quantityElement = document.querySelector('.quantit');
            var daysElement = document.querySelector('.days');
            var priceElement = document.querySelector('.price-per-pallet');
            var subtotalElement = document.querySelector('.subtotal');
            var taxElement = document.querySelector('.tax');
            var processingFeeElement = document.querySelector('.ship'); 
            var totalElement = document.querySelector('.total');



            var quantity = parseFloat(quantityElement.textContent.split(': ')[1]);
            var days = parseFloat(daysElement.textContent.split(': ')[1]);
            var pricePerDay = parseFloat(priceElement.textContent.split('RM ')[1]);
            var subtotal = (quantity * pricePerDay) * days;
            subtotalElement.textContent = 'Subtotal: RM ' + subtotal.toFixed(2);
            var tax = subtotal * 0.06; 
            var processingFee = subtotal * 0.10; 
            var total = subtotal + tax + processingFee;
            taxElement.textContent = 'Tax (6%): RM ' + tax.toFixed(2);
            processingFeeElement.textContent = 'Processing Fee (10%): RM ' + processingFee.toFixed(2);
            totalElement.textContent = 'TOTAL: RM ' + total.toFixed(2);

        }

        var mastercard = document.querySelector('.mastercard');
        var visa = document.querySelector('.visa');

        var cvvInput = document.getElementsByClassName('security')[0];

        cvvInput.addEventListener('keydown', function(e) {

            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
  
                (e.keyCode == 65 && e.ctrlKey === true) || 
     
                (e.keyCode >= 35 && e.keyCode <= 39)) {
              
                    return;
            }

            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $(document).ready(function() {
            $('.number').first().keypress(function(e) {
                var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if ($(this).val().length === 0 && key != 4 && key != 5) {
                    e.preventDefault();
                }
            });
        });


         $(document).ready(function() {
            $('#checkoutButton').click(function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, confirm"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                });
            });
        });   

        

        
    </script>

</body>

</html>