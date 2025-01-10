<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> <!-- Link to the external CSS file -->
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
            <h4>Calendar</h4>
            <p>Details about the calendar.</p>
        </div>

        <div class="section" id="bundles-section">
            <h4>Bundles</h4>
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addBundleModal">+ Add Bundle</button>

            @if($bundles->isEmpty())
                <p>No bundles available.</p>
            @else
                <div class="row">
                    @foreach($bundles as $bundle)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                @if($bundle->image)
                                    <img src="{{ asset('storage/' . $bundle->image) }}" class="card-img-top" alt="{{ $bundle->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $bundle->name }}</h5>
                                    <p class="card-text">{{ $bundle->desc }}</p>
                                    <a href="{{ route('bundles.edit', $bundle) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('bundles.destroy', $bundle) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="section" id="request-section">
            <h4>Request</h4>
            <p>Details about requests.</p>
        </div>

        @if(Auth::user()->position === 'admin') <!-- Show staff section only for admin -->
        <div class="section" id="staff-section">
    <h4>Staff</h4>
    <p>Details about staff management.</p>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addStaffModal">+ Add Staff</button>

    @if($users->isEmpty())
        <p>No staff members available.</p>
    @else
        <div class="table-responsive">
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
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->position }}</td>
                            <td>
                                <a href="{{ route('staff.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('staff.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

        @endif
    </div>
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
</body>
</html>
