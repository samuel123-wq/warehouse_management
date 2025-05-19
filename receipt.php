<?php
include('sambung.php'); 
session_start(); 

if (!isset($_SESSION['ID']) || !isset($_SESSION['PAY'])) {
    die("User not logged in or payment ID not set");
}

$ID = $_SESSION['ID'];
$PAY = $_SESSION['PAY'];
$ss = $_SESSION['sku'];
$cost = 0;
$total = 0;
$percent10 = 0;
$percent6 = 0;
$j = 0;

$store = array(); 
$sql = "SELECT 
            payment.PaymentID,
            payment.ProductID,
            payment.Total,
            product.Product_Name,
            pallet.Sku,
            users.Fullname,
            users.Phone_Number,
            users.Company_Name,
            users.UserID,
            pallet.Inbound_Date,
            pallet.Outbound_Date,
            payment.Sku
        FROM 
            payment
        JOIN
            users ON payment.UserID = users.UserID
        JOIN 
            product ON payment.ProductID = product.ProductID
        JOIN 
            pallet ON payment.Sku = pallet.Sku
        WHERE 
            payment.Sku = ?"; 



if ($sambungan->connect_error) {
    die("Connection failed: " . $sambungan->connect_error);
}

$stmt = $sambungan->prepare($sql); 
if ($stmt === false) {
    die("Failed to prepare statement: " . $sambungan->error);
}

$stmt->bind_param("s", $ss); 
$stmt->execute(); 
$result = $stmt->get_result(); 

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $inboundDate = new DateTime($row['Inbound_Date']);
        $outboundDate = new DateTime($row['Outbound_Date']);
        $interval = $inboundDate->diff($outboundDate)->days;

        if (!is_null($row['Outbound_Date'])) {
            $j++;
        }

        $store[] = array(
            'PaymentID' => $row['PaymentID'],
            'Total' => $row['Total'],
            'Fullname' => $row['Fullname'],
            'Phone_Number' => $row['Phone_Number'],
            'Company_Name' => $row['Company_Name'],
            'UserID' => $row['UserID'],
            'Sku' => $row['Sku'],
            'ProductName' => $row['Product_Name'],
            'Inbound_Date' => $row['Inbound_Date'],
            'Outbound_Date' => $row['Outbound_Date'],
            'Days' => $interval,
        );
    }
} else {
    echo "0 results"; 
}

$stmt->close(); 

$arry = count($store);

        $cost += $store[0]['Total'];


$percent6 = sprintf("%.2f",$cost * 0.06);
$total = sprintf("%.2f",$cost + $percent6);
$percent100 = $total * 0.10;
$percent10 = sprintf("%.2f", $percent100); 
$totals = sprintf("%.2f",$percent10 + $total);

function updatePaymentFlag($conn, $PAY) {

    $sql = "UPDATE payment SET Flag = '1' WHERE PaymentID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return "Failed to prepare statement: " . $conn->error;
    }

    $stmt->bind_param("s", $PAY); 

 
    if ($stmt->execute()) {

        echo "<script>
                window.location='http://localhost/warehouse/receipt.php';
              </script>";
        exit; 
    } else {

        echo "<script>
                alert('Error Updating record: " . $conn->error . "');
                window.location='http://localhost/warehouse/receipt.php';
              </script>";
        exit; 
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == 'updateFlag') {
    echo updatePaymentFlag($sambungan, $PAY);
    exit;
}

if (isset($_GET['receipt'])) {
    echo updatePaymentFlag($sambungan, $PAY);
    exit;
}

