@extends('layouts.dashboard')

@section('content')

@section('page-css')
    <link href="{{ asset('dashui/assets/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">

@endsection

@section('page-js')
   <script src="{{ asset('dashui/assets/libs/toastr/build/toastr.min.js') }}"></script>

    @foreach($notifications as $notify)
<script >
 
// mark deactivate
$("#Deactivate{{$notify->notify_id}}").click(function(){
    toastr.info('Loading', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 1000 });
markDeactivate{{$notify->notify_id}}()
});
$("#Activate{{$notify->notify_id}}").click(function(){
    toastr.info('Loading', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 1000 });
markActivate{{$notify->notify_id}}()
});

        function markDeactivate{{$notify->notify_id}}() {
            var notify_id = {{ $notify->notify_id}};
            var notify_status = 0;
            $.ajax({
                type: 'POST',
                url: '{{ route('notify_status', $notify->notify_id) }}',
                data: { notify_id: notify_id, notify_status: notify_status, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('The notify has been disabled', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                },
                error: function (data) {
                    toastr.error('An error occured, check backend code or db', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });

                }
            });
        }
        function markActivate{{$notify->notify_id}}() {
            var notify_id = {{ $notify->notify_id}};
            var notify_status = 1;
            $.ajax({
                type: 'POST',
                url: '{{ route('notify_status', $notify->notify_id) }}',
                data: { notify_id: notify_id, notify_status: notify_status, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success('The notify has been disabled', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                },
                error: function (data) {
                    toastr.error('An error occured, check backend code or db', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });

                }
            });
        }
  
</script>
@endforeach
@endsection
<div class="container-fluid">

<div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">Notifications</h5>
                                        <h6 class="card-subtitle">Showing All notifys</h6>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                        <div class="input-field dl support-select">
                                        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Send New Notification</a>
                                        </div>
                                    </div>
                                <div class="table-responsive m-b-20">
                                    <table class="">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>notify points</th>
                                                <th>notify url</th>
                                                <th>notify type</th>
                                                <th>notify views</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($notifications as $notify)
                                            <tr>
                                                <td>
                                                    <p class="">{{ $notify->notify_des }}</p>
                                                </td>
                                                <td>
                                                    <p class="">{{ $notify->notify_points }}</p>
                                                </td>
                                                <td >
                                                {{ $notify->notify_url }}
                                                </td>
                                                <td>
                                                {{ $notify->notify_type}}
                                                </td>
                                                <td>
                                                {{ $notify->notify_views}}
                                                </td>
                                                <td class="green-text"><i class="fa fa-arrow-up"></i>
                                                @if($notify->notify_status == 1)
                                                <a id="Deactivate{{$notify->notify_id}}" href="javascript: void(0);">Deactivate</a> 
                                            <br>
                                           
                                            @else
                                            <a id="Activate{{$notify->notify_id}}" href="javascript: void(0);">Activate</a> 

                                            <br>
                            
                                                @endif
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

                <!-- Modal Trigger -->

<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Add Challenge</h4>


        
        <div class="row">
            <form class="col s12" method="POST" action="{{route('send_notify')}}">
                    @csrf
                    <input type="hidden" name="general_notify" value="1">
        <div class="row">
            <div class="input-field col s6">
            <input type="text" id="notify_des" value="{{ old('notify_des') }}" name="notify_des">
                {!! e_form_error('notify_des', $errors) !!}
                <label for="notify_des">Notification des</label>
            </div>
            <div class="input-field col s6">
            <input type="text" id="notify_points" value="{{ old('notify_points') }}" name="notify_points" >
            {!! e_form_error('notify_points', $errors) !!}
            <label for="notify_points">Notification Points</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
                <select  name="notify_type" class="browser-default">
                    <option value="" >Pick type</option>
                    <option value="featured">rate</option>
                    <option value="data">sponsored</option>
                    <option value="challenge">challenge</option>
                    <option value="topic">Topic</option>
                    <option value="product">product</option>
                </select>
            </div>
            <div class="input-field col s6">
            <input type="text" id="notify_url" value="{{ old('notify_url') }}" name="notify_url" >
            {!! e_form_error('notify_url', $errors) !!}
            <label for="notify_url">Notification url</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
                <select  name="product_id" class="browser-default">
                <option value="" >Pick product</option>
                   @foreach($products as $product)
                    <option value="{{$product->id}}">{{$product->product_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-field col s6">
            <select  name="challenge_id" class="browser-default">
            <option value="" >Pick challenge</option>
                   @foreach($challenges as $challenge)
                    <option value="{{$challenge->id}}">{{$challenge->challenge_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6">
            <select  name="topic_id" class="browser-default">
            <option value="" >Pick Topic</option>
                   @foreach($topics as $topic)
                    <option value="{{$topic->id}}">{{$topic->topic_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
  

        <Button class="waves-effect waves-light btn red" type="submit">Submit</Button>

    </form>
</div>

    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>

   

</div>


@endsection