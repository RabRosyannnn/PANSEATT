<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> <!-- Link to the external CSS file -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">  
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<aside>
    <ul class="nav flex-column">
        
        <li class="nav-item">
            <a class="nav-link" href="#calendar-section">Calendar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bundles-section">Bundles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#request-section">Request</a>
        </li>
        @if(Auth::user()->position === 'admin') <!-- Show staff link only for admin -->
        <li class="nav-item">
            <a class="nav-link" href="#staff-section">Staff</a>
        </li>
        @endif
    </ul>
</aside>

<div class="content">
    <div class="header">
        <div>
            <h2 class="header-title">PansEat Tagapo</h2> 
        </div>
        <div>
            <span class ="username">{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="logout-icon-btn">
        <i class="fas fa-sign-out-alt logout-icon"></i>
    </button>
</form>
        </div>
    </div>

    <div class="content">
    <!-- Error Messages -->
    @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(Auth::user()->position === 'admin') <!-- Show staff section only for admin -->
        <div class="section" id="completed-reservations-section">
        
    <div class="completed-reservations-card text-center mb-4" style="background-color: #E07A5F; color: #FFD166;">
        <div class="generate-report text-center mb-4">
        <form method="POST" action="{{ route('generate.report') }}">
    @csrf
    <input type="hidden" name="chartImage" id="chartImage">
    <button type="submit" class="btn btn-primary">Generate Report</button>
</form>
    </div>
        <div class="completed-reservations-card-body d-flex">
            <!-- Left Section (30%) -->
            <div class="completed-count-section" style="flex: 0 0 30%; background-color: #FAF3DD; padding: 20px; border-radius: 10px; margin-right: 10px;">
                <h5 class="completed-card-title">Completed Reservations</h5>
                <h2 class="completed-card-text">{{ $completedReservations }}</h2>
            </div>
            <!-- Right Section (70%) -->
            <div class="completed-graph-section" style="flex: 0 0 70%; background-color: #FAF3DD; padding: 20px; border-radius: 10px;">
                <canvas id="completedReservationsChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
<div class="section" id="calendar-section">
    <button class="btn btn-primary" data-toggle="modal" data-target="#addReservationModal">Add Reservation</button>
    <div id="reservationCalendar"></div>
</div>
    

            
    
<!-- Modal for Adding Reservation -->
<!-- Modal for Adding Reservation -->
<!-- Modal for Adding Reservation -->
<div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReservationModalLabel">Add New Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reservationForm" method="POST">
    @csrf
                <div class="modal-body">
                <div id="errorMessages"></div>
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
    <label for="contact_number">Contact Number</label>
    <input 
        type="text" 
        class="form-control" 
        id="contact_information" 
        name="contact_information" 
        required 
        maxlength="11" 
        pattern="^09\d{9}$" 
        title="Contact number must start with '09' and be exactly 11 digits."
        placeholder="(e.g., 09123456789)">
</div>
<div class="form-group">
    <label for="date">Date</label>
    <input type="date" class="form-control" id="date" name="date" required>
</div>
                            <div class="form-group">
                                <label for="number_of_guests">Number of Guests</label>
                                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" required min="1">
                            </div>
                            <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" required>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                        <div class="form-group">
    <label for="booking_confirmation">Booking Status</label>
    <select name="booking_confirmation" id="booking_confirmation" class="form-control">
        <option value="processing" {{ old('booking_confirmation', 'processing') == 'processing' ? 'selected' : '' }}>Processing</option>
        <option value="confirmed" {{ old('booking_confirmation') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="canceled" {{ old('booking_confirmation') == 'canceled' ? 'selected' : '' }}>Canceled</option>
    </select>
</div>

                            <div class="form-group">
                                <label for="deposit">Deposit</label>
                                <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="occasion">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion" required>
                            </div>
                            <div class="form-group">
                                <label for="bundles">Select Bundles</label>
                                <div>
                                    @foreach($activeBundles as $bundle)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bundle_{{ $bundle->id }}" name="bundles[]" value="{{ $bundle->id }}">
                                            <label class="form-check-label" for="bundle_{{ $bundle->id }}">
                                                {{ $bundle->name }} - ${{ number_format($bundle->price, 2) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Note Input -->
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter additional notes here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>


        

        <div class="section" id="bundles-section">
            <div class ="bundle-header">
    <h4>Bundles</h4>
    <button class="btn btn-secondary mb-3" id="toggleArchivedBtn">Show Archived Bundles</button>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addBundleModal">+ Add Bundle</button></div>

    <div id="activeBundles" class="row">
        @foreach ($activeBundles as $bundle)
            <div class="col-md-4 mb-3 d-flex justify-content-center">
                <div class="card text-center bundle-card">
                    @if ($bundle->image)
                        <img src="{{ asset('storage/' . $bundle->image) }}" 
                             class="card-img-top mx-auto d-block bundle-img" 
                             alt="{{ $bundle->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $bundle->name }}</h5>
                        <p class="card-text">{{ $bundle->desc }}</p>
                        <div class="card-buttons">
                            <a href="{{ route('bundles.edit', $bundle) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('bundles.archive', $bundle) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">Archive</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="archivedBundles" class="row" style="display:none;">
        @foreach($archivedBundles as $bundle)
            <div class="col-md-4 mb-3 d-flex justify-content-center">
                <div class="card bundle-card">
                    @if($bundle->image)
                        <img src="{{ asset('storage/' . $bundle->image) }}" class="card-img-top mx-auto d-block bundle-img" alt="{{ $bundle->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $bundle->name }}</h5>
                        <p class="card-text">{{ $bundle->desc }}</p>
                        <div class="card-buttons">
                            <form action="{{ route('bundles.restore', $bundle) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<div class="section" id="request-section">
    <div class="request-log">
        <div class="log-header">
            <h4>Requests</h4>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="log-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Action</th>
                        <th>Message</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modelRequests as $request)
                        <tr>
                            <td>{{ $request->tracking_id }}</td>
                            <td>{{ $request->action }}</td>
                            <td>{{ $request->message }}</td>
                            <td>
                                <form action="{{ route('requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                
                                <form action="{{ route('requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>


        @if(Auth::user()->position === 'admin') <!-- Show staff section only for admin -->
        <div class="section" id="staff-section">
    <div class="staff-log">
        <div class="log-header">
            <h4>Staff Logs</h4>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="log-table">
            <table>
                <thead>
                    <tr>
                    <th>Staff ID</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffLogs as $log)
                    <tr>
                    <td>{{ $log->user_id }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->description}}</td>
                            <td>{{ $log->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $staffLogs->links('pagination::simple-bootstrap-4') }}
    </div>

    <div class="staff-list">
        <div class="list-header">
            <h4>Staff List</h4>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <button class="add-staff-btn" data-toggle="modal" data-target="#addStaffModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="staff-cards">
            @foreach($activeStaff as $staff)
            <div class="staff-card">
            <h5 class="staff-id">ID: {{ $staff->id }}</h5>
                <div class="staff-icon"></div>
                
                <h5>{{ $staff->name }}</h5>
                <div class="staff-actions">
            
            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-edit">EDIT</a>
            @section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Edit Staff Member</h2>
            <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $staff->name) }}" 
                        class="form-control" 
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $staff->email) }}" 
                        class="form-control" 
                        required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
            <form action="{{ route('staff.archive', $staff->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-archive">ARCHIVE</button>
