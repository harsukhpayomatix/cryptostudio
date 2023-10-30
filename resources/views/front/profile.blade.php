@extends('layouts.user.default')

@section('title')
Settings
@endsection

@section('breadcrumbTitle')
<a href="{{ route('dashboardPage') }}">Dashboard</a> / Edit Settings
@endsection

@section('content')
<div class="row">      
    <div class="col-xl-6 col-lg-12">
        {{ Form::model($data, ['route' => ['update-user-profile', $data->id], 'method' => 'patch','id'=>'profile-form', 'class'=>'form-dark']) }}
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Personal Info</h4>
                </div>
            </div>

            <div class="card-body">
                <div class="basic-form mb-2">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label> Name</label>
                            <input class="form-control" type="text" name="name" placeholder="Enter Name" value="{{$data->name}}">
                            @if ($errors->has('name'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input class="form-control" type="email" name="email" placeholder="Enter Email" value="{{$data->email}}" {{(!empty($data->token))?'disabled':''}}>
                            @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                            @if((!empty($data->email_changes)))
                                <div class="text-right">
                                    <code>Note:-Your email change request has been pending.</code>

                                    <a href="{{ route('resend.profile') }}" class="btn btn-danger text-right"> Resend Mail </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes </button>
                <a href="javascript:;" class="btn btn-danger">Cancel</a>
            </div>        
        </div>
        {{ Form::close() }}
    </div>        
</div>
@endsection
@section('script')
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/front/profile.js') }}"></script>
@endsection
