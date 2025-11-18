@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Addresses</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="fas fa-plus me-2"></i>ADD A NEW ADDRESS
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($addresses->count())
        <div class="row">
            @foreach($addresses as $address)
                <div class="col-12 mb-3">
                    <div class="card border">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <input type="radio" name="default_address" value="{{ $address->id }}"
                                           {{ $address->is_default ? 'checked' : '' }}
                                           onchange="setDefaultAddress({{ $address->id }})"
                                           class="form-check-input">
                                </div>
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            @if($address->is_default)
                                                <span class="badge bg-primary mb-2 me-2">DEFAULT</span>
                                            @endif
                                            <h6 class="mb-1">{{ $address->name }}</h6>
                                            <p class="mb-1 text-muted">{{ $address->street }}</p>
                                            <p class="mb-1 text-muted">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                                            <p class="mb-0 text-muted"><strong>Mobile:</strong> {{ $address->phone }}</p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editAddressModal"
                                                    onclick="editAddress({{ $address->id }}, '{{ $address->name }}', '{{ $address->phone }}', '{{ $address->street }}', '{{ $address->city }}', '{{ $address->state }}', '{{ $address->pincode }}', {{ $address->is_default ? 'true' : 'false' }})">
                                                EDIT
                                            </button>
                                            <form action="{{ route('account.addresses.delete', $address) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    REMOVE
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-map-marker-alt text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-3">No addresses found</h4>
            <p class="text-muted mb-4">Add a new address to get started</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                <i class="fas fa-plus me-2"></i>ADD A NEW ADDRESS
            </button>
        </div>
    @endif
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="street" class="form-label">Street Address</label>
                        <textarea class="form-control" id="street" name="street" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                        <label class="form-check-label" for="is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_street" class="form-label">Street Address</label>
                        <textarea class="form-control" id="edit_street" name="street" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="edit_state" name="state" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="edit_pincode" name="pincode" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_default" name="is_default" value="1">
                        <label class="form-check-label" for="edit_is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAddress(id, name, phone, street, city, state, pincode, isDefault) {
    document.getElementById('editAddressForm').action = `/account/addresses/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_street').value = street;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_state').value = state;
    document.getElementById('edit_pincode').value = pincode;
    document.getElementById('edit_is_default').checked = isDefault;
}

function setDefaultAddress(addressId) {
    fetch(`/account/addresses/${addressId}/set-default`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to set default address');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while setting the default address');
    });
}
</script>
@endsection
