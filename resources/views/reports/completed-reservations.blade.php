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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Completed Reservations Report</h1>
        <p>Date: {{ now()->toFormattedDateString() }}</p>
    </div>

    <div class="content">
        <p>Total Completed Reservations: {{ $completedReservations->count() }}</p>

        <!-- Display Completed Reservations in a Table -->
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Customer Name</th>
                    <th>Reservation Date</th>
                    <th>Time</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($completedReservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->customer_name }}</td>
                        <td>{{ $reservation->date}}</td>
                        <td>{{ $reservation->start_time}} - {{ $reservation->end_time}}</td> <!-- For End Time with Date -->
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
</body>
</html>
