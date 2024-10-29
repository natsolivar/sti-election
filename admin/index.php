<?php 

    include 'admin-sidebar.php';
    include 'db.php';

    $query_years = "SELECT program_code, voter_grade, COUNT(program_code) as program_count 
                FROM voters 
                WHERE program_code IN ('BSIS', 'BSTM') 
                GROUP BY program_code, voter_grade";
    $result_years = mysqli_query($conn, $query_years);

    $bsis_counts = [0, 0, 0, 0]; // 1st, 2nd, 3rd, 4th year for BSIS
    $bstm_counts = [0, 0, 0, 0]; // 1st, 2nd, 3rd, 4th year for BSTM

if ($result_years->num_rows > 0) {
    while ($row = $result_years->fetch_assoc()) {
        if ($row['program_code'] == 'BSIS') {
            if ($row['voter_grade'] == '1st year') {
                $bsis_counts[0] = $row['program_count'];
            } elseif ($row['voter_grade'] == '2nd year') {
                $bsis_counts[1] = $row['program_count'];
            } elseif ($row['voter_grade'] == '3rd year') {
                $bsis_counts[2] = $row['program_count'];
            } elseif ($row['voter_grade'] == '4th year') {
                $bsis_counts[3] = $row['program_count'];
            }
        } elseif ($row['program_code'] == 'BSTM') {
            if ($row['voter_grade'] == '1st year') {
                $bstm_counts[0] = $row['program_count'];
            } elseif ($row['voter_grade'] == '2nd year') {
                $bstm_counts[1] = $row['program_count'];
            } elseif ($row['voter_grade'] == '3rd year') {
                $bstm_counts[2] = $row['program_count'];
            } elseif ($row['voter_grade'] == '4th year') {
                $bstm_counts[3] = $row['program_count'];
            }
        }
    }
}

        $query_grades = "SELECT program_code, voter_grade, COUNT(program_code) as strand_count 
                        FROM voters 
                        WHERE program_code IN ('HUMMS', 'ABM', 'CUART', 'MAWD', 'STEM') 
                        AND voter_grade IN ('g11', 'g12')
                        GROUP BY program_code, voter_grade";
        $result_grades = mysqli_query($conn, $query_grades);

        $g11_counts = [0, 0, 0, 0, 0]; // HUMMS, ABM, CUART, MAWD, STEM for Grade 11
        $g12_counts = [0, 0, 0, 0, 0]; // HUMMS, ABM, CUART, MAWD, STEM for Grade 12

        if ($result_grades->num_rows > 0) {
            while ($row = $result_grades->fetch_assoc()) {
                if ($row['voter_grade'] == 'g11') {
                    if ($row['program_code'] == 'HUMMS') {
                        $g11_counts[0] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'ABM') {
                        $g11_counts[1] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'CUART') {
                        $g11_counts[2] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'MAWD') {
                        $g11_counts[3] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'STEM') {
                        $g11_counts[4] = $row['strand_count'];
                    }
                } elseif ($row['voter_grade'] == 'g12') {
                    if ($row['program_code'] == 'HUMMS') {
                        $g12_counts[0] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'ABM') {
                        $g12_counts[1] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'CUART') {
                        $g12_counts[2] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'MAWD') {
                        $g12_counts[3] = $row['strand_count'];
                    } elseif ($row['program_code'] == 'STEM') {
                        $g12_counts[4] = $row['strand_count'];
                    }
                }
            }
        }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/index-style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>EMVS</title>
    </head>
    <body>
        <div class="main-content">
                <div class="item1" id="item-1"><div class="group">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                        <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
                        ></path>
                        </g>
                    </svg>
                    <input type="text" class="input" type="search" placeholder="Search" />
                    <div id="results"></div>
                </div></div>
                <div class="item" id="item-2">
                    <h2>Election Overview</h2>
                    <p>Summary</p>
                    <div class="child">
                        <div class="child-item">
                        <h3>
                        <i class='bx bxs-user-detail'></i>
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
                        <i class='bx bxs-user-plus'></i>
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
                        <div class="child-item">
                        <h3>
                        <i class='bx bxs-been-here'></i>
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
                        <div class="child-item">
                        <h3>
                        <i class='bx bxs-user-check'></i>
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
                <div class="item" id="item-2">
                    <H3>Vote Monitoring</H3>
                    <div id="chart"></div>
                    <?php include 'e-date_data.php'; ?>
                    <script src="chart.js"></script>
                </div>
                <div class="item" id="item-3">
                    <h3>Tertiary Voters</h3>
                    <canvas id="yearRadarChart"></canvas>
                </div>
                <div class="item" id="item-4">
                    <h3>Senior-High Voters</h3>
                    <canvas id="gradeRadarChart"></canvas>
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
        const bsisCounts = <?php echo json_encode($bsis_counts); ?>;
        const bstmCounts = <?php echo json_encode($bstm_counts); ?>;

        const yearCtx = document.getElementById('yearRadarChart').getContext('2d');
        const yearRadarChart = new Chart(yearCtx, {
            type: 'radar',
            data: {
                labels: ['1st Year', '2nd Year', '3rd Year', '4th Year'],
                datasets: [
                    {
                        label: 'BSIS',
                        data: bsisCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'BSTM',
                        data: bstmCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    r: {
                        beginAtZero: true
                    }
                }
            }
        });

        const g11Counts = <?php echo json_encode($g11_counts); ?>;
        const g12Counts = <?php echo json_encode($g12_counts); ?>;

        const gradeCtx = document.getElementById('gradeRadarChart').getContext('2d');
        const gradeRadarChart = new Chart(gradeCtx, {
            type: 'radar',
            data: {
                labels: ['HUMMS', 'ABM', 'CUART', 'MAWD', 'STEM'],
                datasets: [
                    {
                        label: 'Grade 11',
                        data: g11Counts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Grade 12',
                        data: g12Counts,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    r: {
                        beginAtZero: true
                    }
                }
            }
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
                    categories: names,
                    labels: {
                        formatter: function (val) {
                        return val
                        }
                    }
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