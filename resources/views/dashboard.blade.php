<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> <!-- Link to the external CSS file -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">


    
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
            <h2>Panseat Tagapo</h2>
        </div>
        <div>
            <span>{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-link text-white">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
    

        <div class="section" id="calendar-section">
            
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addReservationModal">+ Add Reservation</button>
            <div id="reservationCalendar"></div>

            
    
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
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_information">Contact Information</label>
                                <input type="text" class="form-control" id="contact_information" name="contact_information" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="number_of_guests">Number of Guests</label>
                                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" required min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="booking_confirmation">Booking Confirmation</label>
                                <select class="form-control" id="booking_confirmation" name="booking_confirmation">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deposit">Deposit</label>
                                <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="occasion">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion">
                            </div>
                            <div class="form-group">
                                <label for="bundle">Bundle</label>
                                <input type="text" class="form-control" id="bundle" name="bundle">
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
        </div>

        <div class="section" id="bundles-section">
        <h4>Bundles</h4>
    <button class="btn btn-secondary mb-3" id="toggleArchivedBtn">Show Archived Bundles</button>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addBundleModal">+ Add Bundle</button>

    <div id="activeBundles" class="row">
    @foreach($activeBundles as $bundle)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($bundle->image)
                    <img src="{{ asset('storage/' . $bundle->image) }}" class="card-img-top" alt="{{ $bundle->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $bundle->name }}</h5>
                    <p class="card-text">{{ $bundle->desc }}</p>
                    <a href="{{ route('bundles.edit', $bundle) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('bundles.archive', $bundle) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">Archive</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div id="archivedBundles" class="row" style="display:none;">
    @foreach($archivedBundles as $bundle)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($bundle->image)
                    <img src="{{ asset('storage/' . $bundle->image) }}" class="card-img-top" alt="{{ $bundle->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $bundle->name }}</h5>
                    <p class="card-text">{{ $bundle->desc }}</p>
                    <form action="{{ route('bundles.restore', $bundle) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

        <div class="section" id="request-section">
            <h4>Request</h4>
            <p>Details about requests.</p>
        </div>

        @if(Auth::user()->position === 'admin') <!-- Show staff section only for admin -->
<div class="section" id="staff-section">
    <h4>Staff</h4>
    <button class="btn btn-secondary mb-3" id="toggleArchivedStaffBtn">Show Archived Staff</button>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addStaffModal">+ Add Staff</button>

    <div id="activeStaff" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeStaff as $staff)
                    <tr>
                        <td>{{ $staff->id }}</td>
                        <td>{{ $staff->name }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>{{ $staff->position }}</td>
                        <td>
                            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('staff.archive', $staff->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">Archive</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="archivedStaff" class="table-responsive" style="display:none;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($archivedStaff as $staff)
                    <tr>
                        <td>{{ $staff->name }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>{{ $staff->position }}</td>
                        <td>
                            <form action="{{ route('staff.restore', $staff->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Staff Logs Section -->
    <div class="mt-4">
        <h5>Staff Logs</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Action</th>
                        <th>Staff ID</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->user_id }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif


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
            <form action="{{ route('bundles.store') }}" method="POST" enctype="multipart/form-data">
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
    document.getElementById('toggleArchivedStaffBtn').addEventListener('click', function() {
        const activeStaff = document.getElementById('activeStaff');
        const archivedStaff = document.getElementById('archivedStaff');
        const toggleBtn = this;

        if (archivedStaff.style.display === 'none') {
            archivedStaff.style.display = 'block';
            activeStaff.style.display = 'none';
            toggleBtn.textContent = 'Show Active Staff';
        } else {
            archivedStaff.style.display = 'none';
            activeStaff.style.display = 'block';
            toggleBtn.textContent = 'Show Archived Staff';
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
            events: '/events', // Change or mock if backend is not yet set up
            selectable: false,
            select: function (info) {
                alert('Selected: ' + info.startStr + ' to ' + info.endStr);
            },
        });

        calendar.render();
    });
</script>



</body>
</html>
