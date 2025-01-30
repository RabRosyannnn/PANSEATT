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
<link rel="icon" type="image/png" href="{{ asset('images/panseat_logo.png') }}">

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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filtersModal">
    Generate Report
</button>
    <div class="completed-reservations-card text-center mb-4" style="background-color: #E07A5F; color: #FFD166;">
        <div class="generate-report text-center mb-4">
        


<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="filtersModal" tabindex="-1" aria-labelledby="filtersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('generate.report') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="filtersModalLabel">Select Month and Year</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Month Filter -->
                    <div class="form-group">
                        <label for="month" class="form-label">Select Month:</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">All Months</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date("F", mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Year Filter -->
                    <div class="form-group">
                        <label for="year" class="form-label">Select Year:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">All Years</option>
                            @for ($i = now()->year; $i >= now()->year - 10; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>




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
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required maxlength="25">
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
                                <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" required min="1" max="20">
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
                                    <option value="cancelled" {{ old('booking_confirmation') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
    <label for="bundles">Select Bundles</label>
    <div style="max-height: 100px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        @foreach($activeBundles as $bundle)
            <div class="form-check d-flex align-items-center mb-2">
                <input class="form-check-input" type="checkbox" id="bundle_{{ $bundle->id }}" name="bundles[]" value="{{ $bundle->id }}" onchange="toggleQuantityInput(this)">
                <label class="form-check-label me-2" for="bundle_{{ $bundle->id }}">
                    {{ $bundle->name }} - ${{ number_format($bundle->price, 2) }}
                </label>
                <div class="input-group quantity-control" id="quantity-group-{{ $bundle->id }}" style="display: none; width: 50px; height: 30px; margin-left:10px">
                    <input type="number" class="form-control text-center" id="quantity_{{ $bundle->id }}" name="quantities[{{ $bundle->id }}]" value="1" min="1" max="10" style="width: 50px; height: 30px;" onchange="updateTotalPrice(); validateQuantity(this)">
                </div>
            </div>
        @endforeach
    </div>
</div>             <!-- Total Price Display -->
<div class="form-group">
    <label for="total_price">Total Price</label>
    <input type="text" class="form-control" id="total_price" name="total_price" readonly>
</div>
<div class="form-group">
    <label for="deposit">Deposit</label>
    <input type="number" class="form-control" id="deposit" name="deposit" step="0.01" min="0" required max="99999.99" oninput="validateDeposit(this)">
</div>
                            <div class="form-group">
                                <label for="occasion">Occasion</label>
                                <input type="text" class="form-control" id="occasion" name="occasion" required maxlength="25">
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
        

<!-- Bundles Section -->
<div class="section" id="bundles-section">
    <div class="bundle-header">
        <h4>Bundles</h4>
        
        <button class="btn btn-secondary mb-3" id="toggleArchivedBundlesBtn">Show Archived Bundles</button>
        <select id="categoryFilter" class="form-control mb-3" style="width: auto; display: inline-block;">
            <option value="">All Categories</option>
            <option value="Bilao">Bilao</option>
            <option value="Noodles">Noodles</option>
            <option value="Chicken">Chicken</option>
            <option value="Pork">Pork</option>
            <option value="Chicharon">Chicharon</option>
            <option value="Fish">Fish</option>
            <option value="Vegetable">Vegetable</option>
            <option value="SeaFood">SeaFood</option>
            <option value="Baka">Baka</option>
            <option value="Soup">Soup</option>
            <option value="Rice">Rice</option>
        </select>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addBundleModal">+ Add Bundle</button>
    </div>
    <!-- Active Bundles Section -->
    <div id="activeBundlesContainer" class="row" style="overflow-x: auto; white-space: nowrap;">
        <div id="activeBundles" class="d-flex">
            @foreach ($activeBundles as $bundle)
                <div class="bundle-card" data-category="{{ $bundle->category }}">
                    <div class="card text-center">
                        @if ($bundle->image)
                            <img src="{{ asset('storage/' . $bundle->image) }}" 
                                 class="card-img-top mx-auto d-block bundle-img" 
                                 alt="{{ $bundle->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $bundle->name }}</h5>
                            <p class="card-text">{{ $bundle->desc }}</p>
                            <p class="card-text">{{ $bundle->price }}</p>
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
    </div>


    <!-- Archived Bundles Section -->
    <div id="archivedBundles" class="row" style="display:none;">
        @foreach($archivedBundles as $bundle)
            <div class="col-md-4 mb-3 d-flex justify-content-center">
                <div class="card bundle-card">
                    @if($bundle->image)
                    <img src="{{ asset('storage/' . $bundle->image) }}" 
     class="card-img-top mx-auto d-block bundle-image" 
     alt="{{ $bundle->name }}">
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
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#messageModal" onclick="setRequestId({{ $request->id }}, 'approve')">Approve</button>
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#messageModal" onclick="setRequestId({{ $request->id }}, 'reject')">Reject</button>
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

    <div class="staff-list" id="staff-section">
    <div class="list-header">
        <h4>Staff List</h4>
        <button class="btn btn-secondary mb-3" id="toggleArchivedStaffBtn">Show Archived Staffs</button>
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

    <!-- Active Staff Section -->
    <div id="activeStaff" class="staff-cards">
        @foreach($activeStaff as $staff)
            <div class="staff-card">
                <h5 class="staff-id">ID: {{ $staff->id }}</h5>
                <div class="staff-icon"></div>
                <h5>{{ $staff->name }}</h5>
                <div class="staff-actions">
                    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-edit">EDIT</a>
                    @if(Auth::id() !== $staff->id) <!-- Check if the logged-in user is not the staff member -->
                        <form action="{{ route('staff.archive', $staff->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-archive">ARCHIVE</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Archived Staff Section -->
    <div id="archivedStaff" class="staff-cards" style="display:none;">
        @foreach($archivedStaff as $staff)
            <div class="staff-card">
                <h5 class="staff-id">ID: {{ $staff->id }}</h5>
                <div class="staff-icon"></div>
                <h5>{{ $staff->name }}</h5>
                <div class="staff-actions">
                    <form action="{{ route('staff.restore', $staff->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">RESTORE</button>
                    </form>
                </div>
            </div>
        @endforeach
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
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="addBundleForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Bundle Name</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="25">
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea class="form-control" id="desc" name="desc" rows="4" required maxlength="255"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required 
                               max="99999.99" oninput="validatePrice(this)">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="Bilao">Bilao</option>
                            <option value="Noodles">Noodles</option>
                            <option value="Chicken">Chicken</option>
                            <option value="Pork">Pork</option>
                            <option value="Chicharon">Chicharon</option>
                            <option value="Fish">Fish</option>
                            <option value="Vegetable">Vegetable</option>
                            <option value="SeaFood">SeaFood</option>
                            <option value="Baka">Beef</option>
                            <option value="Soup">Soup</option>
                            <option value="Rice">Rice</option>
                        </select>
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
<!-- Modal for Approve/Deny Message -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Enter Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="messageForm" method="POST" action="">
    @csrf
    @method('PATCH') <!-- This is important for PATCH requests -->
    <div class="modal-body">
        <input type="hidden" id="requestId" name="request_id">
        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="approveButton">Approve</button>
        <button type="submit" class="btn btn-danger" id="rejectButton">Reject</button>
    </div>
</form>
        </div>
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

    
    // Call the export function after the chart is rendered
    chart.render();
    
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
                window.location.reload(); // Reload the page
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
        .then(response => {
            if (!response.ok) {
                // If the response is not OK, throw an error
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Failed to add bundle.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Bundle added successfully!');
                $('#addBundleModal').modal('hide'); // Hide modal
                addBundleForm.reset(); // Reset form fields
                window.location.reload(); // Reload the page
            } else {
                // Handle specific error messages from the server
                alert('Failed to add bundle: ' + (data.message || 'Unknown error occurred.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert( error.message); // Display the error message
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
  // Toggle Archived Bundles
  document.getElementById('toggleArchivedBundlesBtn').addEventListener('click', function() {
        const activeBundles = document.getElementById('activeBundlesContainer');
        const archivedBundles = document.getElementById('archivedBundles');
        const toggleBtn = this;

        // Check if the archived bundles are currently hidden
        if (archivedBundles.style.display === 'none') {
            // Show archived bundles and hide active bundles
            archivedBundles.style.display = 'block';
            activeBundles.style.display = 'none';
            toggleBtn.textContent = 'Show Active Bundles'; // Change button text
        } else {
            // Hide archived bundles and show active bundles
            archivedBundles.style.display = 'none';
            activeBundles.style.display = 'block';
            toggleBtn.textContent = 'Show Archived Bundles'; // Change button text back
        }
    });

    // Toggle Archived Staff
    document.getElementById('toggleArchivedStaffBtn').addEventListener('click', function() {
        const activeStaff = document.getElementById('activeStaff');
        const archivedStaff = document.getElementById('archivedStaff');
        const toggleBtn = this;

        if (archivedStaff.style.display === 'none') {
            archivedStaff.style.display = 'block';
            activeStaff.style.display = 'none';
            toggleBtn.textContent = 'Show Active Staffs';
        } else {
            archivedStaff.style.display = 'none';
            activeStaff.style.display = 'block';
            toggleBtn.textContent = 'Show Archived Staffs';
        }
    });
    function validatePrice(input) {
    // Convert the input value to a string
    const value = input.value.toString();
    
    // Check if the value exceeds 7 digits
    if (value.length > 7) {
        // If it does, truncate the value to the first 7 characters
        input.value = value.slice(0, 7);
    }
    
    // Ensure the value is not negative
    if (parseFloat(input.value) < 0) {
        input.value = 0;
    }
}
function updateTotalPrice() {
    let totalPrice = 500; // Initialize total price
    const checkboxes = document.querySelectorAll('input[name="bundles[]"]:checked');

    checkboxes.forEach((checkbox) => {
        const bundlePriceText = checkbox.nextElementSibling.innerText.split('- $')[1];
        const bundlePrice = parseFloat(bundlePriceText);
        const quantityInput = document.getElementById(`quantity_${checkbox.value}`);
        const quantity = parseInt(quantityInput.value) || 1; // Get quantity or default to 1
        totalPrice += bundlePrice * quantity; // Calculate total price
    });

    document.getElementById('total_price').value = totalPrice.toFixed(2); // Update total price display
}


document.getElementById('categoryFilter').addEventListener('change', function() {
        const selectedCategory = this.value;
        const bundles = document.querySelectorAll('.bundle-card');

        bundles.forEach(bundle => {
            const category = bundle.getAttribute('data-category');
            if (selectedCategory === "" || category === selectedCategory) {
                bundle.style.display = 'flex'; // Show the bundle
            } else {
                bundle.style.display = 'none'; // Hide the bundle
            }
        });
    });
    function changeQuantity(inputId, change) {
    const quantityInput = document.getElementById(inputId);
    let currentValue = parseInt(quantityInput.value) || 1; // Default to 1 if empty

    // Update the quantity
    currentValue += change;

    // Ensure the quantity does not go below 1
    if (currentValue < 1) {
        currentValue = 1;
    }

    quantityInput.value = currentValue; // Set the new value
    updateTotalPrice(); // Update the total price
}
function toggleQuantityInput(checkbox) {
    const quantityGroup = document.getElementById(`quantity-group-${checkbox.value}`);
    if (checkbox.checked) {
        quantityGroup.style.display = 'flex'; // Show the quantity input
    } else {
        quantityGroup.style.display = 'none'; // Hide the quantity input
        document.getElementById(`quantity_${checkbox.value}`).value = 1; // Reset quantity to 1
        updateTotalPrice(); // Update total price when unchecked
    }
}
function validateQuantity(input) {
    const maxQuantity = 10;
    if (parseInt(input.value) > maxQuantity) {
        input.value = maxQuantity; // Set to max if exceeded
    }
    updateTotalPrice(); // Update total price after validation
}
function setRequestId(requestId, action) {
    document.getElementById('requestId').value = requestId; // Set the request ID in the hidden input
    const messageForm = document.getElementById('messageForm');

    // Change the form action based on the action (approve or reject)
    if (action === 'approve') {
        messageForm.action = "{{ route('requests.approve', '__requestId__') }}".replace('__requestId__', requestId); // Set the action URL for approval
        document.getElementById('approveButton').style.display = 'inline-block';
        document.getElementById('rejectButton').style.display = 'none'; // Hide reject button
    } else {
        messageForm.action = "{{ route('requests.reject', '__requestId__') }}".replace('__requestId__', requestId); // Set the action URL for rejection
        document.getElementById('approveButton').style.display = 'none'; // Hide approve button
        document.getElementById('rejectButton').style.display = 'inline-block';
    }

    // Optionally, you can clear the message field or set a default message
    document.getElementById('message').value = ''; // Clear the message field
}
function validateDeposit(input) {
        const totalPrice = parseFloat(document.getElementById('total_price').value) || 0; // Get the total price
        const depositValue = parseFloat(input.value) || 0; // Get the deposit value

        // Check if the deposit is greater than the total price
        if (depositValue > totalPrice) {
            // Display an error message
            alert("Deposit cannot be greater than the total price.");
            input.value = ''; // Reset the deposit input
        }
    }
</script>

</body>
</html>