
@extends('admin.layout.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('shipping.create')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="{{ route('shipping.store') }}" method="post" id="shippingForm" name="shippingForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{($country->id==$shippingCharge->country_id)?'selected':''}}  value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                        <option {{('rest_of_world'==$shippingCharge->country_id)?'selected':''}} value="rest_of_world" >Rest of the world</option>
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input type="text" value="{{$shippingCharge->amount}}" name="amount" placeholder="Amount" id="amount" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- /.card -->
</section>
@endsection

@section('customjs')
<script>
    $("#shippingForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: '{{route("shipping.update",$shippingCharge->id)}}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] !== false){
                    window.location.href="{{route('shipping.create')}}";
                } else {
                    var errors = response['errors'];

                    if(errors['country']){
                        $("#country").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['country']);
                    } else {
                        $("#country").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    }

                    if(errors['amount']){
                        $("#amount").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['amount']);
                    } else {
                        $("#amount").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    }
                }
            },
            error: function(jqXHR, exception){
                console.log('Something went wrong');
            }
        });
    });
</script>
@endsection
