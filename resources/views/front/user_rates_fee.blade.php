@extends('layouts.user.default')

@section('title')
    Rates & Fee Details
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / Rates & Fee Details
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h4>User Rates & Fee Details</h4>
        </div>
        <div class="card-body">
            @include('partials.user.user_fee')
        </div>
    </div>
@endsection
