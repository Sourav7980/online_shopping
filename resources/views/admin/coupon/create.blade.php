@extends('admin.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">					
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Create Coupon Code</h1>
			</div>
			<div class="col-sm-6 text-right">
				<a href="{{ route('categories.index')}}" class="btn btn-primary">Back</a>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="container-fluid">
		<form action="" method="post" id="discountFrom" name="discountFrom" >
		<div class="card">
			<div class="card-body">								
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label for="name">Code</label>
							<input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code">
							<p></p>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label for="slug">Name</label>
							<input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name">	
							<p></p>
						</div>
					</div>
					
                    <div class="col-md-6">
						<div class="mb-3">
							<label for="slug">Max Uses</label>
							<input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">	
							<p></p>
						</div>
					</div>
                    <div class="col-md-6">
						<div class="mb-3">
                    <label for="slug">Max Uses User</label>
							<input type="text" name="max_uses_uses" id="max_uses_uses" class="form-control" placeholder="Max Uses User">	
							<p></p>
						</div>
					</div>

                    <div class="col-md-6">
						<div class="mb-3">
							<label for="status">Type</label>
							<select name="type" id="type" class="form-control">
								<option value="percent">Percent</option>
								<option value="fixed">Fixed</option>
							</select>	
						</div>
					</div>
                    <div class="col-md-6">
						<div class="mb-3">
                    <label for="slug">Amount</label>
							<input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">	
							<p></p>
						</div>
					</div>

                    <div class="col-md-6">
						<div class="mb-3">
                    <label for="slug">Min Amount</label>
							<input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">	
							<p></p>
						</div>
					</div>    

					<div class="col-md-6">
						<div class="mb-3">
							<label for="status">Status</label>
							<select name="status" id="status" class="form-control">
								<option value="1">Active</option>
								<option value="0">Block</option>
							</select>	
						</div>
					</div>
                    
                    <div class="col-md-6">
						<div class="mb-3">
                    <label for="slug">Starts At</label>
							<input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At">	
							<p></p>
						</div>
					</div>    

                    <div class="col-md-6">
						<div class="mb-3">
                    <label for="slug">End At</label>
							<input type="text" name="ends_at" id="ends_at" class="form-control" placeholder="End At">	
							<p></p>
						</div>
					</div>    

                    <div class="col-md-6">
						<div class="md-3">
                        <label for="slug">Description</label>
                        <textarea class="from-control" name="description" id=""	cols="" rows="10"></textarea>
							<p></p>
						</div>
					</div>

				</div>
			</div>							
		</div>
		<div class="pb-5 pt-3">
			<button type= "submit" class="btn btn-primary">Create</button>
			<a href="{{ route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')
    <script>

$(document).ready(function(){
            $('#starts_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });


        $(document).ready(function(){
            $('#ends_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });

        $("#discountFrom").submit(function(event){
			event.preventDefault();
			var element = $(this);

			$("button[type=submit]").prop('disabled',true);

			$.ajax({
				url:'{{ route("coupons.store") }}',
				type: 'post',
				data: element.serializeArray(),
				dataType: 'json',
				success: function(response){
					$("button[type=submit]").prop('disabled',false);

					if(response["status"] == true){

						window.location.href="{{route('coupons.index')}}"
						$("#code").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");

						$("#amount").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");

						$("#starts_at").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");

						$("#end_at").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");

					}else{
						var errors= response['errors'];

					if(errors['code']){
						$("#code").addClass('is-invalid')
						.siblings('p')
						.addClass('invalid-feedback').html(errors['code']);
					}else{
						$("#code").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");
					}

					if(errors['amount']){
						$("#amount").addClass('is-invalid')
						.siblings('p')
						.addClass('invalid-feedback').html(errors['amount']);
					}else{
						$("#amount").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");
					}

					if(errors['starts_at']){
						$("#starts_at").addClass('is-invalid')
						.siblings('p')
						.addClass('invalid-feedback').html(errors['starts_at']);
					}else{
						$("#starts_at").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");
					}

					if(errors['end_at']){
						$("#end_at").addClass('is-invalid')
						.siblings('p')
						.addClass('invalid-feedback').html(errors['end_at']);
					}else{
						$("#end_at").removeClass('is-invalid')
						.siblings('p')
						.removeClass('invalid-feedback').html("");
					}

					}

				}, error: function(jqXHR, exception){
					console.log("Something went wrong");
				}
			})
		});
    </script>
@endsection