@extends('layouts.user.default')

@section('title')
Deposit Show
@endsection

@section('breadcrumbTitle')
<a href="{{ route('dashboardPage') }}">Dashboard</a> / <a href="{{ url()->previous() }}">Deposit</a> / Show
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
	    <div class="card">
	        <div class="card-header d-flex justify-content-between">
	            <div class="header-title">
	                <h4 class="card-title">Deposit Details</h4>
	            </div>
	            <div>
	                <a href="{{ url()->previous() }}" class="btn btn-primary btn-icon btn-sm" title="Back"> <i class="fa fa-arrow-left"></i></a>
	            </div>
	        </div>
	        <div class="card-body">
	        	<table class="table table-bordered table-striped">
	        		<tr>
	        			<th width="25%">Account Holder Name</th>
	        			<td width="25%">{{ \auth::user()->application->business_name }}</td>
	        			<th width="25%">Account ID</th>
	        			<td width="25%">{{ $data->account_id }}</td>
	        		</tr>
	        		<tr>
	        			<th>Deposit Type</th>
	        			<td>
	        				@if($data->deposit_type == 1)
                            Fiat To Crypto
                            @else
                            Crypto To Fiat
                            @endif
	        			</td>
	        			<th>Currency Type</th>
	        			<td>{{ $data->currency_type }}</td>
	        		</tr>
	        		@if(isset($data->sender_wallet_address))
	        		<tr>
	        			<th>Amount</th>
	        			<td>{{ $data->amount }}</td>
	        			<th>Sender Wallet Address</th>
	        			<td>{{ $data->sender_wallet_address }}</td>
	        		</tr>
	        		@else
	        		<tr>
	        			<th>Amount</th>
	        			<td>{{ $data->amount }}</td>
	        			<th>Bank Name</th>
	        			<td>{{ $data->bank_name }}</td>
	        		</tr>
	        		<tr>
	        			<th>Bank Account Number</th>
	        			<td>{{ $data->bank_account_number }}</td>
	        			<th>SWIFT/IBAN</th>
	        			<td>{{ $data->swift }}</td>
	        		</tr>
	        		@endif

	        		<tr>
	        			<th>Date of Transfer</th>
	        			<td>{{ $data->transfer_date }}</td>
	        			<th>Reference Number</th>
	        			<td>{{ $data->reference_number }}</td>
	        		</tr>

	        		<tr>
	        			<th>Phone Number</th>
	        			<td>{{ $data->phone_number }}</td>
	        			<th>Email</th>
	        			<td>{{ $data->email }}</td>
	        		</tr>

	        		<tr>
	        			<th>Additional Instructions/Notes</th>
	        			<td colspan="3">{{ $data->note }}</td>
	        		</tr>
	        	</table>
	        </div>
	    </div>
	</div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4 class="mt-50">Withdrawal List</h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="" class="btn btn-primary">Withdrawal</a>
    </div>
    <div class="col-xl-12 col-xxl-12">
        <div class="card mt-2">
            <div class="card-header">
                <div></div>
                <div>
                    <form id="noListform" method="GET" style="float:left;" class="me-50 form-dark">
                        <select class="form-control form-control-sm" name="noList" id="noList">
                            <option value="">No of Records</option>
                            <option value="30" {{ request()->get('noList') == '30' ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request()->get('noList') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request()->get('noList') == '100' ? 'selected' : '' }}>100
                            </option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
            	<div class="table-responsive custom-table">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th>Account ID</th>
                                <th>Withdrawal Type</th>
                                <th>Currency Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date of Transfer</th>
                                <th>Date of Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
        	</div>
        </div>
    </div>
</div>
@endsection