</form>

        </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

    
</div>
@endif
</div>

<!-- Modal for Adding Bundle -->
<div class="modal fade" id="addBundleModal" tabindex="-1" role="dialog" aria-labelledby="addBundleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBundleModalLabel">Add New Bundle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addBundleForm" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="name">Bundle Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="desc">Description</label>
            <textarea class="form-control" id="desc" name="desc" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="image">Bundle Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Bundle</button>
    </div>
</form>

        </div>
    </div>
</div>
<!-- Modal for Adding Staff -->
<div class="modal fade" id="addStaffModal" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStaffModalLabel">Add New Staff Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            </div>
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Position -->
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select class="form-control" id="position" name="position" required>
                        <option value="staff">Staff</option>    
                        <option value="admin">Admin</option>
                            
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('toggleArchivedBtn').addEventListener('click', function() {
        const activeBundles = document.getElementById('activeBundles');
        const archivedBundles = document.getElementById('archivedBundles');
        const toggleBtn = this;

        if (archivedBundles.style.display === 'none') {
            archivedBundles.style.display = 'block';
            activeBundles.style.display = 'none';
            toggleBtn.textContent = 'Show Active Bundles';
        } else {
            archivedBundles.style.display = 'none';
            activeBundles.style.display = 'block';
            toggleBtn.textContent = 'Show Archived Bundles';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('reservationCalendar');

    if (!calendarEl) {
        console.error('Element with id "reservationCalendar" not found.');
        return;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route('events.get') }}', // Ensure this route returns the correct JSON format
        eventClick: function (info) {
            // Prevent default browser behavior
            info.jsEvent.preventDefault();

            // Redirect to the reservation details page
            const reservationId = info.event.id;
            window.location.href = `/reservations/${reservationId}`;
        },
        eventContent: function (info) {
            const startTime = new Date(info.event.start).toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            const endTime = new Date(info.event.end).toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Create custom content with event name and time range
            const customContent = document.createElement('div');
            customContent.className = 'custom-event-content'; // Add class for styling
            customContent.innerHTML = `
                <div>${info.event.title}
                ${startTime} - ${endTime}</div>
            `;
            return { domNodes: [customContent] };
        }
    });

    calendar.render();
}); 

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.section');

        function changeActiveLink() {
            let index = sections.length;

            while (--index && window.scrollY + 150 < sections[index].offsetTop) {}

            navLinks.forEach((link) => link.classList.remove('active'));
            navLinks[index].classList.add('active');
        }

        changeActiveLink();
        window.addEventListener('scroll', changeActiveLink);
    });
