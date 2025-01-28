<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bundle</title>
    <link rel="stylesheet" href="{{ asset('css/Bedit.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      $(document).ready(function() {
    // Listen for file input change
    $('#image').change(function() {
        var formData = new FormData();
        formData.append('image', this.files[0]);  // Append the selected file
        formData.append('_token', '{{ csrf_token() }}'); // CSRF token

        // AJAX request to update image
        $.ajax({
            url: '{{ route('bundles.update', $bundle) }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Update the image preview without reloading
                if(response.success) {
                    var newImageUrl = response.imageUrl + '?timestamp=' + new Date().getTime(); // Add timestamp to avoid caching
                    $('.image-box img').attr('src', newImageUrl); // Update the image source
                }
            },
            error: function(xhr, status, error) {
                console.log("Error uploading image:", error);
            }
        });
    });
});

    </script>
</head>
<body>
    <div class="page-container">
        <div class="card">
            <div class="card-header">
                <h2>Edit Bundle</h2>
                <a href="{{ route('dashboard') }}" class="btn-back">BACK</a>
            </div>
            <div class="card-body">
                <form id="editBundleForm" action="{{ route('bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" id="price" name="price" value="{{ old('price', $bundle->price) }}" placeholder="Enter price" required>
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
