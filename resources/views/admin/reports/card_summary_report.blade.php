@extends('layouts.admin.default')
@section('title')
    Card Summary Report
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> / Card Summary Report
@endsection
@section('content')
    <?php
    if (!empty($_GET['start_date'])) {
        $_GET['start_date'] = date('d-m-Y', strtotime($_GET['start_date']));
    }
    if (!empty($_GET['end_date'])) {
        $_GET['end_date'] = date('d-m-Y', strtotime($_GET['end_date']));
    }
    ?>
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Advanced Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form method="" id="search-form" class="form-dark">
                    <div class="modal-body">
                        <div class="basic-form">
                            <div class="row ">
                                <div class="form-group col-lg-6">
                                    <label for="business_name">Select Merchant</label>
                                    <select name="user_id" id="business_name" data-size="7" data-live-search="true"
                                        class="select2 btn-primary fill_selectbtn_in own_selectbox" data-width="100%">
                                        <option selected disabled> -- Select Merchant -- </option>
                                        @foreach ($companyName as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ isset($_GET['user_id']) && $_GET['user_id'] == $key ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="text">Card</label>
                                    <select class="form-control select2" name="card_type" id="card_type">
                                        <option selected disabled> -- Select Card -- </option>
                                        @foreach ($card_type as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ isset($_GET['card_type']) && $_GET['card_type'] == $key ? 'selected' : '' }}>
                                                {{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="text">Select Date</label>
                                    <div class="date-input">
                                        <input class="form-control" type="text" name="start_date"
                                            placeholder="Start Date" id="start_date"
                                            value="{{ isset($_GET['start_date']) && $_GET['start_date'] != '' ? $_GET['start_date'] : '' }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="end_date">End Date</label>
                                    <div class="date-input">
                                        <input type="text" id="end_date" class="form-control"
                                            data-multiple-dates-separator=" - " data-language="en" placeholder="End Date"
                                            name="end_date"
                                            value="{{ isset($_GET['end_date']) && $_GET['end_date'] != '' ? $_GET['end_date'] : '' }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="resetForm"></i>Clear</button>
                        <button type="submit" class="btn btn-success" id="extraSearch123">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h4 class="me-50">Card Summary Report</h4>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group btn-shadow">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                    Advanced
                    Search &nbsp; <svg width="13" height="10" viewBox="0 0 18 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.7936 6.1856C12.4541 6.18549 13.097 5.97225 13.6266 5.57759C14.1563 5.18292 14.5444 4.62788 14.7334 3.99498H17.0511C17.2835 3.99498 17.5064 3.90266 17.6707 3.73833C17.835 3.574 17.9273 3.35112 17.9273 3.11873C17.9273 2.88633 17.835 2.66345 17.6707 2.49913C17.5064 2.3348 17.2835 2.24248 17.0511 2.24248H14.7334C14.5441 1.60989 14.1558 1.05524 13.6262 0.660909C13.0966 0.266574 12.4539 0.0535889 11.7936 0.0535889C11.1333 0.0535889 10.4906 0.266574 9.96099 0.660909C9.43137 1.05524 9.04308 1.60989 8.85378 2.24248H1.27859C1.0462 2.24248 0.82332 2.3348 0.658991 2.49913C0.494663 2.66345 0.402344 2.88633 0.402344 3.11873C0.402344 3.35112 0.494663 3.574 0.658991 3.73833C0.82332 3.90266 1.0462 3.99498 1.27859 3.99498H8.85378C9.04276 4.62788 9.43093 5.18292 9.96057 5.57759C10.4902 5.97225 11.1331 6.18549 11.7936 6.1856ZM1.27859 11.005C1.0462 11.005 0.82332 11.0973 0.658991 11.2616C0.494663 11.426 0.402344 11.6488 0.402344 11.8812C0.402344 12.1136 0.494663 12.3365 0.658991 12.5008C0.82332 12.6652 1.0462 12.7575 1.27859 12.7575H3.15815C3.34745 13.3901 3.73575 13.9447 4.26536 14.339C4.79498 14.7334 5.43767 14.9464 6.09797 14.9464C6.75827 14.9464 7.40096 14.7334 7.93057 14.339C8.46019 13.9447 8.84849 13.3901 9.03779 12.7575H17.0511C17.2835 12.7575 17.5064 12.6652 17.6707 12.5008C17.835 12.3365 17.9273 12.1136 17.9273 11.8812C17.9273 11.6488 17.835 11.426 17.6707 11.2616C17.5064 11.0973 17.2835 11.005 17.0511 11.005H9.03779C8.84849 10.3724 8.46019 9.81775 7.93057 9.42341C7.40096 9.02907 6.75827 8.81609 6.09797 8.81609C5.43767 8.81609 4.79498 9.02907 4.26536 9.42341C3.73575 9.81775 3.34745 10.3724 3.15815 11.005H1.27859Z"
                            fill="#FFFFFF" />
                    </svg></button>
                <a href="{{ route('card-summary-report') }}" class="btn btn-danger btn-sm"
                    style="border-radius: 0px 5px 5px 0px !important;">Reset</a>
            </div>
            @if (auth()->guard('admin')->user()->can(['export-card-type-report']))
                @if (count($transactions_summary) > 0)
                    <a class="btn btn-primary btn-sm"
                        data-link="{{ route('card-summary-report-excle', request()->all()) }}"
                        data-filename="Card_Summary_Report_Excel_" href="#" id="ExcelLink">
                        <i class="fa fa-download"></i> Export
                        Excel</a>
                @endif
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 transaction-summary-tbl">
            <div class="card">
                <div class="card-body bg-secondary p-0">
                    <div class="row">
                        <div class="col-md-9" style="padding: 30px 30px 30px 45px;">
                            <div class="header-title" style="overflow: hidden;">
                                <h5 class="card-title pull-left">MID Summary Report</h5>
                                <div class="btn-group btn-group-sm pull-right">
                                    <a href="{{ route('card-summary-report', ['for' => 'All']) }}" type="button"
                                        class="btn {{ isset($_GET['for']) && $_GET['for'] == 'All' ? 'btn-secondary' : 'btn-primary' }}">All</a>
                                    <a href="{{ route('card-summary-report', ['for' => 'Daily']) }}" type="button"
                                        class="btn {{ (!isset($_GET['for']) && !isset($_GET['end_date'])) || (isset($_GET['for']) && $_GET['for'] == 'Daily') ? 'btn-secondary' : 'btn-primary' }}">Daily</a>
                                    <a href="{{ route('card-summary-report', ['for' => 'Weekly']) }}" type="button"
                                        class="btn {{ isset($_GET['for']) && $_GET['for'] == 'Weekly' ? 'btn-secondary' : 'btn-primary' }}">Weekly</a>
                                    <a href="{{ route('card-summary-report', ['for' => 'Monthly']) }}" type="button"
                                        class="btn {{ isset($_GET['for']) && $_GET['for'] == 'Monthly' ? 'btn-secondary' : 'btn-primary' }}">Monthly</a>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane active" id="SUCCESSFUL">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['success_count'] }}</td>
                                                            <td>{{ $transaction['success_amount'] }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['success_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="DECLINED">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['declined_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['declined_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['declined_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="CHARGEBACKS">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['chargebacks_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['chargebacks_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['chargebacks_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="REFUND">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['refund_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['refund_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['refund_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="SUSPICIOUS">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['flagged_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['flagged_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['flagged_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="RETRIEVAL">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['retrieval_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['retrieval_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['retrieval_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="BLOCK">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Card</th>
                                                    <th>Count</th>
                                                    <th>Amount</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($transactions_summary) > 0)
                                                    @foreach ($transactions_summary as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction['card_type'] > 0 ? $card_type[$transaction['card_type']] : 'N/A' }}
                                                            </td>
                                                            <td>{{ $transaction['block_count'] }}</td>
                                                            <td>
                                                                {{ number_format($transaction['block_amount'], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                {{ round($transaction['block_percentage'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center text-white" colspan="4">No record found.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 bg-secondary-2">
                            <ul class="nav nav-tabs mt-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#SUCCESSFUL" data-bs-toggle="tab">
                                        Successful
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#DECLINED" data-bs-toggle="tab">
                                        Declined
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#CHARGEBACKS" data-bs-toggle="tab">
                                        Chargebacks
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#REFUND" data-bs-toggle="tab">
                                        Refund
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#SUSPICIOUS" data-bs-toggle="tab">
                                        Marked Transactions
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#RETRIEVAL" data-bs-toggle="tab">
                                        Retrieval
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#BLOCK" data-bs-toggle="tab">
                                        Block
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customScript')
    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/common.js') }}"></script>
    <script type="text/javascript">
        $("#resetForm").click(function() {
            $('#search-form').find("input[type=text], input[type=email], input[type=number], select").val("");
            $(".select2").val('first').trigger('change.select2');
        });
    </script>
@endsection
