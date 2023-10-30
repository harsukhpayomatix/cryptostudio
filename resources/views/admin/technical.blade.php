@extends('layouts.admin.default')
@section('title')
    Technical & Additional
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> /Technical & Additional
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Technical</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (auth()->guard('admin')->user()->can(['view-ip-whitelist']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">IP Whitelist</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('ip-whitelist') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-iframe-generator']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Link Generator</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('asp-iframe') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-transaction-session-data']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Transaction Session Data</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('transaction-session') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-transaction-session-data']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Payment API Data</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('admin.paymentApi') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-required-fields']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Required Fields</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{!! url('admin/required_fields') !!}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-merchant-rp-agreement']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Merchant & RP Agreement</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{!! url('admin/agreement_content') !!}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif
                        @if (auth()->guard('admin')->user()->can(['view-email-card-block-system']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Email & Card Block System</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('block-system') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <strong class="mt-2">Mass Transaction Action</strong>
                            <a class="btn btn-primary btn-sm pull-right"
                                href="{{ route('mass-transaction-action.index') }}">Go <i
                                    class="fa fa-angle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Additional</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (auth()->guard('admin')->user()->can(['view-payout-schedule']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Payout Schedule</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('payout-schedule.index') }}">Go
                                    <i class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-industry-type']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Industry Type</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('categories.index') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-integration-preference']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Integration Preference</strong>
                                <a class="btn btn-primary btn-sm pull-right"
                                    href="{{ route('integration-preference.index') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif

                        @if (auth()->guard('admin')->user()->can(['view-admin-logs']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Admin Logs</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('admin-logs.index') }}">Go <i
                                        class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif
                        @if (auth()->guard('admin')->user()->can(['view-mail-templates']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Mail Templates</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('mail-templates.index') }}">Go
                                    <i class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif
                        @if (auth()->guard('admin')->user()->can(['view-mass-mid-switching']))
                            <div class="col-md-12 mb-2">
                                <strong class="mt-2">Mass MID</strong>
                                <a class="btn btn-primary btn-sm pull-right" href="{{ route('mass-mid.index') }}">Go
                                    <i class="fa fa-angle-right ml-1"></i></a>
                            </div>
                        @endif
                        <div class="col-md-12 mb-2">
                            <strong class="mt-2">S3 Test</strong>
                            <a class="btn btn-primary btn-sm pull-right" href="{{ route('aws-s3-test.index') }}">Go
                                <i class="fa fa-angle-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
