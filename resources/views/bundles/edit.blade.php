<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bundle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Bedit.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Edit Bundle</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
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
        <div class="card-container">
            <!-- Left Side: Form Fields -->
            <div class="form-left">
                <form action="{{ route('bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Bundle Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $bundle->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea class="form-control" id="desc" name="desc" rows="4" required>{{ old('desc', $bundle->desc) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update Bundle</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Cancel</a>
                </form>
            </div>

            <!-- Right Side: Image Section -->
            <div class="form-right">
                <label for="image">Bundle Image (Optional)</label>
                <input type="file" class="form-control-file mb-3" id="image" name="image">

                @if($bundle->image)
                    <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}">
                @else
                    <img src="https://via.placeholder.com/150" alt="Default Bundle Image">
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
