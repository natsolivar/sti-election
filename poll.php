<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/poll_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
        <title>EMVS</title>
    </head>
    <body>
        <div class="main-content">
            <h1>Polls Page</h1>
            <div class="dropdown">
            <label for="dropdown">Select a chart title:</label>
                <select id="dropdown" onchange="updateChartTitle()">
                    <option value="President">President</option>
                    <option value="VPresident">Vice President</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                </select>
            </div>
            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
        </div>
        <script>
            
        window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "QOS - Survey Result"
            },
            axisY:{
                title: "Response values",
                includeZero: true,
                interval: 10
            },
            toolTip: {
                shared: true
            },
            data: [{
                    type: "bar",
                    name: "Avg. Score",
                    toolTipContent: "<b>{label}</b> <br> <span style=\"color:#4F81BC\">{name}</span>: {y}",
                    dataPoints: [
                        { y: 94, label: "Order Accuracy" },
                        { y: 74, label: "Packaging" },
                        { y: 80, label: "Quantity" },
                        { y: 88, label: "Quality" },
                        { y: 76, label: "Delivery" }
                    ]
            }]
        });
        chart.render();

        }
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>