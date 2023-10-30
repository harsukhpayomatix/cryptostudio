@extends('layouts.user.default')

@section('title')
    Rates & Fee
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / Rates & Fee
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h4>User Rates & Fee</h4>
        </div>
        <div class="card-body">
            @include('partials.user.user_fee')
        </div>
    </div>
@endsection
