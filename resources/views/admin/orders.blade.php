@extends('layouts.dashboard')

@section('content')
@section('page-css')
<link href="{{ asset('dashui/assets/libs/footable/css/footable.core.css') }}" rel="stylesheet">
    <link href="{{ asset('dashui/dist/css/pages/footable-page.css') }}" rel="stylesheet">
    <link href="{{ asset('dashui/assets/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">

@endsection
@section('page-js')
    <script src="{{ asset('dashui/assets/libs/footable/dist/footable.all.min.js') }}"></script>
    <script src="{{ asset('dashui/dist/js/pages/footable/footable-init.js') }}"></script>
    <script src="{{ asset('dashui/assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('dashui/assets/extra-libs/toastr/toastr-init.js') }}"></script>

    @foreach($orders as $order)
<script >
 
// mark completed
$("#markComplete{{$order->id}}").click(function(){
    toastr.info('Loading', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 1000 });
var notify_success = 'Your order {{ $order->product_name }} has been completed, please contact support for further inquires'
markComplete{{$order->id}}(notify_success)
});

        function markComplete{{$order->id}}(notify_success) {
            var order_id = {{ $order->id}};
            var order_status = 'completed';
            $.ajax({
                type: 'POST',
                url: '{{ route('order_status', $order->id) }}',
                data: { order_id: order_id, order_status: order_status, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('The order has been approved', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    sendNotification{{$order->id}}(notify_success);
                },
                error: function (data) {
                    toastr.error('An error occured, check backend code or db', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });

                }
            });
        }
        function sendNotification{{$order->id}}(notify_success) {
            var order_id = {{ $order->id}};
            var user_id = {{ $order->user_id}};
            var product_id = {{ $order->product_id}};
            var notify_des = notify_success;
            var notify_type = 'data';
            $.ajax({
                type: 'POST',
                url: '{{ route('send_notify')}}',
                data: { order_id: order_id,
                         user_id: user_id,
                         product_id: product_id,
                         notify_des: notify_des,
                         notify_type: notify_type,
                         _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('Notification sent to user', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
            });
        }
// mark suspended
$("#markSuspended{{$order->id}}").click(function(){
    toastr.info('Loading', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 1000 });
var notify_sad = 'Your order {{ $order->product_name }} has been placed under review, please contact support for further inquires'
markSuspended{{$order->id}}(notify_sad)
});

        function markSuspended{{$order->id}}(notify_sad) {
            var order_id = {{ $order->id}};
            var order_status = 'suspended';
            $.ajax({
                type: 'POST',
                url: '{{ route('order_status', $order->id) }}',
                data: { order_id: order_id, order_status: order_status, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('The order has been suspended', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    sendSadNotification{{$order->id}}(notify_sad);
                },
                error: function (data) {
                    toastr.error('An error occured, check backend code or db', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });

                }
            });
        }
        function sendSadNotification{{$order->id}}(notify_sad) {
            var order_id = {{ $order->id}};
            var user_id = {{ $order->user_id}};
            var product_id = {{ $order->product_id}};
            var notify_des = notify_sad;
            var notify_type = 'data';
            $.ajax({
                type: 'POST',
                url: '{{ route('send_notify')}}',
                data: { order_id: order_id,
                         user_id: user_id,
                         product_id: product_id,
                         notify_des: notify_des,
                         notify_type: notify_type,
                         _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('Notification sent to user', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
            });
        }
</script>
@endforeach
@endsection
<div class="page-titles">
                <div class="d-flex align-items-center">
                    <h5 class="font-medium m-b-0">All orders</h5>
                    <div class="custom-breadcrumb ml-auto">
                        <a href="#!" class="breadcrumb">Home</a>
                        <a href="#!" id="p"class="breadcrumb">Orders</a>
                    </div>
                </div>
            </div>
<div class="container-fluid">

    <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">orders</h5>
                                    </div>
                                </div>
                                
                                    <div class="table-responsive">
                                        <table id="demo-foo-addrow2" class="table table-bordered table-hover toggle-circle" data-page-size="7">
                                    <thead>
                                        <tr>
                                             <th>Full Name</th>
                                                <th>Email</th>
                                                <th>phone</th>
                                                <th>Product Name</th>
                                                <th>Product Points</th>
                                                <th>Point Paid</th>
                                                <th data-sort-initial="true" data-toggle="false">Status</th>
                                                <th>Date</th>
                                        </tr>
                                    </thead>
                                    <div class="m-t-40">
                                        <div class="d-flex">
                                            <div class="mr-auto">
                                                
                                            </div>
                                            <div class="ml-auto">
                                                <div class="form-group">
                                                    <input id="demo-input-search2" type="text" placeholder="Search" autocomplete="on">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <tbody>
                                            @foreach($orders as $order)

                                            <tr>
                                                <td> 
                                                <div class="chip">
                                                 {{ $order->name }}
                                                    </div>
                                                </td>
                                                <td>{{ $order->email }}</td>
                                                <td>{{ $order->user_phone }} <br>
                                                       @if($order->phone_verify == null)
                                                       <small>not verified</small>                                    
                                                       @else
                                                       <small>verified</small>
                                                       @endif
                                                </td>
                                                <td>{{ $order->product_name }}</td>
                                                <td>{{ $order->product_points }}</td>
                                                <td>{{ $order->user_point_paid }}</td>
                                                @if($order->order_status == 'completed')
                                                <td><span class="label label-success">completed</span> <br>
                                                <a id="markSuspended{{$order->id}}" href="javascript: void(0);">Suspend</a> 
                                                </td>
                                                @elseif($order->order_status == 'suspended')
                                                <td><span class="label label-warning">suspended</span><br>
                                                <a id="markComplete{{$order->id}}" href="javascript: void(0);">complete</a> 
                                                </td>                                             
                                                @else
                                                <td><span class="label label-warning">pending</span><br>
                                                <a id="markComplete{{$order->id}}" href="javascript: void(0);">complete</a> 
                                                </td>                                               
                                                @endif
                                                <td>{{$order->created_at}}</td>
                                            </tr>
                                          
                                           @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>points</th>
                                                <th>Challenges</th>
                                                <th>phone</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>


</div>



@endsection