@extends('layouts.app')

@section('title', 'Setting')

@section('content')
<div class="col-12">
    <div class="card mb-4">
        <h5 class="card-header">Setting</h5>
        <div class="card-body">
            <form method="POST" action="{{ route('setting_apps.update') }}" enctype="multipart/form-data" id="form">
                @csrf
                @method('PUT')

                <!-- Logo Upload Section -->
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img
                        src="{{ asset('storage/uploads/logos/' . $setting->logo) }}"
                        alt="Logo Preview"
                        class="d-block rounded" width="100"
                        id="uploadedAvatar"
                    />
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                            <span class="d-none d-sm-block">Upload new logo</span>
                            <i class="ti ti-upload d-block d-sm-none"></i>
                            <input
                                type="file"
                                id="upload"
                                class="account-file-input"
                                hidden
                                name="logo"
                                accept="image/png, image/jpeg"
                                onchange="previewImage(event)"
                            />
                        </label>
                        <button type="button" class="btn btn-label-secondary account-image-reset mb-3" onclick="resetPreview()">
                            <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                        <div class="text-muted">Allowed JPG, GIF, or PNG. Max size: 800KB</div>
                    </div>
                </div>

                <hr class="my-0 mt-2" />

                <!-- Form Fields -->
                <div class="row mt-2">
                    <div class="mb-3 col-md-4">
                        <label for="brand" class="form-label">Brand Name</label>
                        <input
                            class="form-control"
                            type="text"
                            id="brand"
                            name="brand"
                            value="{{ old('brand', $setting->brand) }}"
                            autofocus
                        />
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="thumbnail" class="form-label">Thumbnail Brand</label>
                        <input
                            class="form-control"
                            type="text"
                            id="thumbnail"
                            name="thumbnail"
                            value="{{ old('thumbnail', $setting->thumbnail) }}"
                        />
                    </div>
                    <div class="mb-3 mt-4 col-md-4 text-end">
                        <button type="reset" class="btn btn-outline-secondary me-1 btn-reset">Reset</button>
                        <button type="submit" class="btn btn-primary ms-2 btn-submit">Submit</button>
                        <button class="btn btn-primary btn-loading d-none" type="button" disabled>
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span class="ms-25 align-middle">Loading...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        // Hide submit button and reset button
        document.querySelector('.btn-submit').classList.add('d-none');
        document.querySelector('.btn-reset').classList.add('d-none');
        
        // Show loading button
        document.querySelector('.btn-loading').classList.remove('d-none');
    });
    
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('uploadedAvatar').src = reader.result;
        };
        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function resetPreview() {
        document.getElementById('uploadedAvatar').src = "{{ asset('storage/uploads/logos/' . $setting->logo) }}";
    }

</script>
@endsection
