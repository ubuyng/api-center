@extends('layouts.dashboard')


@section('content')
    <div class="row">
        <div class="col-md-12">
<div class="wall">
<img src="/assets/images/{{$user->photo}}">
</div>
             <table class="table table-bordered table-striped mb-4">

                <tr>
                    <th>@lang('app.name')</th>
                    <td>{{ $user->name }}</td>
                </tr>

                <tr>
                    <th>@lang('app.email')</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Points</th>
                    <td>{{ ($user->points) }}</td>
                </tr>
                <tr>
                    <th>@lang('app.phone')</th>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Completed Challenges</th>
                    <td>{{ $user->completed_challenges }}</td>
                </tr>
                <tr>
                    <th>Phone status</th>
                    <td>
                        @if($user->phone_verify)
                            {{ $user->phone_verify }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Account Name</th>
                    <td>                      
                            {{ $user->account_name }}
                    </td>
                </tr>
                <tr>
                    <th>Account Number</th>
                    <td>                      
                            {{ $user->account_number}}
                    </td>
                </tr>
                <tr>
                    <th>Bank Name</th>
                    <td>                      
                            {{ $user->bank_name}}
                    </td>
                </tr>

                <tr>
                    <th>@lang('app.created_at')</th>
                    <td>{{ $user->signed_up_datetime() }}</td>
                </tr>
                <tr>
                    <th>@lang('app.status')</th>
                    <td>{{ $user->status_context() }}</td>
                </tr>
            </table>





            @if( ! empty($is_user_id_view))
                <a href="{{route('users_edit', $user->id)}}"><i class="la la-pencil-square-o"></i> @lang('app.edit') </a>
            @else
                <a href="{{ route('profile_edit') }}"><i class="la la-pencil-square-o"></i> @lang('app.edit') </a>
            @endif


        </div>
    </div>



@endsection