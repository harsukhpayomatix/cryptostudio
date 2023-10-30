@extends('layouts.admin.default')
@section('title')
    Review Application
@endsection
@section('breadcrumbTitle')
    <a href="{{ route('admin.dashboard') }}">
        Dashboard</a> / <a href="{{ route('admin.applications.list') }}">Applications</a>
    / Review
@endsection
@section('customeStyle')
    <style type="text/css">
        .form-group label {
            font-size: 14px;
        }

        #lodder {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            z-index: 99999;
            width: 100%;
            top: 0px;
            left: 0px;
            height: 100%;
            display: none;
        }

        #lodder i {
            color: #fff;
            font-weight: bold;
            font-size: 20px;
            position: fixed;
            top: 50%;
            left: 50%;
        }

        .DeleteDocument {
            padding: 0px 10px 2px 10px;
        }

        .bankNotDes {
            color: #FFF;
            width: 100%;
            padding: 15px;
            background: #34383e;
            border-radius: 15px;
            box-shadow: rgb(0 0 0 / 70%) 10px 10px 15px -5px, rgb(0 0 0 / 60%) 5px 5px 5px -10px;
        }
    </style>
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/custom_css/sweetalert2.min.css') }}">
@endsection
@section('content')
    <div id="lodder">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
    <div class="row">
        @if ($data->status >= 4)
            <div class="col-xl-8 col-xxl-8">
            @else
                <div class="col-xl-12 col-xxl-12">
        @endif
        <div class="card  mt-1">
            <div class="card-header">
                <div class="iq-header-title">
                    <h4 class="card-title">Add Merchant & Referral Partners Rate</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($data->status >= 4)
                        <div class="col-xl-8 col-xxl-8">
                            <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Minimum Settlement Amount</td>
                                            <td>{{ $data->user->minimum_settlement_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Frequency</td>
                                            <td>Weekly payment with
                                                {{ $data->user->payment_frequency }} weeks arrears</td>
                                        </tr>
                                        <tr>
                                            <td><b>Visa -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Master -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate_master_card }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Amex -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate_amex_card }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Discover -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate_discover_card }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> UPI -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate_upi }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Crypto -</b> Merchant Discount Rate (%)</td>
                                            <td>{{ $data->user->merchant_discount_rate_crypto }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Visa -</b> Setup Fee </td>
                                            <td>{{ $data->user->setup_fee }}</td>
                                        </tr>
                                        <tr>
                                            <td> <b> Master -</b> Setup Fee</td>
                                            <td>{{ $data->user->setup_fee_master_card }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Amex -</b> Setup Fee</td>
                                            <td>{{ $data->user->setup_fee_amex_card }}</td>
                                        </tr>
                                        <tr>
                                            <td><b> Discover -</b> Setup Fee </td>
                                            <td>{{ $data->user->setup_fee_discover_card }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rolling Reserve (%)</td>
                                            <td>{{ $data->user->rolling_reserve_paercentage }}</td>
                                        </tr>
                                        <tr>
                                            <td>Transaction Fee</td>
                                            <td>{{ $data->user->transaction_fee }}</td>
                                        </tr>
                                        <tr>
                                            <td>Refund Fee</td>
                                            <td>{{ $data->user->refund_fee }}</td>
                                        </tr>
                                        <tr>
                                            <td>Chargeback Fee</td>
                                            <td>{{ $data->user->chargeback_fee }}</td>
                                        </tr>
                                        <tr>
                                            <td>Marked Transaction Fee</td>
                                            <td>{{ $data->user->flagged_fee }}</td>
                                        </tr>
                                        <tr>
                                            <td>Retrieval Fee</td>
                                            <td>{{ $data->user->retrieval_fee }}</td>
                                        </tr>

                                        @if ($data->user->agent_id)
                                            <tr>
                                                <td>RP Name</td>
                                                <td>{{ $data->user->agent->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>RP Visa Commission (%)</td>
                                                <td>{{ $data->user->agent_commission }}</td>
                                            </tr>
                                            <tr>
                                                <td>RP Master Commission (%)</td>
                                                <td>{{ $data->user->agent_commission_master_card }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-9 col-xxl-9">
                            <div class="row form-dark">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-4 pr-0">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Visa -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate', $data->user->merchant_discount_rate, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="merchant_discount_rate_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Master -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate_master_card', $data->user->merchant_discount_rate_master_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate_master_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="merchant_discount_rate_master_card_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Amex -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate_amex_card', $data->user->merchant_discount_rate_amex_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate_amex_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="merchant_discount_rate_amex_card_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Discover -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate_discover_card', $data->user->merchant_discount_rate_discover_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate_discover_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="merchant_discount_rate_discover_card_error"></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Visa -</b> Setup Fee </label>
                                        <div class="col-md-12">
                                            <input class="form-control" type="number" id="setup_fee"
                                                value="{{ config('custom.visa_setup_fee') }}" />

                                            <span class="help-block text-danger" id="setup_fee_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Master -</b> Setup Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('setup_fee_master_card', $data->user->setup_fee_master_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'setup_fee_master_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="setup_fee_master_card_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Amex -</b> Setup Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('setup_fee_amex_card', $data->user->setup_fee_amex_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'setup_fee_amex_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="setup_fee_amex_card_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Discover -</b> Setup Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('setup_fee_discover_card', $data->user->setup_fee_discover_card, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'setup_fee_discover_card',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="setup_fee_discover_card_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Rolling Reserve (%) </label>
                                        <div class="col-md-12">
                                            {!! Form::number('rolling_reserve_paercentage', $data->user->rolling_reserve_paercentage, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'rolling_reserve_paercentage',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="rolling_reserve_paercentage_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Transaction Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('transaction_fee', $data->user->transaction_fee, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'transaction_fee',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="transaction_fee_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Refund Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('refund_fee', $data->user->refund_fee, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'refund_fee',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="refund_fee_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Chargeback Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('chargeback_fee', $data->user->chargeback_fee, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'chargeback_fee',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="chargeback_fee_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> UPI -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate_upi', $data->user->merchant_discount_rate_upi, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate_upi',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="merchant_discount_rate_upi_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control"><b> Crypto -</b> Merchant Discount Rate
                                            (%)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('merchant_discount_rate_crypto', $data->user->merchant_discount_rate_crypto, [
                                                'placeholder' => 'Enter here',
                                                'class' => 'form-control',
                                                'id' => 'merchant_discount_rate_crypto',
                                            ]) !!}
                                            <span class="help-block text-danger"
                                                id="merchant_discount_rate_crypto_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Suspicious Transaction Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('flagged_fee', $data->user->flagged_fee, [
                                                'placeholder' => 'Enter
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        here',
                                                'class' => 'form-control',
                                                'id' => 'flagged_fee',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="flagged_fee_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Retrieval Fee </label>
                                        <div class="col-md-12">
                                            {!! Form::number('retrieval_fee', $data->user->retrieval_fee, [
                                                'placeholder' => 'Enter
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        here',
                                                'class' => 'form-control',
                                                'id' => 'retrieval_fee',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="retrieval_fee_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Minimum Settlement Amount</label>
                                        <div class="col-md-12">
                                            <input class="form-control" disabled
                                                value="{{ config('custom.minimum_settlement_amount') }}"
                                                id="minimum_settlement_amount" />
                                            {{-- {!! Form::number('minimum_settlement_amount',
                                            $data->user->minimum_settlement_amount, array('placeholder' => 'Enter
                                            here','class' => 'form-control', 'id'=>'minimum_settlement_amount',
                                            $data->user->is_rate_sent >= 1 ? 'disabled'
                                            : '')) !!} --}}
                                            <span class="help-block text-danger"
                                                id="minimum_settlement_amount_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-12 label-control">Payment Frequency (In week)</label>
                                        <div class="col-md-12">
                                            {!! Form::number('payment_frequency', $data->user->payment_frequency, [
                                                'placeholder' => 'Enter
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        here',
                                                'class' => 'form-control',
                                                'id' => 'payment_frequency',
                                                $data->user->is_rate_sent >= 1 ? 'disabled' : '',
                                            ]) !!}
                                            <span class="help-block text-danger" id="payment_frequency_error"></span>
                                        </div>
                                    </div>
                                </div>
                                @if ($data->status == '1' || $data->status == '2')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Select Referral Partners</label>
                                            <select id="selectAgent" class="form-control select2" style="width:100%;"
                                                {{ $data->user->is_rate_sent >= 1 ? 'disabled' : '' }}>
                                                <option value="">Select</option>
                                                <option value="0">No Referral Partners</option>
                                                @if ($agents)
                                                    @foreach ($agents as $key => $agent)
                                                        <option
                                                            @if ($data->user->agent_id == $agent->id) {{ 'selected' }} @endif
                                                            value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block text-danger" id="agent_error"></span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Display the agent commission rate input if user select the RP --}}
                                <div class="col-lg-8">
                                    <div class="row {{ $data->user->agent_id ? '' : 'd-none' }}" id="rpCommissionDiv">
                                        <div class="col-lg-6">
                                            <label>RP Visa Commission</label>
                                            <input type="number" class="form-control" id="agent_commission"
                                                placeholder="Type RP visa commission" name="agent_commission" />
                                            <span class="text-danger" id="agent_commission_error"></span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>RP Master Commission</label>
                                            <input type="number" class="form-control" id="agent_commission_master"
                                                placeholder="Type RP visa commission" name="agent_commission_master" />
                                            <span class="text-danger" id="agent_commission_master_error"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                    @if ($data->status >= 4)
                        <div class="col-xl-4 col-xxl-4">
                        @else
                            <div class="col-xl-3 col-xxl-3">
                    @endif
                    <div class="row">
                        @if ($data->user->is_rate_sent == 3)
                            <div class="col-sm-12 mb-3">
                                <span class="badge badge-danger badge-sm"> Decline By Merchant</span>
                                <span style="white-space: normal; text-align: left;"
                                    class="mt-2 badge badge-danger light">Reason :
                                    {{ $data->user->rate_decline_reason }}</span>
                            </div>
                        @endif
                        @if (auth()->guard('admin')->user()->can(['agreement-action-application']))
                            @if ($data->user->is_rate_sent != 3)
                                @if ($data->status == '4' || $data->status == '10')
                                    <div class="col-sm-12">
                                        <div class="custom-control custom-checkbox form-check mr-0">
                                            <input type="checkbox" id="is_agreement"
                                                data-rate-accept="{{ $data->user->is_rate_sent }}"
                                                data-user-id="{{ $data->user->id }}"
                                                data-link="{{ route('application-agreement-sent') }}" name="is_agreement"
                                                class="form-check-input" value="0" data-app="{{ $data->id }}">
                                            <label class="form-check-label" for="is_agreement">Agreement
                                                Sent</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                @endif
                                @if ($data->status == '5' || $data->status == '11')
                                    <div class="col-sm-12 mt-2">
                                        <div class="custom-control custom-checkbox form-check mr-0">
                                            <input type="checkbox" name="is_agreement" id="is_agreement_d"
                                                class="form-check-input" value="0" checked="checked"
                                                disabled="disabled">
                                            <label class="form-check-label" for="is_agreement_d">Agreement
                                                Sent</label>
                                        </div>
                                    </div>
                                    @if ($data->agreement_received)
                                        <div class="col-sm-12 mt-2">
                                            <div class="custom-control custom-checkbox form-check mr-0">
                                                <input type="checkbox" id="is_received"
                                                    data-link="{{ route('application-agreement-received') }}"
                                                    name="is_agreement_received" class="form-check-input" value="0"
                                                    data-app="{{ $data->id }}">
                                                <label class="form-check-label" for="is_received">Agreement
                                                    Received</label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                @endif
                                @if ($data->status == '6')
                                    <div class="col-sm-12">
                                        <div class="custom-control custom-checkbox form-check mr-0">
                                            <input type="checkbox" id="is_received"
                                                data-link="{{ route('application-agreement-received') }}"
                                                name="is_agreement_received" class="form-check-input" checked="checked"
                                                value="0" data-app="{{ $data->id }}" disabled="disabled">
                                            <label class="form-check-label" for="is_received">Agreement
                                                Received</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                @endif
                            @endif
                        @endif
                        <div class="col-sm-12">
                            @if ($data->status == '1' || $data->status == '2')
                                @if (auth()->guard('admin')->user()->can(['update-application']))
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success  done" id="sa-params"
                                            data-link="{{ route('application-done') }}"
                                            data-id="{{ $data->id }}">Approve</button>
                                        <button type="button" class="btn btn-danger  " id="reject"
                                            data-bs-toggle="modal" href="#rejectModel"
                                            data-id="{{ $data->id }}">Reject</button>
                                        <button type="button" data-bs-target="#apmModal" data-bs-toggle="modal"
                                            data-url="{{ route('apm.modal.content', [$data->user->id]) }}"
                                            class="btn btn-warning addApmButton">Add/Update
                                            APM Rates</button>
                                        @if ($data->status != '2')
                                            <button type="button" class="btn btn-primary  " id="reassign"
                                                data-bs-toggle="modal" href="#reassignModel"
                                                data-id="{{ $data->id }}">Reassign</button>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            @if ($data->status != '3')
                                @if (auth()->guard('admin')->user()->can(['update-application']))
                                    <div class="d-grid mt-2">
                                        <a href="{{ route('application.edit', [$data->id]) }}" class="btn btn-primary"
                                            title="Edit">Edit</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        @if ($data->status == '5' || $data->status == '6')
                            @if (auth()->guard('admin')->user()->can(['update-application']))
                                <div class="d-grid p-1">
                                    <button class="btn btn-danger mt-1" id="applicationTerminate"
                                        data-link="{{ route('application-terminate') }}"
                                        data-id="{{ $data->id }}">Terminate
                                        application</button>
                                </div>
                            @endif
                        @endif
                        @if ($data->status == '3')
                            @if (auth()->guard('admin')->user()->can(['update-application']))
                                <div class="col-sm-12 text-left">
                                    <a href="{{ route('application-restore', $data->id) }}"
                                        class="btn btn-warning btn-sm ">Restore</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @if ($data->status >= 4)
        <div class="col-xl-4 col-xxl-4">
            <div class="card mt-1">
                <div class="card-header">
                    <div class="iq-header-title">
                        <h4 class="card-title"><i class="fa fa-file me-1"></i> Agreement Sent</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if ($data->status > 4 && isset($data->agreement_send))
                        @if (auth()->guard('admin')->user()->can(['agreement-action-application']))
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ getS3Url($data->agreement_send) }}" target="_blank"
                                    class="btn btn-primary  mt-1 btn-sm">Show</a>
                                <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->agreement_send]) }}"
                                    class="btn btn-danger  mt-1 btn-sm">Download</a>
                                <button class="btn btn-primary mt-1 btn-sm" data-bs-toggle="modal"
                                    id="resendAgreementBtn" data-file="{{ $data->agreement_send }}"
                                    data-url="{{ route('resend.agreement.mail') }}"
                                    data-bs-target="#resendAgreementMailModal" data-email="{{ $data->user->email }}"
                                    data-name="{{ $data->user->name }}" data-appId="{{ $data->id }}"
                                    data-userId="{{ $data->user_id }}">Resend Email</button>

                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card  mt-1 height-auto">
                <div class="card-header">
                    <div class="iq-header-title">
                        <h4 class="card-title"><i class="fa fa-file me-1"></i> Signed Agreement</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if (auth()->guard('admin')->user()->can(['agreement-action-application']))
                        @if ($data->status >= 5 && !empty($data->agreement_received))
                            <div class="row">

                                <div class="col-xl-5 col-xxl-5">
                                    <a href="{{ getS3Url($data->agreement_received) }}" target="_blank"
                                        class="btn btn-primary  mt-1 btn-sm">Show</a>
                                </div>
                                <div class="col-xl-5 col-xxl-5">
                                    <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->agreement_received]) }}"
                                        class="btn btn-danger  mt-1 btn-sm">Download</a>
                                </div>
                            </div>
                            @if ($data->status == 11)
                                @if (empty($data->agreement_reassign_reason))
                                    <button type="button" class="btn btn-primary btn-sm  mt-1" id="reassignAgreement"
                                        data-bs-toggle="modal" href="#reassignAgreementModel"
                                        data-id="{{ $data->id }}">Reassign
                                        Agreement</button>
                                @endif
                            @endif
                        @else
                            @if ($data->status == 11)
                                <button type="button" class="btn btn-primary btn-sm  mt-1"
                                    disabled="disabled">Reassigned Agreement</button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            <div class="card mt-1 height-auto">
                <div class="card-header">
                    <h4>APM & Rates</h4>
                    <button type="button"data-bs-target="#apmModal" data-bs-toggle="modal"
                        data-url="{{ route('apm.modal.content', [$data->user->id]) }}"
                        class="btn btn-warning addApmButton btn-sm">Add/Update</button>
                </div>
                <div class="card-body p-0">
                    @if (isset($data->user->apm))
                        @php
                            $userApms = json_decode($data->user->apm, true);
                        @endphp
                        <div class="table-responsive custom-table">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>MID Type</th>
                                        <th>Rate %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userApms as $userApm)
                                        <tr>
                                            <td>{{ $userApm['bank_name'] }}</td>
                                            <td>
                                                @if ($userApm['apm_type'] == '1')
                                                    <span class="badge badge-danger">Card</span>
                                                @elseif($userApm['apm_type'] == '2')
                                                    <span class="badge badge-danger">Bank</span>
                                                @elseif($userApm['apm_type'] == '3')
                                                    <span class="badge badge-danger">Crypto</span>
                                                @elseif($userApm['apm_type'] == '4')
                                                    <span class="badge badge-danger">UPI</span>
                                                @endif
                                            </td>
                                            <td>{{ $userApm['apm_mdr'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center mt-2">
                            <p>No APM assigned.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif
    <div class="col-xl-8 col-xxl-8">
        <div class="card  mt-1 height-auto">
            <div class="card-header">
                <div class="iq-header-title">
                    <h4 class="card-title">Review Application</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @include('partials.application.applicationShow')
                </div>
            </div>
        </div>
        <div class="card  mt-1 height-auto">
            <div class="card-header">
                <div class="iq-header-title">
                    <h4 class="card-title">Documents List</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($data->licence_document != null)
                        <div class="col-md-8 mt-2">Licence Document</div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ getS3Url($data->licence_document) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->licence_document]) }}"
                                class="btn btn-danger btn-sm">Download</a>
                            @if ($data->status == 1 || $data->status == 12)
                                <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                    data-type="licence_document" data-file={{ $data->licence_document }}
                                    data-id={{ $data->id }} class="btn btn-primary btn-sm DeleteDocument">Delete
                                </button>
                            @endif
                        </div>
                    @endif
                    @if ($data->passport != null)
                        <div class="col-md-6 mt-2">Passport</div>
                        <div class="col-md-6 mb-2">
                            <div class="row">
                                @foreach (json_decode($data->passport) as $key => $passport)
                                    <div class="col-md-4 mt-2">File - {{ $key + 1 }}</div>
                                    <div class="col-md-8 mt-2">
                                        <a href="{{ getS3Url($passport) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $passport]) }}"
                                            class="btn btn-danger btn-sm">Download</a>
                                        @if ($data->status == 1 || $data->status == 12)
                                            <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                                data-type="passport" data-file={{ $passport }}
                                                data-id={{ $data->id }}
                                                data-link="{{ route('application-delete-docs') }}"
                                                class="btn btn-primary
                                            btn-sm DeleteDocument">Delete
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($data->company_incorporation_certificate != null)
                        <div class="col-md-8 mt-2">Articles Of Incorporation</div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ getS3Url($data->company_incorporation_certificate) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->company_incorporation_certificate]) }}"
                                class="btn btn-danger btn-sm">Download</a>
                            @if ($data->status == 1 || $data->status == 12)
                                <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                    data-type="company_incorporation_certificate"
                                    data-file={{ $data->company_incorporation_certificate }} data-id={{ $data->id }}
                                    data-link="{{ route('application-delete-docs') }}"
                                    class="btn btn-primary btn-sm
                                    DeleteDocument">Delete
                                </button>
                            @endif
                        </div>
                    @endif
                    @if (isset($data->domain_ownership) && $data->domain_ownership != null)
                        <div class="col-md-8 mt-2">Domain Ownership</div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ getS3Url($data->domain_ownership) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->domain_ownership]) }}"
                                class="btn btn-danger btn-sm">Download</a>
                            @if ($data->status == 1 || $data->status == 12)
                                <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                    data-type="domain_ownership" data-file={{ $data->domain_ownership }}
                                    data-id={{ $data->id }} data-link="{{ route('application-delete-docs') }}"
                                    class="btn
                                    btn-primary btn-sm DeleteDocument">Delete
                                </button>
                            @endif
                        </div>
                    @endif
                    @if (isset($data->latest_bank_account_statement))
                        <div class="col-md-6 mt-2">Company's Bank Statement (last 180 days)</div>
                        <div class="col-md-6 mb-2">
                            <div class="row">
                                @foreach (json_decode($data->latest_bank_account_statement) as $key => $bankStatement)
                                    <div class="col-md-4 mt-2">File - {{ $key + 1 }}</div>
                                    <div class="col-md-8 mt-2">
                                        <a href="{{ getS3Url($bankStatement) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $bankStatement]) }}"
                                            class="btn btn-danger btn-sm">Download</a>
                                        @if ($data->status == 1 || $data->status == 12)
                                            <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                                data-type="latest_bank_account_statement" data-file={{ $bankStatement }}
                                                data-id={{ $data->id }}
                                                data-link="{{ route('application-delete-docs') }}"
                                                class="btn btn-primary btn-sm DeleteDocument">Delete
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (isset($data->utility_bill))
                        <div class="col-md-6 mt-2">Utility Bill</div>
                        <div class="col-md-6 mb-2">
                            <div class="row">
                                @foreach (json_decode($data->utility_bill) as $key => $utilityBill)
                                    <div class="col-md-4 mt-2">File - {{ $key + 1 }}</div>
                                    <div class="col-md-8 mt-2">
                                        <a href="{{ getS3Url($utilityBill) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $utilityBill]) }}"
                                            class="btn btn-danger btn-sm">Download</a>
                                        @if ($data->status == 1 || $data->status == 12)
                                            <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                                data-type="utility_bill" data-file={{ $utilityBill }}
                                                data-id={{ $data->id }}
                                                data-link="{{ route('application-delete-docs') }}"
                                                class="btn btn-primary
                                            btn-sm DeleteDocument">Delete
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($data->owner_personal_bank_statement != null)
                        <div class="col-md-8 mt-2">UBO's Bank Statement (last 90 days)</div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ getS3Url($data->owner_personal_bank_statement) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->owner_personal_bank_statement]) }}"
                                class="btn btn-danger btn-sm">Download</a>
                            @if ($data->status == 1 || $data->status == 12)
                                <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                    data-type="owner_personal_bank_statement"
                                    data-file={{ $data->owner_personal_bank_statement }} data-id={{ $data->id }}
                                    data-link="{{ route('application-delete-docs') }}"
                                    class="btn btn-primary btn-sm
                                    DeleteDocument">Delete
                                </button>
                            @endif
                        </div>
                    @endif
                    @if (isset($data->previous_processing_statement) && $data->previous_processing_statement != null)
                        <div class="col-md-6 mt-2">
                            Processing History (if any)
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="row">
                                @php
                                    $previous_processing_statement_files = json_decode($data->previous_processing_statement);
                                @endphp
                                <div class="col-md-12">
                                    <div class="row">
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($previous_processing_statement_files as $key => $value)
                                            <div class="col-md-4 mt-2">File - {{ $count }}</div>
                                            <div class="col-md-8 mb-2">
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">Show</a>
                                                <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $value]) }}"
                                                    class="btn btn-danger btn-sm">Download</a>
                                                @if ($data->status == 1 || $data->status == 12)
                                                    <button type="button" data-bs-toggle="modal"
                                                        href="#delete_doc_modal" data-type="previous_processing_statement"
                                                        data-file={{ $value }} data-id={{ $data->id }}
                                                        data-link="{{ route('application-delete-docs') }}"
                                                        class="btn btn-primary btn-sm DeleteDocument">Delete
                                                    </button>
                                                @endif
                                            </div>
                                            @php
                                                $count++;
                                            @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($data->moa_document != null)
                        <div class="col-md-8 mt-2">MOA(Memorandum of Association) Document</div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ getS3Url($data->moa_document) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $data->moa_document]) }}"
                                class="btn btn-danger btn-sm">Download</a>
                            @if ($data->status == 1 || $data->status == 12)
                                <button type="button" data-bs-toggle="modal" href="#delete_doc_modal"
                                    data-type="moa_document" data-file={{ $data->moa_document }}
                                    data-id={{ $data->id }} data-link="{{ route('application-delete-docs') }}"
                                    class="btn btn-primary btn-sm
                                    DeleteDocument">Delete
                                </button>
                            @endif
                        </div>
                    @endif
                    @if (isset($data->extra_document) && $data->extra_document != null)
                        <div class="col-md-6 mt-2">
                            Additional Document
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="row">
                                @php
                                    $extra_document_files = json_decode($data->extra_document);
                                @endphp
                                <div class="col-md-12">
                                    <div class="row">
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($extra_document_files as $key => $value)
                                            <div class="col-md-4 mt-2">File - {{ $count }}</div>
                                            <div class="col-md-8 mb-2">
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">Show</a>
                                                <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $value]) }}"
                                                    class="btn btn-danger btn-sm">Download</a>
                                                @if ($data->status == 1 || $data->status == 12)
                                                    <button type="button" data-bs-toggle="modal"
                                                        href="#delete_doc_modal" data-type="extra_document"
                                                        data-file={{ $value }} data-id={{ $data->id }}
                                                        data-link="{{ route('application-delete-docs') }}"
                                                        class="btn btn-primary btn-sm DeleteDocument">Delete
                                                    </button>
                                                @endif
                                            </div>
                                            @php
                                                $count++;
                                            @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (count($bank) && $bank->where('status', '3')->count())
                        <div class="col-md-6 mt-2">
                            Referred Reply Document
                        </div>
                        <div class="col-md-6 mb-2">
                            @foreach ($bank as $v)
                                <div class="row">
                                    @php
                                        $extra_document_files = json_decode($v->extra_documents);
                                    @endphp
                                    <div class="col-md-12">
                                        <div class="row">
                                            @php
                                                $count = 1;
                                            @endphp
                                            @if (!empty($extra_document_files))
                                                @foreach ($extra_document_files as $key => $value)
                                                    <div class="col-md-4 mt-2">File - {{ $count }}</div>
                                                    <div class="col-md-8 mb-2">
                                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                                            class="btn btn-primary btn-sm">Show</a>
                                                        <a href="{{ route('downloadDocumentsUploadeAdmin', ['file' => $value]) }}"
                                                            class="btn btn-danger btn-sm">Download</a>
                                                    </div>
                                                    @php
                                                        $count++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-xxl-4">
        <div class="card  mt-1 height-auto">
            <div class="card-header">
                <div class="iq-header-title">
                    <h4 class="card-title">Status :- @if ($data->status == '1')
                            <span class="badge badge-primary badge-sm">In Progress</span>
                        @elseif($data->status == '2')
                            <span class="badge badge-primary badge-sm">Incomplete</span>
                        @elseif($data->status == '3')
                            <span class="badge badge-danger badge-sm">Rejected</span>
                        @elseif($data->status == '4')
                            <span class="badge badge-success badge-sm">Pre Approval</span>
                        @elseif($data->status == '5')
                            <span class="badge badge-warning badge-sm">Agreement Sent</span>
                        @elseif($data->status == '6')
                            <span class="badge badge-primary badge-sm">Agreement Received</span>
                        @elseif($data->status == '7')
                            <span class="badge badge-danger badge-sm">Not Interested</span>
                        @elseif($data->status == '8')
                            <span class="badge badge-danger badge-sm">Terminated</span>
                        @elseif($data->status == '9')
                            <span class="badge badge-danger badge-sm">Decline</span>
                        @elseif($data->status == '10')
                            <span class="badge badge-success badge-sm">Rate Accepted</span>
                        @elseif($data->status == '11')
                            <span class="badge badge-success badge-sm">Signed Agreement</span>
                        @elseif($data->status == '12')
                            <span class="badge badge-primary badge-sm">Save Draft</span>
                        @endif
                    </h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-xl-12 col-sm-12 col-md-12 col-12">
                        <div class="d-flex row justify-content-between align-items-center">
                            <div class="col-xl-12 col-xxl-12">
                                <div class="row">
                                    @if ($data->reason_reject != null && $data->stauts == '3')
                                        <label class="col-sm-12 mt-2">Reject Reason :</label>
                                        <div class="col-sm-12 text-left">
                                            <code>{{ $data->reason_reject }}</code>
                                        </div>
                                    @endif
                                    @if ($data->reason_reassign != null && $data->status == '2')
                                        <label class="col-sm-12 mt-2">Reassign Reason :</label>
                                        <div class="col-sm-12 text-left">
                                            <code>{{ $data->reason_reassign }}</code>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mt-1 d-grid gap-2">
                                        @if (auth()->guard('admin')->user()->can(['pdf-download-application']))
                                            <a href="{{ route('application-pdf', $data->id) }}"
                                                class="btn btn-success  "><i class="fa fa-file"></i>&nbsp;Download
                                                Application PDF</a>
                                        @endif
                                        @if (auth()->guard('admin')->user()->can(['doc-download-application']))
                                            <a href="{{ route('application-docs', $data->id) }}"
                                                class="btn  btn-primary "><i class="fa fa-download"></i>&nbsp;Download
                                                Application
                                                Document</a>
                                        @endif
                                        @if (auth()->guard('admin')->user()->can(['update-application']))
                                            @if ($data->status > '2')
                                                <a href="{{ route('application-back-inprogress', $data->id) }}"
                                                    class="btn  btn-danger "><i class="fa fa-arrow-left"></i>&nbsp;Back To
                                                    In Progress</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->guard('admin')->user()->can(['current-status-note-application']))
            <div class="card  mt-1 height-auto">
                <div class="card-header">
                    <div class="iq-header-title">
                        <h4 class="card-title">Current Status Notes</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (!empty($bank) && $bank->count())
                            <div class="col-md-12 mb-3">
                                <form id="noteForm">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $data->id }}" id="appId">
                                    <div class="form-group mb-1">
                                        <select name="bank_name" class="form-control select2" id="bank_name">
                                            <option selected disabled value="">Select Bank</option>
                                            @foreach ($bank as $key => $value)
                                                <option value="{{ $value->bankId }}"> {{ $value->bankCompanyName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="bank_name_error"></span>
                                    </div>
                                    <div class="form-group mb-2">
                                        <textarea class="form-control" name="note" id="note" rows="3" placeholder="Write Here Your Note"></textarea>
                                        <span class="text-danger" id="note_error"></span>
                                    </div>
                                    <button type="button" id="submitNoteForm"
                                        data-link="{{ route('store-application-note-bank') }}"
                                        class="btn btn-primary btn-sm">Submit Note</button>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <form id="noteSearch" class="mb-2">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-9 pr-0">
                                            <select name="bank_name_s" class="form-control select2" id="bank_name_s">
                                                <option selected disabled value="">Select Bank</option>
                                                @foreach ($bank as $key => $value)
                                                    <option value="{{ $value->bankId }}"> {{ $value->bankCompanyName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 pl-0 text-right">
                                            <button type="button" id="searchNoteForm"
                                                data-link="{{ route('search-application-note-bank') }}"
                                                class="btn btn-success btn-sm mt-2">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" style="max-height: 550px; overflow-y: auto;">
                                <div id="detailsContent"></div>
                            </div>
                        @else
                            <div class="col-md-12 text-center">
                                <p>Not bank assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>

    {{-- * Modals --}}
    @include('partials.application.applicationShowModals')
    @include('partials.application.apm_model')

@endsection
@section('customScript')
    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/sweetalert2.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/admin/applications.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var id = $('#appId').val();
            $.ajax({
                url: '{{ route('get-application-note-bank') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                beforeSend: function() {
                    $('#detailsContent').html('<i class="fa fa-spinner fa-spin"></i>  Please Wait...');
                },
                success: function(data) {
                    $('#detailsContent').html(data.html);
                },
            });

            // * Resend the mail
            $(document).on('click', '#resendAgreementMailBtn', function() {
                var url = $("#resendAgreementBtn").attr('data-url')
                var file = $("#resendAgreementBtn").attr('data-file')
                var name = $("#resendAgreementBtn").attr('data-name')
                var email = $("#resendAgreementBtn").attr('data-email')
                var appId = $("#resendAgreementBtn").attr('data-appId')
                var userId = $("#resendAgreementBtn").attr('data-userId')
                $.ajax({
                    type: "POST",
                    url: url,
                    context: $(this),
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "file": file,
                        "name": name,
                        "email": email,
                        "userId": userId,
                        "appId": appId,
                    },
                    beforeSend: function() {
                        $(this).prop("disabled", true)
                        $(this).text('Processing...')
                    },
                    success: function(res) {
                        $(this).prop("disabled", false)
                        $(this).text('Yes Send it!')
                        $("#resendAgreementMailModal").modal('hide')
                        if (res.status == 200) {
                            toastr.success(res.message);
                        }
                        console.log("The Response is ", res.status, res.message)
                    }
                })
            });

            // * Fetch APM modal content
            $(document).on('click', ".addApmButton", function() {
                var url = $(this).attr("data-url")
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res.status == 200) {
                            $("#apmModalBody").html(res.html)
                        }
                    }
                })
            });

            // * Listen changes for apmDropDown
            $(document).on('change', ".apmDropDown", function() {
                var selectedOption = $(this).find(':selected');
                var rate = selectedOption.data('rate');
                var midType = selectedOption.data('type')
                var midId = selectedOption.data('apmid')
                console.log('the mid type', midId)
                $(this).closest('.row').find('.apmRateInput').val(rate);
                $(this).closest('.row').find('.apmMidTypeInput').val(midType);
                $(this).closest('.row').find('.apmMidIdInput').val(midId);
            })

            // * Add dynamic listing
            $(document).on("click", '.apmAddMoreBtn', function() {
                var html = $(".apmAppendData").html()
                $(".apmRateBody").append(html)
            })

            // * Remove Item
            $(document).on("click", ".apmRemoveBtn", function() {
                $(this).closest('.row').remove()
            })

            // * Add APM and rates 
            $(document).on("click", ".apmModalSubmitButton", function() {
                var isValid = true;
                $(".apmRateBody select").each(function() {
                    if ($(this).prop('required') && $(this).val() === '') {
                        isValid = false;
                        $(this).next(".errorField").text("This field is required.");
                    } else {
                        $(this).next(".errorField").text("");
                    }
                })

                if (isValid) {
                    var formData = $("#ampRatesForm").serialize();
                    $.ajax({
                        url: "{{ route('apmrates.store') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                        data: formData,
                        context: $(this),
                        beforeSend: function() {
                            $(this).prop("disabled", true)
                            $(this).text('Processing...')
                        },
                        success: function(res) {
                            if (res.status == 200) {
                                toastr.success(res.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1000)
                            } else if (res.status == 500) {
                                toastr.error(res.message);
                                $(this).prop("disabled", false)
                                $(this).text('Submit')
                            }
                        },
                        error: function(res) {
                            $(this).prop("disabled", false)
                            $(this).text('Submit')
                        }
                    })
                }
            });


        });

        function getAppNote(id) {
            $.ajax({
                url: '{{ route('get-application-note-bank') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                beforeSend: function() {
                    $('#detailsContent').html('<i class="fa fa-spinner fa-spin"></i>  Please Wait...');
                },
                success: function(data) {
                    $('#detailsContent').html(data.html);
                },
            });
        }

        $('body').on('click', '#searchNoteForm', function() {
            var noteForm = $("#noteSearch");
            var formData = noteForm.serialize();
            let apiUrl = $(this).data('link');

            $.ajax({
                url: apiUrl,
                type: 'POST',
                data: formData,
                success: function(data) {
                    if (data.errors) {
                        if (data.errors.bank_name_s) {
                            toastr.error('Please Select Bank.');
                        }
                    }
                    if (data.success == '1') {
                        $('#detailsContent').html(data.html);
                    } else if (data.success == '0') {
                        toastr.error('Something went wrong, please try again!');
                    }
                },
            });
        });

        $('body').on('click', '#submitNoteForm', function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>  Please Wait...');
            $(this).css('cursor', 'not-allowed');
            var noteForm = $("#noteForm");
            var formData = noteForm.serialize();
            $('#note_error').html("");
            $('#bank_name_error').html("");
            $('#note').val("");
            $('#bank_name').val("");
            let apiUrl = $(this).data('link');

            $.ajax({
                url: apiUrl,
                type: 'POST',
                data: formData,
                success: function(data) {
                    $('#submitNoteForm').html('Submit Note');
                    $('#submitNoteForm').css('cursor', 'pointer');
                    if (data.errors) {
                        if (data.errors.note) {
                            $('#note_error').html(data.errors.note[0]);
                        }
                        if (data.errors.bank_name) {
                            $('#bank_name_error').html(data.errors.bank_name[0]);
                        }
                    }
                    if (data.success == '1') {
                        getAppNote(data.id);
                        toastr.success('Add Note Successfully.');
                    } else if (data.success == '0') {
                        toastr.error('Something went wrong, please try again!');
                    }
                },
            });
        });
    </script>
@endsection
