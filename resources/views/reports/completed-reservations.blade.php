<!DOCTYPE html>
<html>
<head>
    <title>Completed Reservations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            padding: 10px;
        }
        .chart {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Completed Reservations Report</h1>
        <p>Date: {{ now()->toFormattedDateString() }}</p>
    </div>
    <div class="content">
        <p>Total Completed Reservations: {{ $completedReservations }}</p>
    </div>
    <div class="chart">
        @if($chartImage)
            <img src="{{ $chartImage }}" alt="Completed Reservations Chart" style="width: 100%; max-width: 600px;">
        @else
            <p>Chart not available</p>
        @endif
    </div>
</body>
</html>