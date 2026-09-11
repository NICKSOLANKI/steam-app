@extends('ADMIN.layout')

@section('title','Manage Subscriptions')

@section('content')

<style>
body {
    background-color: #1b2838;
    color: #c7d5e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.subscription-container {
    max-width: 700px;
    margin: 40px auto;
    padding: 30px;
    background-color: #2a475e;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}
.subscription-container h2 {
    font-size: 28px;
    margin-bottom: 20px;
}
.plan {
    margin-top: 20px;
    padding: 20px;
    border: 2px solid #66c0f4;
    border-radius: 10px;
    background-color: #1b2838;
}
.plan h3 {
    font-size: 22px;
    margin-bottom: 10px;
    color: #fff;
}
.plan p {
    font-size: 16px;
    color: #dcdfe3;
}
.price {
    font-size: 24px;
    font-weight: bold;
    color: #66c0f4;
    margin: 15px 0;
}
.plan button {
    background-color: #66c0f4;
    color: #1b2838;
    border: none;
    padding: 10px 25px;
    font-size: 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.plan button:hover {
    background-color: #00bcd4;
}
.countdown {
    font-size: 16px;
    margin-top: 10px;
    color: #fff;
}
</style>

<div class="container py-4">
    <h2 class="mb-4">📦 Manage Subscriptions</h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Subscription Plans Management --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🎯 Subscription Plans</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                        <i class="fas fa-plus"></i> Add New Plan
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptionPlans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($plan->description, 50) }}</td>
                                    <td>{{ $plan->formatted_price }}</td>
                                    <td>{{ $plan->duration_text }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-plan-btn"
                                            data-id="{{ $plan->id }}"
                                            data-name="{{ $plan->name }}"
                                            data-slug="{{ $plan->slug }}"
                                            data-description="{{ $plan->description }}"
                                            data-price="{{ $plan->price }}"
                                            data-duration="{{ $plan->duration_days }}"
                                            data-active="{{ $plan->is_active }}"
                                            data-features="{{ json_encode($plan->features) }}"
                                            data-bs-toggle="modal" data-bs-target="#editPlanModal">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Subscriptions Management --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">👥 User Subscriptions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>User Email</th>
                                    <th>Plan</th>
                                    <th>Description</th>
                                    <th>Price (₹)</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                <tr id="row-{{ $sub->id }}">
                                    <td>{{ $sub->user->email }}</td>
                                    <td class="row-plan">{{ ucfirst($sub->plan) }}</td>
                                    <td class="row-description">{{ $sub->description }}</td>
                                    <td class="row-price">{{ $sub->price }}</td>
                                    <td class="row-status">{{ ucfirst($sub->status) }}</td>
                                    <td class="row-start">{{ $sub->start_date?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="row-end">{{ $sub->plan === 'monthly' ? ($sub->end_date?->format('Y-m-d') ?? '-') : 'Lifetime' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="{{ $sub->id }}"
                                            data-plan="{{ $sub->plan }}"
                                            data-status="{{ $sub->status }}"
                                            data-description="{{ $sub->description }}"
                                            data-price="{{ $sub->price }}"
                                            data-start="{{ $sub->start_date?->format('Y-m-d') }}"
                                            data-end="{{ $sub->end_date?->format('Y-m-d') }}"
                                            data-email="{{ $sub->user->email }}"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.subscriptions.destroy',$sub) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Plan Modal --}}
<div class="modal fade" id="addPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('admin.subscription-plans.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Plan Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Slug *</label>
                            <input type="text" name="slug" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" class="form-control" min="1">
                            <small class="text-muted">Leave empty for lifetime plan</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Features (one per line)</label>
                    <textarea name="features" class="form-control" rows="4" placeholder="Unlimited game access&#10;Premium support&#10;Early access to new games"></textarea>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active_add" checked>
                    <label class="form-check-label" for="is_active_add">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Plan Modal --}}
<div class="modal fade" id="editPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="edit-plan-form" method="POST">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-plan-id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Plan Name *</label>
                            <input type="text" name="name" id="edit-plan-name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Slug *</label>
                            <input type="text" name="slug" id="edit-plan-slug" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" id="edit-plan-description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" name="price" id="edit-plan-price" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" id="edit-plan-duration" class="form-control" min="1">
                            <small class="text-muted">Leave empty for lifetime plan</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Features (one per line)</label>
                    <textarea name="features" id="edit-plan-features" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="edit-plan-active" class="form-check-input">
                    <label class="form-check-label" for="edit-plan-active">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Subscription Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content needs-validation" id="edit-subscription-form" novalidate>
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Edit User Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-id">
                <div class="mb-3">
                    <label>Plan *</label>
                    <select name="plan" class="form-control" id="modal-plan" required>
                        <option value="">-- Select Plan --</option>
                        @foreach($subscriptionPlans as $plan)
                            <option value="{{ $plan->slug }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Plan is required.</div>
                </div>
                <div class="mb-3">
                    <label>Status *</label>
                    <select name="status" class="form-control" id="modal-status" required>
                        <option value="">-- Select Status --</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                    </select>
                    <div class="invalid-feedback">Status is required.</div>
                </div>
                <div class="mb-3">
                    <label>Description *</label>
                    <input type="text" name="description" id="modal-description" class="form-control" required minlength="5">
                    <div class="invalid-feedback">Description is required (min 5 characters).</div>
                </div>
                <div class="mb-3">
                    <label>Price (₹) *</label>
                    <input type="number" name="price" id="modal-price" class="form-control" min="1" required>
                    <div class="invalid-feedback">Enter a valid price greater than 0.</div>
                </div>
                <div class="mb-3">
                    <label>Start Date *</label>
                    <input type="date" name="start_date" id="modal-start" class="form-control" required>
                    <div class="invalid-feedback">Start date is required.</div>
                </div>
                <div class="mb-3">
                    <label>End Date (only for monthly plan)</label>
                    <input type="date" name="end_date" id="modal-end" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit Plan Modal
document.querySelectorAll('.edit-plan-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        document.getElementById('edit-plan-id').value = id;
        document.getElementById('edit-plan-name').value = btn.dataset.name;
        document.getElementById('edit-plan-slug').value = btn.dataset.slug;
        document.getElementById('edit-plan-description').value = btn.dataset.description;
        document.getElementById('edit-plan-price').value = btn.dataset.price;
        document.getElementById('edit-plan-duration').value = btn.dataset.duration || '';
        document.getElementById('edit-plan-active').checked = btn.dataset.active === '1';
        
        // Handle features
        const features = JSON.parse(btn.dataset.features || '[]');
        document.getElementById('edit-plan-features').value = features.join('\n');
        
        // Set form action
        document.getElementById('edit-plan-form').action = `/admin/subscription-plans/${id}`;
    });
});

