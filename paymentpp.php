<?php
include('sambung.php'); 
session_start();

if(!isset($_SESSION['Price']) || !isset($_SESSION['ID'])){
    die("Session data not found. Please try again.");
}

$payment = $_SESSION['Price'];
$ID = $_SESSION['ID'];



$sambungan->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Warehouse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .payment-container {
            box-shadow: 0px 0px 25px 0px rgba(0,0,0,0.1);
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Complete Your Purchase</h2>
        <div id="paypal-button-container"></div>
    </div>
                <?php
    include("loading.html");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=ASrz86rh2J0BiH1LEzFKfB3SuB8eWYZrCez20I_gmY2MLn0rCr1vqa4sesa6tJxY4Hp3Z7hg2GIdOtAo&currency=MYR"></script>

    <script>
        const myrAmount = <?php echo json_encode($payment); ?>;

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: myrAmount.toFixed(2),
                            currency_code: 'MYR'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // SweetAlert
                    Swal.fire(
                        'Transaction Completed',
                        'Transaction completed by ' + details.payer.name.given_name,
                        'success'
                    ).then(() => {
                        window.location.href = "receipt.php?receipt";
                    });
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
