@extends('layouts.dashboard')


@section('content')
@section('page-css')
<link href="{{ asset('dashui/assets/libs/footable/css/footable.core.css') }}" rel="stylesheet">
    <link href="{{ asset('dashui/dist/css/pages/footable-page.css') }}" rel="stylesheet">
@endsection

    <div class="container-fluid">

<div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">Users</h5>
                                        <h6 class="card-subtitle">Showing all users strating from latest</h6>
                                    </div>
                                </div>
                                
                                    <div class="table-responsive">
                                        <table id="demo-foo-addrow2" class="table table-bordered table-hover toggle-circle" data-page-size="7">
                                    <thead>
                                        <tr>
                                             <th>User Name</th>
                                                <th>Email</th>
                                                <th data-sort-initial="true" data-toggle="false">points</th>
                                                <th >Challenges</th>
                                                <th>phone</th>
                                                <th>Status</th>
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
                                            @foreach($users as $user)
                                            <tr>
                                                <td> 
                                                <div class="chip">
                                                        <img src="/storage/uploads/images/user/{{ $user->photo }}" > {{ $user->name }}
                                                    </div>                                            
                                                            @if($user->active_status == 0)
                                                            <form class="col s12" method="POST" action="{{ route('user_status', $user->id) }}" >
                                                @csrf
                                                <input type="hidden" value="1" name="active_status">
                                            <button class="waves-effect waves-light btn btn-small  blue" type="submit"><i class="material-icons">save</i></button>
                                               </form>
                                               @else
                                               <form class="col s12" method="POST" action="{{ route('user_status', $user->id) }}" >
                                                @csrf
                                                <input type="hidden" value="0" name="active_status">
                                            <button class="waves-effect waves-light btn btn-small  red" type="submit"><i class="material-icons">block</i></button>
                                               </form>
                                               @endif
                                                            <span><a href="#" >contact</a></span>
                                                
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->points }}</td>
                                                <td>{{ $user->completed_challenges }}</td>
                                                <td>{{ $user->phone }}</td>
                                                @if($user->active_status == 0)
                                                <td>Suspended</td>
                                                @else
                                                <td>Active</td>
                                                @endif
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

@section('page-js')
 
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script src="{{ asset('dashui/assets/libs/footable/dist/footable.all.min.js') }}"></script>
    <script src="{{ asset('dashui/dist/js/pages/footable/footable-init.js') }}"></script>
    @endsection
@endsection