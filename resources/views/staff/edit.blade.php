<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
<div class="container">
    <div class="card">
        <!-- Header -->
        <div class="card-header">
            <h2>Edit Staff Member</h2>
            <a href="{{ route('dashboard') }}" class="btn-back">BACK</a>
        </div>

        <!-- Body -->
        <div class="card-body">
        <form action="{{ route('staff.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

                <!-- Input Group -->
                <div class="input-group">
                    <!-- Left Side: Form Fields -->
                    <div class="form-fields">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                placeholder="Enter name" 
                                required>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                placeholder="Enter email" 
                                required>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select 
                                class="form-control" 
                                id="position" 
                                name="position" 
                                required>
                                <option value="Admin" {{ old('position', $user->position) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Staff" {{ old('position', $user->position) == 'Staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            @error('position')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn-save">SAVE</button>
                    <a href="{{ route('dashboard') }}" class="btn-clear">CLEAR</a>
                </div>
            </form>
        </div>
    </div>
</div>
