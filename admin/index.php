<?php 

    include 'admin-sidebar.php';
    include 'db.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/index-style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <title>EMVS</title>
    </head>
    <body>
        <div class="main-content">
                <div class="item" id="item-1"><div class="group">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                        <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
                        ></path>
                        </g>
                    </svg>
                    <input class="input" type="search" placeholder="Search" />
                    </div></div>
                <div class="item" id="item-2">
                    <h2>Election Data</h2>
                    <p>Summary</p>
                    <div class="child">
                        <div class="child-item">
                        <h3>
                            <?php 

                                $qry1 = "SELECT COUNT('voter_id') AS voters FROM voters";
                                $result = mysqli_query($conn, $qry1);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $voters = $row['voters'];

                                        if ($voters != 0) {
                                            echo $voters;
                                        } else {
                                            echo "0 :(";
                                        }
                                       
                                    }
                                }
                            
                            ?></h3>
                            <p>Number of Registered Voters</p>
                        </div>
                        <div class="child-item">
                        <h3>
                        <?php 

                            $qry2 = "SELECT COUNT('candidate_id') AS candidate FROM candidate";
                            $result = mysqli_query($conn, $qry2);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $candidates = $row['candidate'];

                                    if ($candidates != 0) {
                                        echo $candidates;
                                    } else {
                                        echo "0 :(";
                                    }
                                }
                            }

                            ?></h3>
                            <p>Number of Potential Candidates</p>
                        </div>
                        <div class="child-item"><h3>
                        <?php 

                            $qry3 = "SELECT COUNT('r_vote_id') AS r_votes FROM registered_votes";
                            $result = mysqli_query($conn, $qry3);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $r_votes = $row['r_votes'];

                                    if ($r_votes != 0) {
                                        echo $r_votes;
                                    } else {
                                        echo "0 :(";
                                    }
                                }
                            } 
                            ?></h3>
                            <p>Number of Counted Votes</p>
                        </div>
                        <div class="child-item"><h3>
                        <?php 

                            $qry4 = "SELECT COUNT('user_id') AS user FROM users";
                            $result = mysqli_query($conn, $qry4);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $user = $row['user'];

                                    if ($user != 0) {
                                        echo $user;
                                    } else {
                                        echo "0 :(";
                                    }
                                }
                            } 
                            ?></h3>
                            <p>Number of Users</p>
                        </div>
                    </div>
                </div>
                <div class="item" id="item-3">
                    <h3>Registered Voters</h3>
                    <div id="pieChart"></div>
                </div>
                <div class="item" id="item-4">
                    <h3>Registered Voters</h3>
                <div class="container">
                <div class="table-container">
                    <table class="table table-hover" id="table-hover" >
                        <thead>
                            <tr>
                                <th scope="col">Voter ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Program</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                                $query = "SELECT u.user_name, v.voter_id, v.program_code, v.vote_status FROM users u JOIN voters v ON u.user_id = v.user_id";
                                $result = mysqli_query($conn, $query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $voterid = $row['voter_id'];
                                        $username = $row['user_name'];
                                        $program = $row['program_code'];
                                        $status = $row['vote_status'];

                                        echo "<tr>";
                                        echo "<td>$voterid</td>";
                                        echo "<td>$username</td>";
                                        echo "<td>$program</td>";
                                        echo "<td>$status</td>";
                                        echo "</tr>";
                                    }
                                }            
                            ?>
                        </tbody>
                    </table>
                </div>
                </div>
                </div>
                <div class="item" id="item-5">
                    <h3>Candidates</h3>
                        <select id="positionDropdown" onchange="updateChart()">
                            <?php 

                                $query = "SELECT position_id, position_name FROM position ORDER BY position_rank";
                                $result = mysqli_query($conn, $query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $pos = $row['position_id'];
                                        $pos_name = $row['position_name'];

                                        $selected = $first ? ' selected' : '';
                                        echo "<option value='$pos'$selected>$pos_name</option>";
                                        $first = false;
                                    }
                                }
                            
                            
                            ?>
                        </select>
                    <div id="votesChart"></div>
                </div>

        </div>
        <script>
    document.addEventListener("DOMContentLoaded", function() {
      var optionsPie = {
        series: [<?php 
            $query = "SELECT COUNT('voter_grade') as grade FROM voters WHERE voter_grade = 'g11' OR voter_grade = 'g12'";
            $result = mysqli_query($conn, $query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()){
                    $grade = $row['grade'];
                    echo $grade;
                }
            }
            ?>,<?php 
            $query = "SELECT COUNT('voter_grade') as grade FROM voters WHERE voter_grade = '1st year' OR voter_grade = '2nd year' OR voter_grade = '3rd year' OR voter_grade = '4th year'";
            $result = mysqli_query($conn, $query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()){
                    $grade = $row['grade'];
                    echo $grade;
                }
            }  
            ?>],
        chart: {
          type: 'pie',
          width: 450, 
          height: 450,
        },
        labels: ['Senior High', 'Tertiary'],
        colors: ['#0079c2' , '#FFD700'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      };
      var pieChart = new ApexCharts(document.querySelector("#pieChart"), optionsPie);
      pieChart.render();

    });

    var chart;

        document.addEventListener("DOMContentLoaded", function() {
            var position = document.getElementById('positionDropdown').value;
            loadChartData(position);
        });

        function loadChartData(position) {
            console.log('Fetching data for position:', position); 
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_data.php?position=' + position, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        try {
                            var data = JSON.parse(xhr.responseText);
                            console.log('Received data:', data); 
                            if (data.names && data.sh_votes && data.ter_votes) {
                                var sh_colors = Array(data.names.length).fill('yellow');
                                var ter_colors = Array(data.names.length).fill('blue');

                                if (chart) {
                                    chart.updateOptions({
                                        xaxis: {
                                            categories: data.names
                                        },
                                        series: [
                                            {
                                                name: 'Senior High Votes',
                                                data: data.sh_votes
                                            },
                                            {
                                                name: 'Tertiary Votes',
                                                data: data.ter_votes
                                            }
                                        ],
                                        colors: ['#FFD700', '#0079c2']
                                    });
                                } else {
                                    renderChart(data.names, data.sh_votes, data.ter_votes, sh_colors, ter_colors);
                                }
                            } else {
                                console.error('Invalid data format', data);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.error('Response text:', xhr.responseText);
                        }
                    } else {
                        console.error('Error fetching data:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        function renderChart(names, sh_votes, ter_votes, sh_colors, ter_colors) {
            var options = {
            series: [
                {
                    name: 'Senior High Votes',
                    data: sh_votes
                },
                {
                    name: 'Tertiary Votes',
                    data: ter_votes
                }
            ],
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
                colors: ['#FFD700', '#0079c2'],
                stroke: {
                    width: 1,
                    colors: ['#fff']
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
                    horizontalAlign: 'center',
                    offsetX: 40
                },
            };

            chart = new ApexCharts(document.querySelector("#votesChart"), options);
            chart.render().catch(error => {
                console.error('Error rendering chart:', error);
            });
        }

        function updateChart() {
            var position = document.getElementById('positionDropdown').value;
            console.log('Dropdown changed, new position:', position); 
            loadChartData(position);
        }

        function updateStatus(button, status) {
        const td = button.parentNode;
        td.innerHTML = status;
        }


  </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>