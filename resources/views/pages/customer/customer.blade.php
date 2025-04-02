@extends('layouts.users.app')

@section('content')
<div class="container mx-auto px-6 py-6">
    <div class="grid grid-cols-2 gap-6 max-w-4xl mx-auto">
        <!-- Personal Information Section (Left Side) -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-semibold text-black-150 mb-4 uppercase tracking-wide">
                Personal Information
            </h3>
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-3 text-sm" id="personal-info-form">
                @csrf
                @method('PUT')
            
                <div id="personal-display" class="space-y-3" @if($errors->any()) style="display: none;" @endif>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <span class="text-black-150 font-medium">Name:</span>
                        <span class="col-span-3 text-black-150">{{ $customer->name }}</span>
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <span class="text-black-150 font-medium">Email:</span>
                        <span class="col-span-3 text-black-150">{{ $customer->email }}</span>
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <span class="text-black-150 font-medium">Phone:</span>
                        <span class="col-span-3 text-black-150">{{ $customer->phone ?? 'Not provided' }}</span>
                    </div>
                    <button type="button" id="editPersonalBtn" class="mt-3 bg-red-600 text-white hover:bg-red-700 px-3 py-1 rounded-lg">
                        Edit
                    </button>
                </div>
            
                <div id="personal-edit" class=" @if(!$errors->any()) hidden @endif space-y-3">
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <label for="name" class="text-black-150 font-medium">Name:</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                            class="col-span-3 p-2 border rounded-md text-black-150 w-full"
                            disabled>
                        @error('name')
                            <span class="col-span-3 p-2 text-red-600 text-xs w-full">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <label for="email" class="text-gray-600 font-medium">Email:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                            class="col-span-3 p-2 border rounded-md text-black-150 w-full"
                            disabled>
                        @error('email')
                            <span class="col-span-3 text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-4 gap-3 items-center">
                        <label for="phone" class="text-gray-600 font-medium">Phone:</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                            placeholder="Not provided"
                            class="col-span-3 p-2 border rounded-md text-black-150 w-full"
                            disabled>
                        @error('phone')
                            <span class="col-span-3 text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex space-x-3 mt-3">
                        <button type="submit" class="mt-3 bg-red-600 text-white hover:bg-red-700 px-3 py-1 rounded-lg">
                            Save
                        </button>
                        <button type="button" id="cancelPersonalEditBtn" class="mt-3 bg-red-500 text-white px-3 py-1 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Addresses Section (Right Side) -->
        <div class="bg-gray-100 p-6 rounded-md shadow-md">
            <h3 class="text-sm font-semibold text-black-150 mb-3 uppercase tracking-wide">
                Your Addresses
            </h3>
            @if ($customer->addresses->isEmpty())
                <p class="text-gray-600 text-sm">No addresses found.</p>
            @else
                <div class="space-y-3" id="addresses-container">
                    @foreach ($customer->addresses as $address)
                        <div id="display-{{ $address->id }}" class="address-display flex justify-between items-center w-full border p-3 rounded-md mb-3">
                            <p class="text-black-150">{{ $address->address_line1 }}, {{ $address->city }}, {{ $address->state }}, {{ $address->postal_code }}, {{ $address->country }} ({{ $address->type }})</p>
                            <button type="button" class="edit-address-btn mt-3 bg-red-600 text-white hover:bg-red-700 px-3 py-1 rounded-lg"
                                data-address-id="{{ $address->id }}">
                                Edit
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Tailwind Modal for Address Edit -->
<div id="edit-address-modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Edit Address</h3>
        <form action="{{ route('customer.address.edit') }}" method="POST" id="edit-address-form">
            @csrf
            @method('PUT')
            <div class="space-y-3">
                <input type="hidden" name="addressId" id="addressId"/>
                <input type="text" name="address_line1" id="address_line1" class="w-full p-2 border rounded-md text-black-150 focus:ring-1 focus:ring-blue-500">
                <div class="grid grid-cols-3 gap-3">
                    <input type="text" name="city" id="city" class="p-2 border rounded-md text-black-150 focus:ring-1 focus:ring-blue-500">
                    <input type="text" name="state" id="state" class="p-2 border rounded-md text-black-150 focus:ring-1 focus:ring-blue-500">
                    <input type="text" name="zip_code" id="zip_code" class="p-2 border rounded-md text-black-150 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex items-center mt-3">
                    <input type="checkbox" name="is_default" id="is_default" class="mr-2"/>
                    <label for="is_default" class="text-gray-700">Set as Default Address</label>
                </div>
                <div class="flex space-x-3 mt-3">
                    <button type="submit" class="mt-3 bg-red-600 text-white hover:bg-red-700 px-3 py-1  rounded-lg">
                        Save
                    </button>
                    <button type="button" id="cancelAddressEditBtn" class="mt-3 bg-red-500 text-white px-3 py-1 rounded-lg">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Enable Edit functionality for Personal Information
        $('#editPersonalBtn').click(function () {
            $('#personal-display').hide();
            $('#personal-edit').removeClass('hidden');
            
            // Enable inputs to submit data
            $('#name, #email, #phone').prop('disabled', false);
        });

        // Cancel the edit and disable inputs again
        $('#cancelPersonalEditBtn').click(function () {
            $('#personal-edit').addClass('hidden');
            $('#personal-display').show();
            
            // Disable inputs to prevent submission
            $('#name, #email, #phone').prop('disabled', true);
        });

        // Edit Address functionality using AJAX
        $('.edit-address-btn').on('click', function() {
            const addressId = $(this).data('address-id');

            // Send AJAX request to fetch address data
            $.ajax({
                url: '{{ route('customer.address.fetch') }}', // Adjust this URL according to your route
                method: 'post',
                data:{
                    addressId:addressId,
                },
                success: function(response) {
                    const address = response.address;
                    console.log(address);
                    // Populate the form fields with address data
                    $('#addressId').val(address.id);
                    $('#address_line1').val(address.address_line1);
                    $('#city').val(address.city);
                    $('#state').val(address.state);
                    $('#zip_code').val(address.postal_code);
                    if (address.is_default == 1) {
                        $('#is_default').prop('checked', true);
                    }else{
                         $('#is_default').prop('checked', false);
                    };
                    $('#edit-address-modal').removeClass('hidden');
                },
                error: function() {
                    alert('Error fetching address data');
                }
            });
        });

        // Cancel Edit Address functionality
        $('#cancelAddressEditBtn').on('click', function() {
            $('#edit-address-modal').addClass('hidden');
        });
    });
</script>
@endsection