$sambungan->close(); 
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Warehouse</title>
  <style>
    *,
    ::before,
    ::after {
      box-sizing: border-box;
      border-width: 0;
      border-style: solid;
      border-color: #e5e7eb;
    }

    ::before,
    ::after {
      --tw-content: '';
    }

    html {
      line-height: 1.5;
      -webkit-text-size-adjust: 100%;
      -moz-tab-size: 4;
      tab-size: 4;
      font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
      font-feature-settings: normal;
      font-variation-settings: normal;
    }

    body {
      margin: 0;
      line-height: inherit;
    }

    /*body {
      width: 210mm;
      height: 297mm;
      margin: 0 auto;
      background-color: white;
      box-shadow: none;
    }*/

    hr {
      height: 0;
      color: inherit;
      border-top-width: 1px;
    }

    abbr:where([title]) {
      -webkit-text-decoration: underline dotted;
      text-decoration: underline dotted;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-size: inherit;
      font-weight: inherit;
    }

    a {
      color: inherit;
      text-decoration: inherit;
    }

    b,
    strong {
      font-weight: bolder;
    }

    code,
    kbd,
    samp,
    pre {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: 1em;
    }

    small {
      font-size: 80%;
    }

    sub,
    sup {
      font-size: 75%;
      line-height: 0;
      position: relative;
      vertical-align: baseline;
    }

    sub {
      bottom: -0.25em;
    }

    sup {
      top: -0.5em;
    }

    table {
      text-indent: 0;
      border-color: inherit;
      border-collapse: collapse;
    }

    button,
    input,
    optgroup,
    select,
    textarea {
      font-family: inherit;
      font-feature-settings: inherit;
      font-variation-settings: inherit;
      font-size: 100%;
      font-weight: inherit;
      line-height: inherit;
      color: inherit;
      margin: 0;
      padding: 0;
    }

    button,
    select {
      text-transform: none;
    }

    button,
    [type='button'],
    [type='reset'],
    [type='submit'] {
      appearance: button;
      -webkit-appearance: button;
      background-color: transparent;
      background-image: none;
    }

    :-moz-focusring {
      outline: auto;
    }

    :-moz-ui-invalid {
      box-shadow: none;
    }

    progress {
      vertical-align: baseline;
    }

    ::-webkit-inner-spin-button,
    ::-webkit-outer-spin-button {
      height: auto;
    }

    [type='search'] {
      appearance: textfield;
      -webkit-appearance: textfield;
      outline-offset: -2px;
    }

    ::-webkit-search-decoration {
      -webkit-appearance: none;
    }

    ::-webkit-file-upload-button {
      -webkit-appearance: button;
      font: inherit;
    }

    summary {
      display: list-item;
    }

    blockquote,
    dl,
    dd,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    hr,
    figure,
    p,
    pre {
      margin: 0;
    }

    fieldset {
      margin: 0;
      padding: 0;
    }

    legend {
      padding: 0;
    }

    ol,
    ul,
    menu {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    dialog {
      padding: 0;
    }

    textarea {
      resize: vertical;
    }

    input::placeholder,
    textarea::placeholder {
      opacity: 1;
      color: #9ca3af;
    }

    button,
    [role="button"] {
      cursor: pointer;
    }

    :disabled {
      cursor: default;
    }

    img,
    svg,
    video,
    canvas,
    audio,
    iframe,
    embed,
    object {
      display: block;
    }

    img,
    video {
      max-width: 100%;
      height: auto;
    }

    [hidden] {
      display: none;
    }

    .fixed {
      position: fixed;
    }

    .bottom-0 {
      bottom: 0px;
    }

    .left-0 {
      left: 0px;
    }

    .table {
      display: table;
    }

    .h-12 {
      height: 3rem;
    }

    .w-1\/2 {
      width: 50%;
    }

    .w-full {
      width: 100%;
    }

    .border-collapse {
      border-collapse: collapse;
    }

    .border-spacing-0 {
      --tw-border-spacing-x: 0px;
      --tw-border-spacing-y: 0px;
      border-spacing: var(--tw-border-spacing-x) var(--tw-border-spacing-y);
    }

    .whitespace-nowrap {
      white-space: nowrap;
    }

    .border-b {
      border-bottom-width: 1px;
    }

    .border-b-2 {
      border-bottom-width: 2px;
    }

    .border-r {
      border-right-width: 1px;
    }

    .border-main {
      border-color: #5c6ac4;
    }

    .bg-main {
      background-color: #5c6ac4;
    }

    .bg-slate-100 {
      background-color: #f1f5f9;
    }

    .p-3 {
      padding: 0.75rem;
    }

    .px-14 {
      padding-left: 3.5rem;
      padding-right: 3.5rem;
    }

    .px-2 {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .py-10 {
      padding-top: 2.5rem;
      padding-bottom: 2.5rem;
    }

    .py-3 {
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
    }

    .py-4 {
      padding-top: 1rem;
      padding-bottom: 1rem;
    }

    .py-6 {
      padding-top: 1.5rem;
      padding-bottom: 1.5rem;
    }

    .pb-3 {
      padding-bottom: 0.75rem;
    }

    .pl-2 {
      padding-left: 0.5rem;
    }

    .pl-3 {
      padding-left: 0.75rem;
    }

    .pl-4 {
      padding-left: 1rem;
    }

    .pr-3 {
      padding-right: 0.75rem;
    }

    .pr-4 {
      padding-right: 1rem;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .align-top {
      vertical-align: top;
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .text-xs {
      font-size: 0.75rem;
      line-height: 1rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .italic {
      font-style: italic;
    }

    .text-main {
      color: #5c6ac4;
    }

    .text-neutral-600 {
      color: #525252;
    }

    .text-neutral-700 {
      color: #404040;
    }

    .text-slate-300 {
      color: #cbd5e1;
    }

    .text-slate-400 {
      color: #94a3b8;
    }

    .text-white {
      color: #fff;
    }

    @page {
      margin: 0;
    }

    @media print {
      body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
      }
    }

    button[type="print"] {
        background-color: #102c53; 
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        position: absolute; 
        bottom: 30px; 
        right: 50px; 
        }

    button[type="print"]:hover {
        background-color: #0e3469;
    }


    button[type="back"] {
        background-color: #102c53; 
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        transition-duration: 0.4s;
        position: absolute;
        bottom: 30px;
        right: 200px; 
        
    }

    button[type="back"]:hover {
        background-color: #0e3469;
    }
    .no-print {
      display: none;
    }
  </style>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div style="color:purple; font-size:25px;">
                    <b>
                  CRSST
                        </b>
                </div>
              </td>

              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Date</p>
                            <p class="whitespace-nowrap font-bold text-main text-right" id="dt">
                            </p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Invoice </p>
                            <p class="whitespace-nowrap font-bold text-main text-right"><?php echo $store[0]['PaymentID'] ?></p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">CRSST Warehouse</p>
                  <p>Number: 011-3099-1764</p>
                  <p>VAT: 23456789</p>
                  <p>Jalan Ayer Keroh Lama,</p>
                  <p>75450 Bukit Beruang,</p>
                  <p>Melaka</p>
                </div>
              </td>
              <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">
                  
                  <p class="font-bold">Company: <?php echo $store[0]['Company_Name'] ?></p>

                  <p>Identification Number: <?php echo $store[0]['UserID'] ?></p>

                  <p>Name: <?php echo $store[0]['Fullname'] ?></p>

                  <p>Phone Number: <?php echo $store[0]['Phone_Number'] ?></p>

                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">ID</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">SKU</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Product details</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Day</td>

              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Cost per day</td>
              <td class="border-b-2 border-main pb-3 pl-2 pr-3 text-right font-bold text-main">Subtotal</td>
            </tr>
          </thead>
          <tbody>
              
                       
            <tr>

              <td class="border-b py-3 pl-3"><?php echo $store[0]['PaymentID'] ?></td>
              <td class="border-b py-3 pl-2 text-center"><?php echo $store[0]['Sku'] ?></td>
              <td class="border-b py-3 pl-2 text-center"><?php echo $store[0]['ProductName'] ?></td>
              <?php $sstd = $store[0]['Days'] + 1; ?>
              <td class="border-b py-3 pl-2 text-center"><?php echo $sstd ?></td>
              <td class="border-b py-3 pl-2 text-right">RM 50</td>
              <td class="border-b py-3 pl-2 pr-3 text-right"><?php echo 'RM ' .$store[0]['Total']; ?></td>

            </tr>

              
              
            <tr>
              <td colspan="7">
                <table class="w-full border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td class="w-full"></td>
                      <td>
                        <table class="w-full border-collapse border-spacing-0">
                          <tbody>
                            <tr>
                              <td class="border-b p-3">
                                <div class="whitespace-nowrap text-slate-400">GST 6% total:</div>
                              </td>
                              <td class="border-b p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main"><?php echo 'RM '.$percent6 ?></div>
                              </td>
                            </tr>
                            <tr>
                              <td class="p-3">
                                <div class="whitespace-nowrap text-slate-400">Proccess Charge 10% total:</div>
                              </td>
                              <td class="p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main"><?php echo 'RM '.$percent10 ?></div>
                              </td>
                            </tr>
                            <tr>
                              <td class="bg-main p-3">
                                <div class="whitespace-nowrap font-bold text-white">Total:</div>
                              </td>
                              <td class="bg-main p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-white"><?php echo 'RM '.$totals ?></div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>


      <button id="print" type="print" onclick="generatePDF()">Print</button>

    <button id="back" type="back" onclick="window.location.href='main'">Back to Home</button>
>

        <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
          CRSST Warehouse
          <span class="text-slate-300 px-2">|</span>
          crsstwarehouse@gmail.com
          <span class="text-slate-300 px-2">|</span>
          +6011-3099-1764
        </footer>
      </div>
    </div>
                    <?php
    include("loading.html");
    ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    var today = new Date();
    var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
    document.getElementById('dt').innerHTML = date; 

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            openForm();
        } 
    }   
    
    function openForm() {
        fetch('receipt.php?ajax=updateFlag')
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Success') {
                    console.log('Payment successfully');
                    window.print();
                } else {
                    console.error('Error updating payment flag:', data);
                }
            })
            .catch(error => console.error('Error:', error));
    }         
</script>
  <script>
    function generatePDF() {
      var printButton = document.getElementById('print');
      var backButton = document.getElementById('back');
      printButton.style.display ='none';
      backButton.style.display ='none';
      
      var { jsPDF } = window.jspdf;
      var doc = new jsPDF({
        orientation: 'landscape',
        unit: 'pt',
        format: 'a4'
      });
      
      doc.html(document.body, {
        callback: function (doc) {
          printButton.style.display = 'block';
         backButton.style.display = 'block';
          doc.save("invoice.pdf");
        },
        x: 10,
        y: 10,
        width: 800,
        windowWidth: 1500
      });
    }
  </script>

    

</body>

</html>