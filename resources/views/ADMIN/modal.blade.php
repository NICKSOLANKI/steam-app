<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="modalForm" class="modal-content" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Add Game Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Game Title -->
        <div class="mb-3">
          <label class="form-label">Game Title</label>
          <input type="text" class="form-control" id="gameTitle" required pattern="^[A-Za-z\s]+$">
          <div class="invalid-feedback">Please enter a valid title (letters only).</div>
        </div>

        <!-- Developer -->
        <div class="mb-3">
          <label class="form-label">Developer</label>
          <input type="text" class="form-control" id="developer" required pattern="^[A-Za-z\s]+$">
          <div class="invalid-feedback">Developer name must contain letters only.</div>
        </div>

        <!-- Price -->
        <div class="mb-3">
          <label class="form-label">Price ($)</label>
          <input type="number" class="form-control" id="price" required min="0.01" step="0.01">
          <div class="invalid-feedback">Enter a valid price (greater than 0).</div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
      </div>
    </form>
  </div>
</div>
