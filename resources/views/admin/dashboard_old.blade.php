@extends('layouts.dashboard')


@section('content')

<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Sales Summery -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col l3 m6 s12">
                        <div class="card danger-gradient card-hover">
                            <div class="card-content">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        {{-- <h2 class="white-text m-b-5">{{$topicCount}}</h2> --}}
                                        <h6 class="white-text op-5 light-blue-text">TOpics</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="white-text display-6"><i class="material-icons">assignment</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col l3 m6 s12">
                        <a href="{{route('users')}}">
                        <div class="card info-gradient card-hover">
                            <div class="card-content">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        {{-- <h2 class="white-text m-b-5">{{$usersCount}}</h2> --}}
                                        <h6 class="white-text op-5">Users</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="white-text display-6"><i class="material-icons">user</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                     
                    
                    <div class="col l3 m6 s12">
                    <a href="{{route('dashboard_challenges')}}">
                        <div class="card success-gradient card-hover">
                            <div class="card-content">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2 class="white-text m-b-5">{{$totalChallenge}}</h2>
                                        <h6 class="white-text op-5 text-darken-2">Challenges</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="white-text display-6"><i class="material-icons">equalizer</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col l3 m6 s12">
                    <a href="{{route('dashboard_orders')}}">
                        <div class="card warning-gradient card-hover">
                            <div class="card-content">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2 class="white-text m-b-5">{{$ordersCount}}</h2>
                                        <h6 class="white-text op-5">News Orders</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="white-text display-6"><i class="material-icons">attach_money</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col s12 l4">
                        <div class="card">
                            <div class="card-content">
                                <h5 class="card-title">Home Sliders</h5>
                                <div class="message-box">
                                    <div class="message-widget message-scroll">
                                        <!-- sliders -->
                                        @foreach($sliders as $slider)
                                        <a href="{{route('dashboard_sliders')}}">
                                            <div class="user-img"> <img src="/storage/uploads/images/sliders/{{ $slider->slider_image }}" > <span class="profile-status online pull-right"></span> </div>
                                            <div class="mail-contnet">
                                            @if($slider->challenge_id != null)
                                <h5>slider type: Challenge</h5>
                            @elseif($slider->topic_id != null)
                                <h5>slider type: Topic</h5>
                            @elseif($slider->fact_id != null)
                                <h5>slider type: Fact</h5>
                            @else($slider->slider_url != null)
                                <h5>slider url: {{$slider->slider_url}}</h5>
                            @endif
                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 l4">
                        <div class="card">
                            <div class="card-content">
                                <h5 class="card-title">Noticy Board</h5>
                                <div class="message-box">
                                    <div class="message-widget message-scroll">
                                       @foreach($notifies as $notify)
                                       @if($notify->general_notify == true)
                                        <a href="{{route('dashboard_notify')}}">
                                            <div class="mail-contnet">
                                                <h5>{{$notify->notify_type}}</h5> <span class="mail-desc">{{$notify->notify_des}} </span></div>
                                        </a>
                                        @endif
                                        @endforeach
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 l4">
                        <div class="card">
                            <div class="card-content">
                                <h5 class="card-title">Recent Orders</h5>
                                <div class="message-box">
                                    <div class="message-widget message-scroll">
                                    @foreach($orders as $order)
                                        <a href="{{route('dashboard_orders')}}">
                                            <div class="user-img"> <img src="/storage/uploads/images/products/{{ $order->product_img }}" alt="user" class="circle"> </div>
                                            <div class="mail-contnet">
                                                <h5>{{$order->name}}</h5> <span class="mail-desc"> {{$order->product_name}}</span> <span class="time">{{$order->created_at}}</span> </div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- ============================================================== -->
                <!-- Full list of users leaderboard -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="card-title">Leaderboard</h5>
                                        <h6 class="card-subtitle">Showing top players</h6>
                                    </div>
                                </div>
                                <div class="table-responsive m-b-20">
                                    <table class="">
                                        <thead>
                                            <tr>
                                                <th>Players Name</th>
                                                <th>Completed Challenges</th>
                                                <th>Points</th>
                                                <th>Phone</th>
                                                <th>Phone Verify</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                <a href="{{route('users_edit',$user->id)}}">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div class="m-r-10"><img src="/storage/uploads/images/user/{{ $user->photo }}" class="circle" width="45" /></div>
                                                        <div class="">
                                                            <h5 class="m-b-0 font-16 font-medium">{{$user->name}}</h5></div>
                                                    </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <p class="">{{$user->completed_challenges}}</p>
                                                </td>
                                                <td class="blue-grey-text text-darken-4 font-medium">{{$user->points}}</td>
                                                <td>{{$user->phone}}</td>
                                                <td><span class="label label-info">{{$user->phone_verify}}</span></td>                                               
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Recent comment -->
                    <div class="col s12 m12 l6">
                        <div class="card">
                            <div class="card-content">
                                <h5 class="card-title">Recent Nagative Reviews</h5>
                                <div class="comment-widgets scrollable" style="height:560px;">
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row">
                                        <div class="p-2"><img src="{{ asset('dashui/assets/images/users/1.jpg') }}" alt="user" width="50" class="circle"></div>
                                        <div class="comment-text w-100">
                                            <h6 class="font-medium">James Anderson</h6>
                                            <span class="m-b-15 db">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                            <div class="comment-footer">
                                                <span class="text-muted right">April 14, 2016</span> <span class="label label-info">Pending</span> <span class="action-icons">
                                                    <a href="javascript:void(0)"><i class="ti-pencil-alt"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-check"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-heart"></i></a>    
                                                </span> </div>
                                        </div>
                                    </div>
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row">
                                        <div class="p-2"><img src="{{ asset('dashui/assets/images/users/4.jpg') }}" alt="user" width="50" class="circle"></div>
                                        <div class="comment-text active w-100">
                                            <h6 class="font-medium">Michael Jorden</h6>
                                            <span class="m-b-15 db">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                            <div class="comment-footer ">
                                                <span class="text-muted right">April 14, 2016</span>
                                                <span class="label label-success">Approved</span>
                                                <span class="action-icons active">
                                                    <a href="javascript:void(0)"><i class="ti-pencil-alt"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon-close"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-heart text-danger"></i></a>    
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row">
                                        <div class="p-2"><img src="{{ asset('dashui/assets/images/users/5.jpg') }}" alt="user" width="50" class="circle"></div>
                                        <div class="comment-text w-100">
                                            <h6 class="font-medium">Johnathan Doeting</h6>
                                            <span class="m-b-15 db">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                            <div class="comment-footer">
                                                <span class="text-muted right">April 14, 2016</span>
                                                <span class="label label-warning">Rejected</span>
                                                <span class="action-icons">
                                                    <a href="javascript:void(0)"><i class="ti-pencil-alt"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-check"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-heart"></i></a>    
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row">
                                        <div class="p-2"><img src="{{ asset('dashui/assets/images/users/1.jpg') }}" alt="user" width="50" class="circle"></div>
                                        <div class="comment-text w-100">
                                            <h6 class="font-medium">James Anderson</h6>
                                            <span class="m-b-15 db">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                            <div class="comment-footer">
                                                <span class="text-muted right">April 14, 2016</span> <span class="label label-info">Pending</span> <span class="action-icons">
                                                    <a href="javascript:void(0)"><i class="ti-pencil-alt"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-check"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-heart"></i></a>    
                                                </span> </div>
                                        </div>
                                    </div>
                                    <!-- Comment Row -->
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row">
                                        <div class="p-2"><img src="{{ asset('dashui/assets/images/users/4.jpg') }}" alt="user" width="50" class="circle"></div>
                                        <div class="comment-text active w-100">
                                            <h6 class="font-medium">Michael Jorden</h6>
                                            <span class="m-b-15 db">Lorem Ipsum is simply dummy text of the printing and type setting industry. </span>
                                            <div class="comment-footer ">
                                                <span class="text-muted right">April 14, 2016</span>
                                                <span class="label label-success">Approved</span>
                                                <span class="action-icons active">
                                                    <a href="javascript:void(0)"><i class="ti-pencil-alt"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon-close"></i></a>
                                                    <a href="javascript:void(0)"><i class="ti-heart text-danger"></i></a>    
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comment Row -->
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>  

            @section('page-js')
              <!-- ============================================================== -->
    <script src="{{ asset('dashui/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('dashui/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('dashui/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dashui/dist/js/pages/dashboards/dashboard1.js') }}"></script>
    <!-- ============================================================== -->
   
    @endsection
@endsection