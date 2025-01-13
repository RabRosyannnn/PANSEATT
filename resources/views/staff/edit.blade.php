<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container mt-5 d-flex justify-content-center">
    <div class="card" style="width: 100%; max-width: 600px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Edit Staff Member</h2>
            
            <form action="{{ route('staff.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name field -->
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email field -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                 <!-- Position field (Dropdown) -->
                 <div class="form-group mb-3">
                    <label for="position">Position</label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="Admin" {{ old('position', $user->position) == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Staff" {{ old('position', $user->position) == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('position')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Update button -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Staff</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
