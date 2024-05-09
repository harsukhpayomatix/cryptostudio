@extends('layouts.user.default')

@section('title')
    Deposit Crypto
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / <a href="{{ route('trade.index') }}">Trade</a> / Deposit Crypto
@endsection

@section('content')
    <style type="text/css">
        .currency_type_other{
            display: none;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card  mt-1">
                <div class="card-header">
                    <div class="iq-header-title">
                        <h4 class="card-title">Deposit Crypto</h4>
                    </div>
                    <a href="{{ route('trade.crypto.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('trade.deposit.crypto.store') }}" method="post" class="form-dark">
                        {!! csrf_field() !!}
                        <input type="hidden" name="deposit_type" value="2">
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label for="text">Account Holder Name</label>
                                <input type="text" class="form-control" disabled="disabled" value="{{ \auth::user()->application->business_name }}">
                                <input type="hidden" name="user_id" value="{{ \auth::user()->id }}">
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Account ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_id" placeholder="Enter here...">
                                @if ($errors->has('account_id'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('account_id') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Currency Type <span class="text-danger">*</span></label>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label class="radio-inline mr-3 col-lg-3">
                                        <input type="radio" id="rdo-1" name="currency_type" value="Bitcoin" class="checkradio form-check-input currency_type_r">
                                        Bitcoin
                                    </label>
                                    <label class="radio-inline mr-3 col-lg-3">
                                        <input type="radio" id="rdo-2" name="currency_type" value="USDT" class="checkradio form-check-input currency_type_r">
                                        USDT
                                    </label>
                                    <label class="radio-inline mr-3 col-lg-3">
                                        <input type="radio" id="rdo-3" name="currency_type" value="Ethereum" class="checkradio form-check-input currency_type_r">
                                        Ethereum
                                    </label>
                                    <label class="radio-inline mr-3 col-lg-3">
                                        <input type="radio" id="rdo-4" name="currency_type" value="Other" class="checkradio form-check-input currency_type_r">
                                        Other
                                    </label>
                                </div>
                                @if ($errors->has('currency_type'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('currency_type') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-3">
                                <input type="text" name="currency_type_other" class="form-control mt-1 currency_type_other" placeholder="Enter Here...">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="form-group col-lg-3">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="amount" placeholder="Enter here...">
                                @if ($errors->has('amount'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('amount') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Sender Wallet Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sender_wallet_address" placeholder="Enter here...">
                                @if ($errors->has('sender_wallet_address'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('sender_wallet_address') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="form-group col-lg-3">
                                <label>Date of Transfer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control flatpicker" name="transfer_date" placeholder="Enter here...">
                                @if ($errors->has('transfer_date'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('transfer_date') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Reference Number (if applicable)</label>
                                <input type="text" class="form-control" name="reference_number" placeholder="Enter here...">
                                @if ($errors->has('reference_number'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('reference_number') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone_number" placeholder="Enter here...">
                                @if ($errors->has('phone_number'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('phone_number') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" placeholder="Enter here...">
                                @if ($errors->has('email'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('email') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="form-group col-lg-6">
                                <label>Additional Instructions/Notes</label>
                                <textarea class="form-control" name="note"></textarea>
                            </div>

                            <div class="form-group col-lg-6 mt-2">
                                <div style="width: 100%; overflow: hidden;">
                                    <div style="width: 30px; float: left;">
                                        <input type="checkbox" name="confirm_by_user" id="confirm_by_user" class="form-check-input">
                                    </div>
                                    <div style="width: calc(100% - 30px); float: left;">
                                        <label class="custom-control-label" for="confirm_by_user">
                                            By submitting this deposit form, I confirm that the information provided is accurate and that I agree to Crypto Studio's terms of service. I understand the security implications of deposit transactions and commit to following all guidelines and requirements set forth by Crypto Studio.
                                        </label>
                                    </div>
                                </div>

                                @if ($errors->has('confirm_by_user'))
                                    <span class="help-block font-red-mint text-danger">
                                        <span>{{ $errors->first('confirm_by_user') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mt-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-danger">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customScript')
    <script type="text/javascript">
        $('.currency_type_r').on('change', function(){
            var type = $(this).val();
            if(type == 'Other'){
                $('.currency_type_other').css('display','block');
            }else{
                $('.currency_type_other').css('display','none');
            }
        });
    </script>
@endsection