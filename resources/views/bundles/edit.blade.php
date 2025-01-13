<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bundle</title>
    <link rel="stylesheet" href="{{ asset('css/Bedit.css') }}">
</head>
<body>
    <div class="page-container">
        <div class="card">
            <div class="card-header">
                <h2>New Bundle</h2>
                <a href="{{ route('dashboard') }}" class="btn-back">BACK</a>
            </div>
            <div class="card-body">
                <form action="{{ route('bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-container">
                        <!-- Image Section -->
                        <div class="image-container">
                            <label for="image" class="image-label">
                                Update Image
                                <div class="image-box">
                                    @if($bundle->image)
                                        <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}">
                                    @else
                                        <span class="placeholder-icon">+</span>
                                    @endif
                                </div>
                            </label>
                            <input type="file" id="image" name="image" class="hidden-input">
                        </div>

                        <!-- Form Section -->
                        <div class="form-section">
                            <div class="form-group">
                                <label for="name">Bundle Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $bundle->name) }}" placeholder="Enter name" required>
                            </div>
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea id="desc" name="desc" rows="4" placeholder="Enter bundle description" required>{{ old('desc', $bundle->desc) }}</textarea>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="btn-save">SAVE</button>
                                <button type="reset" class="btn-clear">CLEAR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
