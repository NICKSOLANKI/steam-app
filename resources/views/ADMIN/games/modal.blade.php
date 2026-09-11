<!-- resources/views/games/modal.blade.php -->
<div class="modal fade" id="gameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content needs-validation" id="gameForm" novalidate>
            <input type="hidden" id="gameId">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="gameModalLabel">Add New Game</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- form fields here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Game</button>
            </div>
        </form>
    </div>
</div>
