<div class="col-xl-3 col-lg-4 col-md-6 game-card {{ !$game->is_active ? 'opacity-50' : '' }}" id="game-{{ $game->id }}">
    <div class="card h-100 shadow-sm border-0">
        <div class="position-relative">
            <img src="{{ Storage::url($game->image_path) }}" class="card-img-top" alt="{{ $game->title }}" style="height: 250px; object-fit: cover;">
            @if($game->discount > 0)
                <span class="position-absolute top-0 end-0 badge bg-danger m-2">-{{ $game->discount }}%</span>
            @endif
            @if($game->is_featured)
                <span class="position-absolute top-0 start-0 badge bg-warning m-2">Featured</span>
            @endif
            @if(!$game->is_active)
                <span class="position-absolute bottom-0 start-0 badge bg-secondary m-2">Inactive</span>
            @endif
        </div>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-dark">{{ $game->title }}</h5>
            @if($game->developer)
                <h6 class="card-subtitle mb-2 text-muted">{{ $game->developer }}</h6>
            @endif
            <p class="card-text text-muted flex-grow-1">{{ Str::limit($game->description, 100) }}</p>
            <div class="mb-2">
                <span class="badge bg-info">{{ ucfirst($game->genre) }}</span>
            </div>
            <div class="price-section mb-3">
                @if($game->price < $game->original_price)
                    <span class="text-decoration-line-through text-muted">₹{{ number_format($game->original_price, 0) }}</span>
                    <span class="h5 text-success ms-2">₹{{ number_format($game->price, 0) }}</span>
                @else
                    <span class="h5 text-dark">₹{{ number_format($game->original_price, 0) }}</span>
                @endif
            </div>
            <div class="btn-group w-100" role="group">
                <button class="btn btn-outline-primary btn-sm" onclick="editGame({{ $game->id }})"><i class="fas fa-edit"></i> Edit</button>
                <button class="btn btn-outline-danger btn-sm" onclick="deleteGame({{ $game->id }})"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>