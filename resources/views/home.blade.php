@extends('layouts.user.default')

@section('title')
Home
@endsection

@section('breadcrumbTitle')
Home
@endsection

@section('customeStyle')
<script src="https://cdn.lordicon.com/libs/frhvbuzj/lord-icon-2.0.2.js"></script>
<style type="text/css">
    .dropdown.bootstrap-select.default-select {
        width: 130px !important;
    }
</style>
@endsection

@section('content')
@php
$transactionPermission = 0;
$settingsPermission = 0;
@endphp
@if(Auth()->user()->main_user_id != '0')
@if(Auth()->user()->transactions == '1')
@php
$transactionPermission = 1;
@endphp
@endif
@if(Auth()->user()->settings == '1')
@php
$settingsPermission = 1;
@endphp
@endif
@endif
@if(!empty(Auth::user()->application))
@if(Auth::user()->application->status == 4 || Auth::user()->application->status == 5 ||
Auth::user()->application->status == 6 || Auth::user()->application->status == 10 || Auth::user()->application->status
== 11)
@php
$transactionPermission = 1;
$settingsPermission = 1;
@endphp
@endif
@endif
@if($transactionPermission == 1)
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <div class="merchantTxnCard text-white" style="background-color: #82CD47;">
                    <h2 class="text-white">{{ round($transaction->successfullP,2) }} %</h2>
                    <p class="mb-1">Successful</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->successfullC }}</span></p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="merchantTxnCard text-white" style="background-color: #FF5858;">
                    <h2 class="text-white">{{round($transaction->declinedP,2)}} %</h2>
                    <p class="mb-1">Declined</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{$transaction->declinedC}}</span></p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
                    <h2 class="text-white">{{ round($transaction->suspiciousP,2) }} %</h2>
                    <p class="mb-1">Marked</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->suspiciousC }}</span></p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="merchantTxnCard text-white" style="background-color: #b16ee7;">
                    <h2 class="text-white">{{ round($transaction->refundP,2) }} %</h2>
                    <p class="mb-1">Refund</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->refundC }}</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h5 class="card-title">Recent Transactions</h5>
                </div>
                <div class="card-header-toolbar d-flex align-items-center">
                    <a href="{!! url('transactions') !!}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive custom-table" id="latestTransactionsData">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="badge-primary spinner-grow" style="width: 3rem;height:3rem;" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('customScript')
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            getDashboardData();
        }, 100);

        function getDashboardData() {
            $.ajax({
                url: '{{ route("get-user-dashbaord-data") }}',
                type: 'GET',
                success: function (data) {
                    $('#latestTransactionsData').html(data.latestTransactionsData);
                },
            });
        }
    });
</script>

@endsection