@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Profile</h4>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-xxl-4">
        <div class="card">
            <div class="card-body p-0">
                <div class="user-profile-img">
                    <img src="{{asset('imgs/pattern-bg.jpg')}}" class="profile-img profile-foreground-img rounded-top" style="height: 120px;" alt="">
                    <div class="overlay-content rounded-top">
                        <div>
                            <div class="user-nav p-3">
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <a class="text-muted dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                            <i class="bx bx-dots-vertical text-white font-size-20"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 pt-0">
                    <div class="mt-n5 position-relative text-center border-bottom pb-3">
                        <img src="{{$avatar}}" alt="" class="avatar-xl rounded-circle img-thumbnail">
                        <div class="mt-3">
                           <h5 class="mb-1">{{$user->name}}</h5>
                        </div>
                    </div>
                    
                    <div class="table-responsive mt-3 border-bottom pb-3">
                        <table class="table align-middle table-sm table-nowrap table-borderless table-centered mb-0">
                            <tbody>
                                <tr>
                                    <th class="fw-bold">Fullname:</th>
                                    <td class="text-muted">{{$user->name ?? 'n/a'}}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold">Email:</th>
                                    <td class="text-muted">{{$user->email ?? 'n/a'}}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold">Birth:</th>
                                    <td class="text-muted">{{$user->birth ?? 'n/a'}}</td>
                                </tr>
                                
                                <tr>
                                    <th class="fw-bold">Phone:</th>
                                    <td class="text-muted">{{$user->phone ?? 'n/a'}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 mt-3">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <div class="p-1">
                                    <h5 class="mb-1">{{ $totalEvent }}</h5>
                                    <p class="text-muted mb-0">Events</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-1">
                                    <h5 class="mb-1">0</h5>
                                    <p class="text-muted mb-0">Followers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-1 text-center">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="javascript:void()" class="social-list-item bg-primary text-white border-primary">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void()" class="social-list-item bg-info text-white border-info">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void()" class="social-list-item bg-danger text-white border-danger">
                                    <i class="bx bxl-google"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-8">
        <div class="tab-content">
            <div role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{$flag ? 'active' : ''}}" data-bs-toggle="tab" href="#info" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Info</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$flagInfo ? 'active' : ''}}" data-bs-toggle="tab" href="#edit-info" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Edit Profile</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$flagEmail ? 'active' : ''}}" data-bs-toggle="tab" href="#edit-email" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Edit Email</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$flagPass ? 'active' : ''}}" data-bs-toggle="tab" href="#edit-password" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Edit Password</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#event" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Events</span>    
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            @include('cws.profiles._edit_info', ['flagInfo' => $flagInfo])
                            @include('cws.profiles._edit_email', ['flagEmail' => $flagEmail])
                            @include('cws.profiles._edit_password', ['flagPass' => $flagPass])
                            <div class="tab-pane" id="event" role="tabpanel">
                                <p class="mb-0">
                                    Edit 
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
