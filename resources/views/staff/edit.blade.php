
    <div class="container">
        <h2>Edit Staff Member</h2>
        
        <form action="{{ route('staff.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Position field -->
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $user->position) }}" required>
                @error('position')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Update button -->
            <button type="submit" class="btn btn-primary">Update Staff</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

