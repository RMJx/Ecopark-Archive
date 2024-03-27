<?php 
echo "<div class='carlist-parent'>";
echo "<div class='carlist-child'>";
$rows3 = array();
for ($x = 0; $x <= 30; $x++) {
    $rows3[$x] = '{date:"' . date('d/m/Y', strtotime('today - ' . $x . 'days')) . '"},';
  } 
  
?>
<!DOCTYPE html>
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<body>
    <canvas id="myChart" style="width:100%;max-width:800px"></canvas>
    <script type="text/javascript">
    const ctx = document.getElementById("myChart").getContext("2d");

    let data = [];

    function displayData() {
        var datesParked = [];
        var counter = 0;
        var hours = 0;
        datesParked = (JSON.stringify(data, null, 2));
        console.log(datesParked);
        const xValues = [<?php for ($x = 0; $x <= 30; $x++) echo $rows3[$x]; ?>];

        const yValues = [{
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
            {
                attendance: "0"
            },
        ];
        for (var i = 0; i < data.length; i++) {
            hours = hours + (data[i].outTime - data[i].inTime);
            dateConvert = new Date(data[i].inTime * 1000).toLocaleDateString("en-GB");
            for (var k = 0; k < 30; k++) {
                if (xValues[k].date == String(dateConvert)) {
                    yValues[k].attendance = 1;
                    counter++;
                }
            }
        }
        document.getElementById("statistic").innerHTML = "Over the last 30 days you have parked " + counter + " times.";
        document.getElementById("statistic2").innerHTML = "You spent an average of " + Math.round(((hours/3600)/counter) * 100) / 100 + " hours parked per parking session.";
        const x = xValues.map(item => item.date);
        const y = yValues.map(item => item.attendance);
        var yLabels = {
            0: 'Not Parked',
            1: 'Parked'
        };


        new Chart(ctx, {
            type: "line",
            data: {
                labels: x,
                datasets: [{
                    label: "30 days Parking History for " + "<?php echo $usernamegraph; ?>",
                    backgroundColor: "rgba(140, 20, 252, 1)",
                    data: y,
                    fill: true,
                }, ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                // for a value (tick) equals to 8
                                return yLabels[value];
                                // 'junior-dev' will be returned instead and displayed on your chart
                            }
                        }
                    }]
                }
            },
        });
    }

    async function APIGetData() {
        const response = await fetch(
            "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" + "<?php echo $usernamegraph; ?>" + "&function=parkinghistoryuser&API-KEY=" + "<?php echo $APIKey; ?>");
        data = await response.json();
        displayData();
    }

    APIGetData();
    </script>
    <p id="statistic"></p>
    <p id="statistic2"></p>
</body>

</html>
<?php echo "</div>";
echo "<br>";
echo "</div>"; ?>