@extends('layouts.app')

@section('title', 'Game Management')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🎮 Game Management</h2>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> Add New Game
        </button>
    </div>

    <!-- Banner Slider Management Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="fas fa-images"></i> Banner Slider Management</h5>
                <small>Manage the 3 featured games that appear in the main banner slider</small>
            </div>
            <button class="btn btn-success btn-sm" onclick="addNewSliderBanner()" {{ count($bannerSliders) >= 3 ? 'disabled' : '' }}>
                <i class="fas fa-plus"></i> Add Slider Banner ({{ count($bannerSliders) }}/3)
            </button>
        </div>
        <div class="card-body">
            @if(count($bannerSliders) == 0)
                <div class="text-center py-4">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Banner Sliders Yet</h5>
                    <p class="text-muted">Click "Add Slider Banner" to create your first banner slider</p>
                </div>
            @else
                <div class="row">
                    @foreach($bannerSliders as $slider)
                    <div class="col-md-4 mb-3">
                        <div class="card border-info h-100 d-flex flex-column">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Slider Position {{ $slider->display_order }}</h6>
                                <div>
                                    <button class="btn btn-sm btn-warning" onclick="openSliderEditor({{ $slider->display_order }}, {{ $slider->id }})"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSlider({{ $slider->id }})"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="card-body text-center flex-grow-1">
                                <img src="{{ $slider->image_path ? Storage::disk('public')->url($slider->image_path) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $slider->title }}" 
                                     style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;" class="mb-2">
                                <h6 class="card-title">{{ $slider->title ?? '—' }}</h6>
                                <p class="text-muted mb-2">₹{{ $slider->price }} @if($slider->original_price) <del class="text-danger">₹{{ $slider->original_price }}</del> @endif</p>
                                <span class="badge {{ $slider->is_active?'bg-success':'bg-secondary' }}">{{ $slider->is_active?'Active':'Inactive' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    {{-- Show empty slots for remaining positions --}}
                    @for($i = count($bannerSliders) + 1; $i <= 3; $i++)
                    <div class="col-md-4 mb-3">
                        <div class="card border-dashed h-100 d-flex flex-column" style="border: 2px dashed #ccc;">
                            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Slider Position {{ $i }}</h6>
                                <p class="text-muted mb-3">Empty Slot</p>
                                <button class="btn btn-outline-primary btn-sm" onclick="addSliderAtPosition({{ $i }})">
                                    <i class="fas fa-plus"></i> Add Banner
                                </button>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle" id="gamesTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Original Price</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($games as $key => $game)
                <tr id="game-{{ $game->id }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $game->title }}</td>
                    <td>{{ $game->genre }}</td>
                    <td>₹{{ $game->original_price ?? '-' }}</td>
                    <td>₹{{ $game->price }}</td>
                    <td>{{ $game->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $game->is_featured ? 'Yes' : 'No' }}</td>
                    <td>
                        @php($img = $game->image_path ? Storage::disk('public')->url($game->image_path) : asset('images/placeholder.jpg'))
                        <img src="{{ $img }}" alt="Image" style="width:50px;height:50px;">
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editGame({{ $game->id }})">Edit</button>
                        <button class="btn btn-sm btn-secondary" onclick="openGallery({{ $game->id }})">Manage Gallery</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteGame({{ $game->id }})">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="gameModal" tabindex="-1" aria-labelledby="gameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="gameForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="gameId" name="gameId">
                <div class="modal-header">
                    <h5 class="modal-title" id="gameModalLabel">Add / Edit Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" id="genre" name="genre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="developer" class="form-label">Developer</label>
                        <input type="text" id="developer" name="developer" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="release_date" class="form-label">Release Date</label>
                            <input type="date" id="release_date" name="release_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trailer_url" class="form-label">Trailer URL</label>
                            <input type="url" id="trailer_url" name="trailer_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags (comma separated)</label>
                        <input type="text" id="tags" name="tags" class="form-control" placeholder="RPG, Open World, Shooter">
                    </div>
                    <div class="mb-3">
                        <label for="original_price" class="form-label">Original Price</label>
                        <input type="number" id="original_price" name="original_price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Selling Price</label>
                        <input type="number" id="price" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 form-check form-switch">
                            <input type="checkbox" id="supports_windows" name="supports_windows" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="supports_windows">Supports Windows</label>
                        </div>
                        <div class="col-md-4 form-check form-switch">
                            <input type="checkbox" id="supports_controller" name="supports_controller" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="supports_controller">Controller Supported</label>
                        </div>
                        <div class="col-md-4 form-check form-switch">
                            <input type="checkbox" id="is_single_player" name="is_single_player" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="is_single_player">Single Player</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="min_requirements" class="form-label">Minimum Requirements</label>
                            <textarea id="min_requirements" name="min_requirements" class="form-control" rows="4" placeholder="Write minimum specs..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rec_requirements" class="form-label">Recommended Requirements</label>
                            <textarea id="rec_requirements" name="rec_requirements" class="form-control" rows="4" placeholder="Write recommended specs..."></textarea>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1">
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input type="file" id="image" name="image" class="form-control">
                        <img id="imagePreview" src="" style="max-width:100px; display:none;" class="mt-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Per-Game Gallery (Banners) Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Manage Game Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="galleryGameId" value="">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Upload New Banner Image</label>
                        <input type="file" id="bannerImage" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="bannerIsActive" checked>
                            <label class="form-check-label" for="bannerIsActive">Active</label>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" onclick="uploadBanner()">Upload</button>
                    </div>
                </div>
                <div id="bannersList" class="row g-3"></div>
            </div>
        </div>
    </div>
    
</div>
<!-- Banner Slider Edit Modal -->
<div class="modal fade" id="bannerSliderModal" tabindex="-1" aria-labelledby="bannerSliderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerSliderModalLabel">Change Banner Slider Game</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Game for Slider Position (Optional):</label>
                    <select id="sliderGameSelect" class="form-select">
                        <option value="">Choose a game or create custom banner...</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" data-title="{{ $game->title }}" data-price="{{ $game->price }}" data-original-price="{{ $game->original_price }}" data-image="{{ $game->image_path ? Storage::disk('public')->url($game->image_path) : asset('images/placeholder.jpg') }}">
                                {{ $game->title }} - ₹{{ $game->price }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Select a game to auto-fill details, or leave empty to create a custom banner</small>
                </div>
                <div id="sliderGamePreview" class="text-center" style="display: none;">
                    <img id="sliderPreviewImage" src="" alt="" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" class="mb-3">
                    <h6 id="sliderPreviewTitle"></h6>
                    <p id="sliderPreviewPrice"></p>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Position (1-3) *</label>
                        <input type="number" id="sliderDisplayOrder" class="form-control" min="1" max="3" value="1" readonly>
                        <small class="text-muted">Position is auto-assigned</small>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Custom Banner Details</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" id="sliderTitle" class="form-control" placeholder="Banner Title *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="sliderSlug" class="form-control" placeholder="banner-slug">
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="sliderPrice" class="form-control" placeholder="Price *" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="sliderOldPrice" class="form-control" placeholder="Original Price" min="0" step="0.01">
                            </div>
                        </div>
                        <small class="text-muted">Required for custom banners. Auto-filled when game is selected.</small>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">Upload Image *</label>
                    <input type="file" id="sliderImage" class="form-control" accept="image/*">
                    <div class="mt-2">
                        <img id="sliderImagePreview" src="" style="max-width: 200px; max-height: 100px; display: none; border-radius: 8px;" class="img-thumbnail">
                    </div>
                    <small class="text-muted">Required for custom banners. Recommended size: 1920x720px</small>
                </div>
                <input type="hidden" id="sliderId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveBannerSlider()">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-dashed {
    border-style: dashed !important;
    transition: all 0.3s ease;
}

.border-dashed:hover {
    border-color: #007bff !important;
    background-color: rgba(0, 123, 255, 0.05);
}

.img-thumbnail {
    border: 2px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.modal-header .btn-close {
    filter: invert(1);
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-muted {
    font-size: 0.875rem;
}

.alert {
    border: none;
    border-radius: 8px;
}
</style>
@endpush

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const gameModal = new bootstrap.Modal(document.getElementById('gameModal'));
    const gameForm = document.getElementById('gameForm');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));

    // Image Preview
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = e => { imagePreview.src = e.target.result; imagePreview.style.display='block'; };
            reader.readAsDataURL(file);
        }
    });

    // Clear validation errors
    function clearErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(e => e.remove());
        gameForm.querySelectorAll('.is-invalid').forEach(i => i.classList.remove('is-invalid'));
    }

    // Show validation errors
    function showErrors(errors) {
        for (const field in errors) {
            const input = document.getElementById(field);
            if(input){
                input.classList.add('is-invalid');
                const errorEl = document.createElement('div');
                errorEl.classList.add('invalid-feedback');
                errorEl.innerText = errors[field][0];
                input.parentNode.appendChild(errorEl);
            }
        }
    }

    // Open Add Modal
    window.openModal = function() {
        gameForm.reset();
        imagePreview.style.display='none';
        document.getElementById('gameId').value='';
        clearErrors();
        gameModal.show();
    };

    // Edit game
    window.editGame = function(id) {
        fetch(`/admin/games/${id}/edit`).then(res=>res.json()).then(data=>{
            const game = data.game;
            document.getElementById('gameId').value = game.id;
            document.getElementById('title').value = game.title;
            document.getElementById('genre').value = game.genre;
            document.getElementById('developer').value = game.developer ?? '';
            document.getElementById('release_date').value = game.release_date ?? '';
            document.getElementById('trailer_url').value = game.trailer_url ?? '';
            document.getElementById('tags').value = game.tags ?? '';
            document.getElementById('original_price').value = game.original_price ?? '';
            document.getElementById('price').value = game.price;
            document.getElementById('description').value = game.description;
            document.getElementById('supports_windows').checked = !!game.supports_windows;
            document.getElementById('supports_controller').checked = !!game.supports_controller;
            document.getElementById('is_single_player').checked = !!game.is_single_player;
            document.getElementById('min_requirements').value = game.min_requirements ?? '';
            document.getElementById('rec_requirements').value = game.rec_requirements ?? '';
            document.getElementById('is_active').checked = game.is_active;
            document.getElementById('is_featured').checked = game.is_featured;
            if(game.image_path){ imagePreview.src=`/storage/${game.image_path}`; imagePreview.style.display='block'; }
            else imagePreview.style.display='none';
            clearErrors();
            gameModal.show();
        }).catch(err=>{ alert("Failed to load game."); console.error(err); });
    };

    // Delete game
    window.deleteGame = function(id){
        if(confirm("Delete this game?")){
            fetch(`/admin/games/${id}`, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
            })
            .then(async res=>{
                if(!res.ok){ const data=await res.json(); alert(data.message||"Failed"); throw new Error("Delete failed"); }
                document.getElementById(`game-${id}`).remove();
            }).catch(err=>console.error(err));
        }
    };

    // Submit Add/Edit form
    gameForm.addEventListener("submit", function(e){
        e.preventDefault();
        clearErrors();
        const id = document.getElementById('gameId').value;
        const formData = new FormData(gameForm);
        let url = '/admin/games';
        if(id){ url=`/admin/games/${id}`; formData.append('_method','PUT'); }

        fetch(url,{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body: formData
        })
        .then(async res=>{
            const data = await res.json();
            if(!res.ok || data.error){
                if(data.errors) showErrors(data.errors);
                else alert(data.message||"Failed to save game");
                throw new Error("Validation error");
            }
            return data;
        })
        .then(data=>{
            const game = data.game;
            if(!id){
                const tbody=document.querySelector('#gamesTable tbody');
                const row=document.createElement('tr');
                row.id=`game-${game.id}`;
                row.innerHTML=`
                    <td>${tbody.children.length+1}</td>
                    <td>${game.title}</td>
                    <td>${game.genre}</td>
                    <td>₹${game.original_price??'-'}</td>
                    <td>₹${game.price}</td>
                    <td>${game.is_active?'Active':'Inactive'}</td>
                    <td>${game.is_featured?'Yes':'No'}</td>
                    <td><img src="${game.image_url || (game.image_path?`/storage/${game.image_path}`:'/images/placeholder.jpg')}" style="width:50px;height:50px;"></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editGame(${game.id})">Edit</button>
                        <button class="btn btn-sm btn-secondary" onclick="openGallery(${game.id})">Manage Gallery</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteGame(${game.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            } else location.reload();
            gameModal.hide();
            gameForm.reset();
            imagePreview.style.display='none';
        })
        .catch(err=>console.error(err));
    });

    // Banner Slider Management
    const bannerSliderModal = new bootstrap.Modal(document.getElementById('bannerSliderModal'));
    const sliderGameSelect = document.getElementById('sliderGameSelect');
    const sliderGamePreview = document.getElementById('sliderGamePreview');
    const sliderDisplayOrder = document.getElementById('sliderDisplayOrder');
    const sliderTitle = document.getElementById('sliderTitle');
    const sliderSlug = document.getElementById('sliderSlug');
    const sliderPrice = document.getElementById('sliderPrice');
    const sliderOldPrice = document.getElementById('sliderOldPrice');
    const sliderImage = document.getElementById('sliderImage');

    // Preview selected game
    sliderGameSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('sliderPreviewImage').src = selectedOption.dataset.image;
            document.getElementById('sliderPreviewTitle').textContent = selectedOption.dataset.title;
            document.getElementById('sliderPreviewPrice').textContent = '₹' + selectedOption.dataset.price + 
                (selectedOption.dataset.originalPrice ? ' (was ₹' + selectedOption.dataset.originalPrice + ')' : '');
            sliderGamePreview.style.display = 'block';
            
            // Auto-fill custom fields with game data
            sliderTitle.value = selectedOption.dataset.title;
            sliderSlug.value = selectedOption.value; // Use game ID as slug reference
            sliderPrice.value = selectedOption.dataset.price;
            sliderOldPrice.value = selectedOption.dataset.originalPrice || '';
        } else {
            sliderGamePreview.style.display = 'none';
            // Clear custom fields when no game selected
            sliderTitle.value = '';
            sliderSlug.value = '';
            sliderPrice.value = '';
            sliderOldPrice.value = '';
            // Clear image preview
            sliderImagePreview.style.display = 'none';
            sliderImageInput.value = '';
        }
    });
    
    // Image preview functionality
    const sliderImageInput = document.getElementById('sliderImage');
    const sliderImagePreview = document.getElementById('sliderImagePreview');
    
    sliderImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file!');
                this.value = '';
                sliderImagePreview.style.display = 'none';
                return;
            }
            
            // Validate file size (max 4MB)
            if (file.size > 4 * 1024 * 1024) {
                alert('Image size must be less than 4MB!');
                this.value = '';
                sliderImagePreview.style.display = 'none';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                sliderImagePreview.src = e.target.result;
                sliderImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            sliderImagePreview.style.display = 'none';
        }
    });

    // Edit banner slider
    window.editBannerSlider = function(sliderId) {
        document.getElementById('sliderId').value = sliderId;
        document.getElementById('sliderGameSelect').value = '';
        sliderGamePreview.style.display = 'none';
        bannerSliderModal.show();
    };

    window.openSliderEditor = function(displayOrder, sliderId){
        clearModalForm();
        sliderDisplayOrder.value = displayOrder;
        document.getElementById('sliderId').value = sliderId || '';
        document.getElementById('bannerSliderModalLabel').textContent = 'Edit Slider Position ' + displayOrder;
        
        // Load existing slider data if editing
        if (sliderId) {
            // You can add AJAX call here to load existing slider data
            console.log('Loading slider data for ID:', sliderId);
        }
        
        bannerSliderModal.show();
    }

    // Add new slider banner function
    window.addNewSliderBanner = function() {
        const currentCount = {{ count($bannerSliders) }};
        if (currentCount >= 3) {
            alert('Maximum 3 slider banners allowed!');
            return;
        }
        const nextPosition = currentCount + 1;
        addSliderAtPosition(nextPosition);
    }

    // Add slider at specific position
    window.addSliderAtPosition = function(position) {
        clearModalForm();
        sliderDisplayOrder.value = position;
        document.getElementById('bannerSliderModalLabel').textContent = 'Add New Slider at Position ' + position;
        bannerSliderModal.show();
    }
    
    // Clear modal form function
    function clearModalForm() {
        document.getElementById('sliderId').value = '';
        document.getElementById('sliderGameSelect').value = '';
        sliderTitle.value = '';
        sliderSlug.value = '';
        sliderPrice.value = '';
        sliderOldPrice.value = '';
        sliderImage.value = '';
        sliderGamePreview.style.display = 'none';
        sliderImagePreview.style.display = 'none';
        
        // Remove required attributes when game is selected
        sliderTitle.removeAttribute('required');
        sliderPrice.removeAttribute('required');
        sliderImage.removeAttribute('required');
    }

    // Save banner slider changes
    window.saveBannerSlider = function() {
        const sliderId = document.getElementById('sliderId').value;
        const currentCount = {{ count($bannerSliders) }};
        
        // Check maximum limit for new sliders
        if (!sliderId && currentCount >= 3) {
            alert('Maximum 3 slider banners allowed! Please delete an existing banner first.');
            return;
        }
        
        const formData = new FormData();
        const gameId = document.getElementById('sliderGameSelect').value;
        
        // Validate required fields for custom banners
        if (!gameId) {
            if (!sliderTitle.value.trim()) {
                alert('Please enter a title for the custom banner!');
                return;
            }
            if (!sliderPrice.value) {
                alert('Please enter a price for the custom banner!');
                return;
            }
            if (!sliderImage.files[0] && !sliderId) {
                alert('Please upload an image for the custom banner!');
                return;
            }
        }
        
        if (gameId) formData.append('game_id', gameId);
        formData.append('display_order', sliderDisplayOrder.value || 1);
        if (sliderTitle.value) formData.append('title', sliderTitle.value);
        if (sliderSlug.value) formData.append('slug', sliderSlug.value);
        if (sliderPrice.value) formData.append('price', sliderPrice.value);
        if (sliderOldPrice.value) formData.append('original_price', sliderOldPrice.value);
        if (sliderImage.files[0]) formData.append('image', sliderImage.files[0]);
        formData.append('is_active', '1'); // Make new sliders active by default

        const url = sliderId ? ('/admin/banner-sliders/' + sliderId) : '/admin/banner-sliders';
        const method = sliderId ? 'POST' : 'POST';
        if (sliderId) formData.append('_method', 'PUT');

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bannerSliderModal.hide();
                alert(sliderId ? 'Banner slider updated successfully!' : 'New banner slider added successfully!');
                location.reload();
            } else {
                alert(data.message || 'Failed to save banner slider');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save banner slider. Please try again.');
        });
    };

    window.deleteSlider = function(id){
        if(!confirm('Delete this slider? This will free up a slot for new banners.')) return;
        fetch('/admin/banner-sliders/' + id, {
            method:'DELETE',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' }
        })
        .then(async res=>{ if(!res.ok){ throw new Error('delete failed'); } return res.json(); })
        .then(()=> {
            // Show success message and reload
            alert('Slider deleted successfully! You can now add a new banner.');
            location.reload();
        })
        .catch(error => {
            console.error(error);
            alert('Failed to delete slider. Please try again.');
        });
    }

    // -----------------------------
    // Per-Game Gallery (Banners)
    // -----------------------------
    window.openGallery = function(gameId){
        document.getElementById('galleryGameId').value = gameId;
        loadBanners(gameId);
        galleryModal.show();
    };

    function bannerCardHtml(banner){
        const url = banner.image_path ? `/storage/${banner.image_path}` : '/images/placeholder.jpg';
        return `
            <div class="col-md-4" id="banner-${banner.id}">
                <div class="card h-100">
                    <img src="${url}" class="card-img-top" style="height:160px;object-fit:cover;">
                    <div class="card-body p-2">
                        <span class="badge ${banner.is_active?'bg-success':'bg-secondary'}">${banner.is_active?'Active':'Inactive'}</span>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <button class="btn btn-sm ${banner.is_active?'btn-warning':'btn-success'}" onclick="toggleBanner(${banner.id})">${banner.is_active?'Deactivate':'Activate'}</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBanner(${banner.id})">Delete</button>
                    </div>
                </div>
            </div>
        `;
    }

    function loadBanners(gameId){
        const list = document.getElementById('bannersList');
        list.innerHTML = '<div class="text-center text-muted">Loading...</div>';
        fetch(`/admin/games/${gameId}/banners`, { headers: { 'Accept':'application/json' }})
            .then(res=>res.json())
            .then(data=>{
                const banners = data.banners || [];
                if(!banners.length){ list.innerHTML = '<div class="text-center text-muted">No banners yet.</div>'; return; }
                list.innerHTML = banners.map(b=>bannerCardHtml(b)).join('');
            })
            .catch(()=>{ list.innerHTML = '<div class="text-danger">Failed to load banners.</div>'; });
    }

    window.uploadBanner = function(){
        const gameId = document.getElementById('galleryGameId').value;
        const file = document.getElementById('bannerImage').files[0];
        const isActive = document.getElementById('bannerIsActive').checked;
        if(!file){ alert('Please choose an image'); return; }
        const fd = new FormData();
        fd.append('image', file);
        if(isActive) fd.append('is_active', '1');
        fetch(`/admin/games/${gameId}/banners`, {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' },
            body: fd
        })
        .then(async res=>{
            const data = await res.json();
            if(!res.ok){ alert(data.message||'Failed to upload'); throw new Error('upload failed'); }
            return data.banner;
        })
        .then(()=>{ document.getElementById('bannerImage').value=''; loadBanners(gameId); })
        .catch(console.error);
    };

    window.toggleBanner = function(bannerId){
        fetch(`/admin/banners/${bannerId}/toggle`, {
            method:'PUT',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' }
        })
        .then(async res=>{ const data=await res.json(); if(!res.ok){ throw new Error('toggle failed'); } return data.banner; })
        .then(()=>{ const gameId = document.getElementById('galleryGameId').value; loadBanners(gameId); })
        .catch(console.error);
    };

    window.deleteBanner = function(bannerId){
        if(!confirm('Delete this banner?')) return;
        fetch(`/admin/banners/${bannerId}`, {
            method:'DELETE',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' }
        })
        .then(async res=>{ const data=await res.json(); if(!res.ok){ throw new Error('delete failed'); } return true; })
        .then(()=>{ const gameId = document.getElementById('galleryGameId').value; loadBanners(gameId); })
        .catch(console.error);
    };
});
</script>
@endsection