// Edit User Subscription Modal
const editButtons = document.querySelectorAll('.edit-btn');

editButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        document.getElementById('modal-id').value = id;
        document.getElementById('modal-plan').value = btn.dataset.plan;
        document.getElementById('modal-status').value = btn.dataset.status;
        document.getElementById('modal-description').value = btn.dataset.description;
        document.getElementById('modal-price').value = btn.dataset.price;
        document.getElementById('modal-start').value = btn.dataset.start;
        document.getElementById('modal-end').value = btn.dataset.end;
    });
});

// Validation + AJAX for User Subscription
document.getElementById('edit-subscription-form').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;

    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add("was-validated");
        return;
    }

    const id = document.getElementById('modal-id').value;

    fetch(`/admin/subscriptions/${id}/update`, {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            plan: document.getElementById('modal-plan').value,
            status: document.getElementById('modal-status').value,
            description: document.getElementById('modal-description').value,
            price: document.getElementById('modal-price').value,
            start_date: document.getElementById('modal-start').value,
            end_date: document.getElementById('modal-end').value
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            const row = document.getElementById(`row-${id}`);
            row.querySelector('.row-plan').textContent = data.subscription.plan.charAt(0).toUpperCase() + data.subscription.plan.slice(1);
            row.querySelector('.row-status').textContent = data.subscription.status.charAt(0).toUpperCase() + data.subscription.status.slice(1);
            row.querySelector('.row-description').textContent = data.subscription.description;
            row.querySelector('.row-price').textContent = data.subscription.price;
            row.querySelector('.row-start').textContent = data.subscription.plan === 'monthly' ? (data.subscription.start_date ?? '-') : '-';
            row.querySelector('.row-end').textContent = data.subscription.plan === 'monthly' ? (data.subscription.end_date ?? '-') : 'Lifetime';

            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            alert(data.message);
        }
    })
    .catch(err => console.log(err));
});
</script>

@endsection