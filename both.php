<?php
include('header.php');
if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/index.php?login");
    exit();
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'Outbound') {
        $selected_statuss = 'Outbound';
    } elseif ($_GET['status'] == 'Inbound') {
        $selected_statuss = 'Inbound';
    } else {

        $selected_statuss = 'Outbound';
    }
} else {

    $selected_statuss = 'Outbound';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Warehouse</title>
    <link rel="stylesheet" type="text/css" href="stylez.css">
    <style>
    
    
h1, .h1 {
  font-size: 2rem;
}

    </style>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('status').addEventListener('change', function() {
                this.form.submit();


            });
        });
    </script> 
    <div style="margin-top: 100px;">

        <form method="get" style="margin-left:10px;">
    <main class="table" id="customers_table" style="margin-top : 90px;">
        <section class="table__header">
        <style>

        button[type='bu'] {
            background-color: rgba(0,0,0,0);
            border: none;
            cursor: pointer;
            }
            </style>
        <button type='bu' onclick='event.preventDefault(); customBack1();'><img src='back.png' style='width: 25px; height: 25px;'></button>
        <?php if ($selected_statuss == 'Outbound') { ?>
        <h1> Request Outbound</h1>
  <?php   } elseif ($selected_statuss == 'Inbound') { ?>
                <h1> Request Inbound</h1>
        <?php } ?>
         <div class="input-group" style="margin-right:270px;">
                <input id="search" type="search" placeholder="Search Data..." style="padding-top:5px;">

            </div>
                <div class="export__file">
                    <label for="status">Type:</label>
                    <select id="status" name="status" onchange="this.form.submit()" style="background-color: #fff5;">
                        <option value="Inbound" <?php if ($selected_statuss == 'Inbound') echo 'selected'; ?>>Inbound</option>
                        <option value="Outbound" <?php if ($selected_statuss == 'Outbound') echo 'selected'; ?>>Outbound</option>
                    </select>
                </div>
        </section>

     
        <?php
        if ($selected_statuss == 'Outbound') {
    include("inoutbound_user.php");
} elseif ($selected_statuss == 'Inbound') {
    include("account_all_update_name_all.php");
}
        
        ?>
            </main>
        </form>
    </div>
</body>
</html>
<?php
//include('header.php');

?>
    <script>
        function reloadPage() {
            location.reload();
        }
        
        
        function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }

        function customBack1() {
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
    <script>
        const search = document.querySelector('#search'),
            table_rows = document.querySelectorAll('tbody tr');

        search.addEventListener('input', searchTable);

        function searchTable() {
            const search_data = search.value.toLowerCase();

            table_rows.forEach((row, i) => {
                const sku_data = row.querySelector('td:first-child').textContent.toLowerCase();
                const product_name_data = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const isMatch = sku_data.includes(search_data) || product_name_data.includes(search_data);

                row.classList.toggle('hide', !isMatch);
                row.style.setProperty('--delay', i / 25 + 's');
            });

            document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
                visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
            });
        }
    </script>