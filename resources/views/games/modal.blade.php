<div class="modal fade" id="gameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content needs-validation" id="gameForm" novalidate enctype="multipart/form-data">
            <input type="hidden" id="gameId">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="gameModalLabel">Add New Game</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="genre" class="form-label">Genre *</label>
                        <select id="genre" name="genre" class="form-select" required>
                            <option value="" disabled selected>Select Genre</option>
                            <option>Action</option>
                            <option>Adventure</option>
                            <option>RPG</option>
                            <option>Shooter</option>
                            <option>Racing</option>
                        </select>
                        <div class="invalid-feedback">Genre is required.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="original_price" class="form-label">Original Price (₹) *</label>
                        <input type="number" id="original_price" name="original_price" class="form-control" min="0" required>
                        <div class="invalid-feedback">Enter a valid original price.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Selling Price (₹) *</label>
                        <input type="number" id="price" name="price" class="form-control" min="0" required>
                        <div class="invalid-feedback">Selling price is required.</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                        <div class="invalid-feedback">Description is required.</div>
                    </div>
                    <div class="col-md-6 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <div class="col-md-6 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured">
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="image" class="form-label">Game Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Game</button>
            </div>
        </form>
    </div>
</div>
