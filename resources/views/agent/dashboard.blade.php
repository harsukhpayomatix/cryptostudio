@extends($agentUserTheme)
@section('title')
    Dashboard
@endsection

@section('breadcrumbTitle')
    Dashboard
@endsection
@section('customStyle')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="merchantTxnCard">
                        <h2>{{ round($transaction->successfullP, 2) }} %</h2>
                        <p class="mb-1" style="color: #82CD47;">Successful</p>
                        <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->successfullC }}</span>
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="merchantTxnCard">
                        <h2>{{ round($transaction->declinedP, 2) }} %</h2>
                        <p class="mb-1" style="color: #5F9DF7;">Declined</p>
                        <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->declinedC }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="merchantTxnCard">
                        <h2>{{ round($transaction->chargebackP, 2) }} %</h2>
                        <p class="mb-1" style="color: #C47AFF;">Chargeback</p>
                        <p class="total">Total Count : <span style="color: #B3ADAD;">
                                {{ $transaction->chargebackC }}</span></p>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="merchantTxnCard">
                        <h2>{{ round($transaction->suspiciousP, 2) }} %</h2>
                        <p class="mb-1" style="color: #C47AFF;">Marked</p>
                        <p class="total">Total Count : <span style="color: #B3ADAD;">
                                {{ $transaction->suspiciousC }}</span></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="merchantTxnCard">
                        <h2>{{ round($transaction->refundP, 2) }} %</h2>
                        <p class="mb-1" style="color: #FF5858;">Refund</p>
                        <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->refundC }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                @if (Auth::guard('agentUser')->user()->main_agent_id == 0)
                    @if (auth()->guard('agentUser')->user()->referral_code != null ||
                            auth()->guard('agentUser')->user()->referral_code != '')
                        <div class="col-xl-12 col-lg-12">
                            <div class=" merchantTxnCard">
                                <div class="rounded">
                                    <div class="row">
                                        <div class="col-md-12 text-center mt-3 mb-3">
                                            <div class="rounded-circle iq-card-icon">
                                                <i class="la la-users text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <h4>Your Referral Link</h4>
                                            <p class="mb-1">Your personal referral link for merchant registration</p>

                                            <span class="badge badge-primary px-3 py-1" id="link"
                                                data-link="{{ config('app.url') }}/register?RP={{ auth()->guard('agentUser')->user()->referral_code }}">{{ config('app.url') }}/register?RP={{ auth()->guard('agentUser')->user()->referral_code }}</span>
                                        </div>
                                        <div class="col-md-12 mt-3 text-center">
                                            <span class="btn btn-danger btn-sm" id="Copy"
                                                style="cursor: pointer;">Copy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12" id="latest_merchants">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title">Recent Merchants</h5>

                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <a class="btn btn-danger btn-sm" href="{!! url('rp/user-management') !!}">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Business Name</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestMerchants as $key => $value)
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->business_name }}</td>
                                        <td>{{ $value->mobile_no }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12" id="latest_transactions">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title">Recent Transactions</h5>

                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <a class="btn btn-danger btn-sm" href="{!! url('rp/merchant-transactions') !!}">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Order No.</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latest10Transactions as $key => $value)
                                    <tr>
                                        <td>{{ $value->order_id }}</td>
                                        <td>{{ convertDateToLocal($value->created_at, 'd-m-Y') }}</td>
                                        <td>{{ $value->amount }}</td>
                                        <td>{{ $value->currency }}</td>
                                        <td>
                                            @if ($value->status == '1')
                                                <label class="light badge-sm badge badge-success">Success</label>
                                            @elseif($value->status == '2')
                                                <label class="light badge-sm badge badge-warning">Pending</label>
                                            @elseif($value->status == '3')
                                                <label class="light badge-sm badge badge-primary">Canceled</label>
                                            @elseif($value->status == '4')
                                                <label class="light badge-sm badge badge-primary">To Be Confirm</label>
                                            @else
                                                <label class="light badge-sm badge badge-danger">Declined</label>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customScript')
    <script>
        function Clipboard_CopyTo(value) {
            var tempInput = document.createElement("input");
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
        }
        document.querySelector('#Copy').onclick = function() {
            var code = $('#link').attr("data-link");
            Clipboard_CopyTo(code);
            toastr.success("Referral link copied successfully!");
        }
    </script>
@endsection
