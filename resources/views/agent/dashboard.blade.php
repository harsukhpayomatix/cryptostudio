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
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <div class="merchantTxnCard text-white" style="background-color: #82CD47;">
                        <h2 class="text-white">{{ round($transaction->successfullP, 2) }} %</h2>
                        <p class="mb-1">Successful</p>
                        <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->successfullC }}</span>
                        </p>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="merchantTxnCard text-white" style="background-color: #FF5858;">
                        <h2 class="text-white">{{ round($transaction->declinedP, 2) }} %</h2>
                        <p class="mb-1">Declined</p>
                        <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->declinedC }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
                        <h2 class="text-white">{{ round($transaction->chargebackP, 2) }} %</h2>
                        <p class="mb-1">Chargeback</p>
                        <p class="total">Total Count : <span style="color: var(--main-primary);">
                                {{ $transaction->chargebackC }}</span></p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="merchantTxnCard text-white" style="background-color: #b16ee7;">
                        <h2 class="text-white">{{ round($transaction->refundP, 2) }} %</h2>
                        <p class="mb-1">Refund</p>
                        <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->refundC }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
                        <h2 class="text-white">{{ round($transaction->suspiciousP, 2) }} %</h2>
                        <p class="mb-1">Marked</p>
                        <p class="total">Total Count : <span style="color: var(--main-primary);">
                                {{ $transaction->suspiciousC }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                @if (Auth::guard('agentUser')->user()->main_agent_id == 0)
                    @if (auth()->guard('agentUser')->user()->referral_code != null ||
                            auth()->guard('agentUser')->user()->referral_code != '')
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="merchantTxnCard">
                                <div class="rounded">
                                    <div class="row m-0">
                                        <div class="col-md-5 text-left mb-1">
                                            <h4>Your Referral Link</h4>
                                            <p class="mb-1">Your personal referral link for merchant registration</p>
                                        </div>
                                        <div class="col-md-7 text-left mb-1">
                                            <span class="badge badge-dark px-3 py-1" id="link"
                                                data-link="{{ config('app.url') }}/register?RP={{ auth()->guard('agentUser')->user()->referral_code }}">{{ config('app.url') }}/register?RP={{ auth()->guard('agentUser')->user()->referral_code }}</span>
                                            <span class="btn btn-primary rounded" id="Copy"
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
        <div class="col-xl-12 col-lg-12 mt-2" id="latest_merchants">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title">Recent Merchants</h5>

                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <a class="btn btn-primary btn-sm" href="{!! url('rp/user-management') !!}">View All</a>
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
                        <a class="btn btn-primary btn-sm" href="{!! url('rp/merchant-transactions') !!}">View All</a>
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
