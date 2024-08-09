document.addEventListener('DOMContentLoaded', function() {
    var options = {
        chart: {
            type: 'line',
            height: 250,
            width: '100%'
        },
        series: [{
            name: 'Total Votes',
            data: totalVotes 
        }],
        xaxis: {
            categories: dates 
        },
        title: {
            text: 'Total Votes',
            align: 'left'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});