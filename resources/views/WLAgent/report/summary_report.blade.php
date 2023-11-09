@extends($WLAgentUserTheme)
@section('title')
    Summary Report
@endsection

@section('breadcrumbTitle')
    Summary Report
@endsection
@section('customeStyle')
    <style>
        .summaryCard {
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h4 class="me-50">Merchant Management</h4>
        </div>
        <div class="col-lg-12 mt-2">
            <div class="row">
                <div class="col-sm-4 ml-2 ">
                    <a href="{!! route('wl-user-card-summary-report') !!}">
                        <div class=" summaryCard">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <span class="activity-icon me-md-4 me-3">
                                        <i class="fa fa-credit-card text-primary"
                                            style="line-height: 83px; font-size: 28px;"></i>
                                    </span>
                                    <div class="media-body">
                                        <span class="title  font-w600">Card type summary</span>
                                    </div>
                                </div>
                                <div class="progress" style="height:5px;">
                                    <div class="progress-bar bg-danger" style="width: 100%; height:5px;"
                                        role="progressbar"></div>
                                </div>
                            </div>
                            <div class="effect bg-danger"></div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{!! route('wl-user-payment-status-summary-report') !!}">
                        <div class=" summaryCard">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <span
                                        class="activity-icon 
                                 mr-md-4 mr-3">
                                        <i class="fa fa-credit-card text-primary"
                                            style="line-height: 83px; font-size: 28px;"></i>
                                    </span>
                                    <div class="media-body">
                                        <span class="title  font-w600">Payment status summary</span>
                                    </div>
                                </div>
                                <div class="progress" style="height:5px;">
                                    <div class="progress-bar bg-danger" style="width: 100%; height:5px;"
                                        role="progressbar"></div>
                                </div>
                            </div>
                            <div class="effect bg-danger"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
