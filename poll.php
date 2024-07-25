<?php 
    session_start();
    include 'sidebar.php';
    include 'db.php';
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
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <title>EMVS</title>
    </head>
    <body>
        <div class="main-content">
            <h1>Polls Page</h1>
            <div class="dropdown">
            <label for="dropdown">Select a chart title:</label>
                <select id="dropdown" onchange="updateChartTitle()">
                    <option value="PRES">President</option>
                    <option value="TERVP">Vice President</option>
                    <option value="SEC">Secretary</option>
                    <option value="TRE">Treasurer</option>
                </select>
            </div>
            <div id="chart"></div>
        </div>
        <script>
                          var chart;

document.addEventListener("DOMContentLoaded", function() {
    // Load initial data for President
    loadChartData('PRES');
});

function loadChartData(position) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_data.php?position=' + position, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.names && data.votes) {
                    if (chart) {
                        // Update existing chart with new data
                        chart.updateOptions({
                            xaxis: {
                                categories: data.names
                            },
                            series: [{
                                name: 'Votes',
                                data: data.votes
                            }]
                        });
                    } else {
                        // Create the chart for the first time
                        renderChart(data.names, data.votes);
                    }
                } else {
                    console.error('Invalid data format', data);
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', xhr.responseText);
            }
        }
    };
    xhr.send();
}

function renderChart(names, votes) {
    var options = {
        series: [{
            name: 'Votes',
            data: votes
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true
        },
        plotOptions: {
            bar: {
                horizontal: true,
                dataLabels: {
                    total: {
                        enabled: true,
                        offsetX: 0,
                        style: {
                            fontSize: '13px',
                            fontWeight: 900
                        }
                    }
                }
            }
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        title: {
            text: 'Candidate Vote Counts'
        },
        xaxis: {
            categories: names
        },
        yaxis: {
            title: {
                text: undefined
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " votes";
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
        }
    };

    chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render().catch(error => {
        console.error('Error rendering chart:', error);
    });
}

function updateChartTitle() {
    var position = document.getElementById('dropdown').value;
    loadChartData(position);
}
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>