</script>
<script>
    document.querySelectorAll('.btn-success, .btn-danger').forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to perform this action?')) {
                event.preventDefault();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('completedReservationsChart').getContext('2d');
    const monthlyCounts = @json($monthlyCounts);

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Completed Reservations',
                data: monthlyCounts,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                borderColor: '#FFD166',
                borderWidth: 1,
                barPercentage: 0.8
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        color: '#FFD166',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#FFD166'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Function to export chart to image
    function exportChartToImage() {
        const chartImage = chart.toBase64Image(); // Get the chart image as a base64 string
        document.getElementById('chartImage').value = chartImage; // Set the hidden input value
    }

    // Call the export function after the chart is rendered
    chart.render();
    exportChartToImage();
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const reservationForm = document.getElementById('reservationForm');

    reservationForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Create FormData object
        const formData = new FormData(reservationForm);

        // Send the form data to the server
        fetch("{{ route('reservations.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, // Ensure CSRF token is sent
            },
            body: formData
        })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            if (data.success) {
                alert('Reservation saved successfully!');
                $('#addReservationModal').modal('hide'); // Close modal
                reservationForm.reset(); // Reset form fields
            } else {
                alert(data.message || 'Failed to save reservation');
            }
        })
        .catch(error => {
            console.error('There was an error:', error);
            alert('An error occurred while saving the reservation.');
        });
    });
});



    document.addEventListener('DOMContentLoaded', function () {
    const addBundleForm = document.getElementById('addBundleForm');

    addBundleForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Prepare form data
        const formData = new FormData(addBundleForm);

        // Send AJAX request
        fetch("{{ route('bundles.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bundle added successfully!');
                $('#addBundleModal').modal('hide'); // Hide modal
                addBundleForm.reset(); // Reset form fields
                // Optionally reload or update the bundles list dynamically
            } else {
                alert('Failed to add bundle: ' + (data.message || 'Unknown error occurred.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const contactInformationInput = document.getElementById('contact_information');

    contactInformationInput.addEventListener('input', function (e) {
        let value = this.value;

        // If the first character isn't 0, reset the input
        if (value.length === 1 && value !== '0') {
            this.value = ''; // Reset the input if the first character isn't '0'
        }

        // If the second character isn't 9, reset the input to '09'
        if (value.length === 2 && value !== '09') {
            this.value = '0'; // Reset to '09' if it's not '09'
        }

        // Allow only numeric characters after the first two digits
        if (value.length > 2) {
            this.value = value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        }
    });
});
document.addEventListener("DOMContentLoaded", function() {
    // Get the current date
    var today = new Date();
    
    // Format the date in yyyy-mm-dd format (for input type="date")
    var year = today.getFullYear();
    var month = ("0" + (today.getMonth() + 1)).slice(-2); // Add leading zero for single-digit months
    var day = ("0" + today.getDate()).slice(-2); // Add leading zero for single-digit days
    var formattedDate = year + "-" + month + "-" + day;

    // Set the min attribute of the date input to today's date
    var dateInput = document.getElementById("date");
    dateInput.setAttribute("min", formattedDate);
});
document.addEventListener("DOMContentLoaded", function() {
    // Get the current date and time
    var now = new Date();
    
    // Format the current time in HH:mm format (24-hour format for input type="time")
    var hours = ("0" + now.getHours()).slice(-2);
    var minutes = ("0" + now.getMinutes()).slice(-2);
    var currentTime = hours + ":" + minutes;

    // Get the date input and time inputs
    var dateInput = document.getElementById("date");
    var startTimeInput = document.getElementById("start_time");
    var endTimeInput = document.getElementById("end_time");

    // Initially disable the time inputs
    startTimeInput.disabled = true;
    endTimeInput.disabled = true;

    // Event listener for when the date is changed
    dateInput.addEventListener("change", function() {
        var selectedDate = new Date(dateInput.value);

        // Enable time inputs only if a valid date is selected
        if (dateInput.value) {
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
            
            // Set the minimum time for both start and end time inputs based on the selected date
            if (selectedDate.toDateString() === now.toDateString()) {
                // If the selected date is today, set min time to current time
                startTimeInput.setAttribute("min", currentTime);
                endTimeInput.setAttribute("min", currentTime);
            } else {
                // Otherwise, set min time to 00:00 (midnight) for future dates
                startTimeInput.setAttribute("min", "00:00");
                endTimeInput.setAttribute("min", "00:00");
            }
        } else {
            // Disable the time inputs if no date is selected
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
        }
    });
});

</script>

</body>
</html>