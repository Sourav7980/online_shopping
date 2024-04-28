@extends('front.layout.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile')}}">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm" method="POST">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="mb-3">
                                <label for="name">name</label>
                                <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Enter Your name" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                <p></p>
                            </div>

                            <div class="d-flex">
                                <button class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm" method="POST">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">First name</label>
                                <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Enter Your First name" class="form-control">
                                <p></p>
                            </div>
                            <div class=" col-md-6 mb-3">
                                <label for="name">Last name</label>
                                <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Enter Your Last name" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input value="{{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile." class="form-control">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="phone">country_id</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Select a country_id</option>
                                    @if($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{ (!empty($address) && $address->country_id == $country->id ) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>

                            <div class="mb-3">
                                <label for="phone">Address</label>
                                <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                <p></p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Apartment</label>
                                <input value="{{ (!empty($address)) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="apartment" class="form-control">
                                <p></p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">City</label>
                                <input value="{{ (!empty($address)) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="City" class="form-control">
                                <p></p>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="phone">State</label>
                                <input value="{{ (!empty($address)) ? $address->state : '' }}" type="text" name="state" id="state" placeholder="State" class="form-control">
                                <p></p>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="phone">Zip</label>
                                <input value="{{ (!empty($address)) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Zip" class="form-control">
                                <p></p>
                            </div>

                            <div class="d-flex">
                                <button class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#profileForm").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route("account.updateProfile")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                if(response.status == true){

                    $("#profileForm #name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#profileForm #email").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#profileForm #phone").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       window.location.href='{{ route("account.profile")}}';


                }else{
                    var errors = response.errors;
                    if (errors.name) {
                    $("#profileForm #name").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.name);
                    } else {
                    $("#profileForm #name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                    }

                    if (errors.email) {
                    $("#profileForm #email").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.email);
                    } else {
                    $("#profileForm #email").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                    }

                    if (errors.phone) {
                    $("#profileForm #phone").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.phone);
                   } else {
                    $("#profileForm #phone").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }
                }
            }
        });
    });

    $("#addressForm").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route("account.updateAddress")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                if(response.status == true){

                    $("#first_name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#last_name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#addressForm #email").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#mobile").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#country_id").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#address").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#apartment").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#city").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#state").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       $("#zip").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');

                       window.location.href='{{ route("account.profile")}}';


                }else{
                    var errors = response.errors;

                    if (errors.first_name) {
                    $("#first_name").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.first_name);
                   } else {
                    $("#first_name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }
                   if (errors.last_name) {
                    $("#last_name").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.last_name);
                   } else {
                    $("#last_name").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.email) {
                    $("#addressForm #email").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.email);
                   } else {
                    $("#addressForm #email").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.mobile) {
                    $("#mobile").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.mobile);
                   } else {
                    $("#mobile").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.country_id) {
                    $("#country_id").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.country_id);
                   } else {
                    $("#country_id").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.address) {
                    $("#address").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.address);
                   } else {
                    $("#address").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.apartment) {
                    $("#apartment").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.apartment);
                   } else {
                    $("#apartment").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.city) {
                    $("#city").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.city);
                   } else {
                    $("#city").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.state) {
                    $("#state").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.state);
                   } else {
                    $("#state").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }

                   if (errors.zip) {
                    $("#zip").addClass('is-invalid')
                       .siblings('p')
                       .addClass('invalid-feedback').html(errors.zip);
                   } else {
                    $("#zip").removeClass('is-invalid')
                       .siblings('p')
                       .removeClass('invalid-feedback').html('');
                   }
                }
        }
        });
    });
</script>
@endsection
