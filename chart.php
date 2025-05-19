        <?php
        include('sambung.php');
session_start();
if (!isset($_SESSION['ID']) || !isset($_SESSION['Name']) || !isset($_SESSION['Status'])) {
    header("Location: http://localhost/warehouse/main?login");
    exit();
}

        if($_SESSION['Status'] != 'admin' ){
    echo "<script>window.location.href = 'http://localhost/warehouse/main';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Warehouse</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>    
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: white;
            z-index: 1000;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        main {
            margin-top: 120px; 
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        .select-box, .print-button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            cursor: pointer;
        }
        .select-box {
            width: 200px;
            margin-right: 10px;
        }
        .print-button {
            background-color: teal;
            color: white;
            border: none;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }
        .print-button:active {
            box-shadow: none;
        }
        .chart-container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        canvas {
            max-width: 600px;
            width: 100%;
        }
        @media print {
            header {
                display: none;
            }
            main {
                margin-top: 0;
            }
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
        
    .title-container {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        margin-left: 350px;
    }
    </style>
</head>
<body>
    <header>
        <?php include("header.php"); ?>
        
    </header>
    <main>
        
        <?php

        $years = "SELECT YEAR(MIN(Inbound_date)) AS min_year FROM pallet";
        $stmts = $sambungan->prepare($years);
        $stmts->execute();
        $result = $stmts->get_result()->fetch_assoc();
        $min_year = $result['min_year'];     
        
        $status = date("Y");
        $statuz = "Inbound_date";
        $months = array(
            "January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"
        );
        $count = array_fill(0, 12, 0);
        $count1 = array_fill(0, 12, 0);
        $count2 = array_fill(0, 12, 0);
        $count3 = array_fill(0, 12, 0);
        $total = array_fill(0, 12, 0);

        if (isset($_GET['status'])) {
            $status = intval($_GET['status']);
            $statuz = $_GET['statuz'];


            $conn = new mysqli("localhost", "root", "", "warehouse");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $queries = [
                "SELECT MONTHNAME($statuz) AS month, COUNT(*) AS count 
                 FROM pallet 
                 WHERE YEAR($statuz) = ? 
                 GROUP BY MONTHNAME($statuz)" => &$count,

                "SELECT MONTHNAME(Outbound_Date) AS month, COUNT(*) AS count 
                 FROM pallet 
                 WHERE YEAR(Outbound_Date) = ? 
                 GROUP BY MONTHNAME(Outbound_Date)" => &$count2,

                "SELECT MONTHNAME(Date) AS month, COUNT(*) AS count 
                 FROM users 
                 WHERE YEAR(Date) = ? 
                 GROUP BY MONTHNAME(Date)" => &$count3,

                "SELECT MONTHNAME(Date) AS month, SUM(Totaltax) AS total, COUNT(*) AS count 
                 FROM payment 
                 WHERE YEAR(Date) = ? 
                 GROUP BY MONTHNAME(Date)" => &$count1
            ];

            foreach ($queries as $query => &$resultArray) {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $status);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $monthIndex = array_search($row['month'], $months);
                    if ($monthIndex !== false) {
                        if (isset($row['total'])) {
                            $total[$monthIndex] = $row['total'];
                        }
                        $resultArray[$monthIndex] = $row['count'];
                    }
                }
                $stmt->close();
            }

            $conn->close();
        }
        ?>
        <form method="get">
            <div class="title-container">
            <button type='bu' onclick='event.preventDefault(); customBack20();'><img src='back.png' style='width: 25px; height: 25px; margin-left:130px;'></button>
            <h1>CRSST Warehouse Data Analysis</h1>
            </div>
            <label for="status">Year:</label>
            <select name="status" id="status" class="select-box" onchange="this.form.submit()">
                <option value="-" <?php echo (!isset($_GET['status']) || $status == '-') ? 'selected' : ''; ?>>-</option>
                <?php for ($year = $min_year; $year <= date("Y"); $year++): ?>
                    <option value="<?php echo $year; ?>" <?php echo $status == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                <?php endfor; ?>
            </select>

            <input type="hidden" name="statuz" value="<?php echo $statuz; ?>">
            <button type="button" class="print-button" id="print">Download</button>
          
        </form>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="cChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="dChart"></canvas>
        </div>
    </main>

    <script>
        const months = <?php echo json_encode($months); ?>;
        const count = <?php echo json_encode($count); ?>;
        const count2 = <?php echo json_encode($count2); ?>;
        const count1 = <?php echo json_encode($count1); ?>;
        const count3 = <?php echo json_encode($count3); ?>;
        const total = <?php echo json_encode($total); ?>;
        const status = <?php echo json_encode($status); ?>;

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Outbound Count",
                    backgroundColor: 'blue',
                    data: count2
                },{
                    label: "Inbound Count",
                    backgroundColor: 'red',
                    data: count
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Total Inbound and Outbound (Year " + status + ")"
                },
                scales: {
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        });

        new Chart("cChart", {
            type: "line",
            data: {
                labels: months,
                datasets: [{
                    label: "Total RM",
                    backgroundColor: 'red',
                    data: total
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Total Payment (Year " + status + ")"
                }
            }
        });

        new Chart("dChart", {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Total Signup",
                    backgroundColor: 'green',
                    data: count3
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Total Signup (Year " + status + ")"
                }
            }
        });

document.getElementById('print').addEventListener("click", function() {
    var status = <?php echo json_encode($status); ?>;
    const chart1Image = document.getElementById('myChart').toDataURL('image/png');
    const chart2Image = document.getElementById('cChart').toDataURL('image/png');
    const chart3Image = document.getElementById('dChart').toDataURL('image/png');
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const text = "CRSST Warehouse "+status+" Data Analysis";
    const textWidth = doc.getStringUnitWidth(text) * doc.internal.getFontSize() / doc.internal.scaleFactor;
    const xOffset = (doc.internal.pageSize.getWidth() - textWidth) / 2;
    doc.text(text, xOffset, 10); // Center-align the text
    doc.addImage(chart1Image, 'PNG', 10, 20, 180, 70);
    doc.addImage(chart2Image, 'PNG', 10, 110, 180, 70);
    doc.addImage(chart3Image, 'PNG', 10, 210, 180, 70);

    doc.save(+status+'_report.pdf');
    
});
        
        
function storeCurrentPage() {
            const currentUrl = window.location.href;
            let pageHistory = JSON.parse(sessionStorage.getItem('pageHistory')) || [];

            if (pageHistory.length === 0 || getBaseUrl(pageHistory[pageHistory.length - 1]) !== getBaseUrl(currentUrl)) {
                pageHistory.push(currentUrl);
                sessionStorage.setItem('pageHistory', JSON.stringify(pageHistory));
            }
        }
        function customBack20() {
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


</body>
</html>
