<!DOCTYPE html>
<html>
<head>
    <title>Generar Gráfico</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Paquete', 'Total Reservas'],
                ['Andes', 2],
                ['Paquete 13', 1],
                ['DF', 1],
                ['Paquete 3', 0],
                ['SAD', 0],
                ['SDA', 0]
            ]);

            var options = {
                title: 'Paquetes Más Reservados',
                pieHole: 0.4
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
            
            // Captura el gráfico como una imagen en Base64
            var chartImage = document.getElementById('piechart').toDataURL('image/png');
            document.getElementById('chartImage').value = chartImage;
            document.getElementById('chartForm').submit();
        }
    </script>
</head>
<body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <form id="chartForm" method="POST" action="./guardar_grafico.php">
        <input type="hidden" id="chartImage" name="chartImage" />
    </form>
</body>
</html>
