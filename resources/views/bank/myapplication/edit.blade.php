@extends('layouts.bank.default')

@section('title')
    Bank Application Edit
@endsection

@section('breadcrumbTitle')
<a href="{{ route('bank.my-application.detail',$application->id) }}">Application Detail</a> /
Edit
@endsection

@section('customeStyle')
<link href="{{ storage_asset('ThemeCryptoStudio/css/selectize.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .selectize-control.multi .selectize-input > div{
        cursor: pointer;
        background: #FFD956;
        color: #212529;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card border-card">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Application</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('bank.my-application.update') }}" method="post" enctype="multipart/form-data"
                        id="application-form" class="form form-dark">
                        @csrf
                        <input type="hidden" name="id" value="{{$application->id}}">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label for="company_name">Company Name<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('company_name',$application->company_name, array('placeholder' => 'Enter here...','class' =>
                                    'form-control','id'=>'company_name')) !!}
                                </div>
                                @if ($errors->has('company_name'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('company_name') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="website_url">Website URL<span class="text-danger">*</span> <small class="text-primary">https://example.com</small></label>
                                <div class="input-div">
                                    {!! Form::text('website_url', $application->website_url, array('placeholder' => 'Enter here...','class' =>
                                    'multi-select','id'=>'website_url',"multiple" => "multiple")) !!}
                                </div>
                                <small>Press <kbd>Tab</kbd> after each input and <kbd>left/right arrow keys</kbd> to move the cursor between values.</small> 
                                @if ($errors->has('website_url'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('website_url') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="company_registered_number_year">Company Register Number / Year<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::number('company_registered_number_year', $application->company_registered_number_year, array('placeholder' => 'Enter here...','class' =>
                                    'form-control','id'=>'company_registered_number_year')) !!}
                                </div>
                                @if ($errors->has('company_registered_number_year'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('company_registered_number_year') }}
                                </span>
                                @endif
                            </div>
                            
                            <div class="form-group col-lg-4">
                                <label for="company_address">Company Address<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    <textarea id="company_address" class="form-control" name="company_address"
                                        placeholder="Enter here..."
                                        value="{{ $application->company_address??Input::old('company_address') }}">{{ $application->company_address?? Input::old('company_address') }}</textarea>
                                </div>
                                @if ($errors->has('company_address'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('company_address') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="settlement_method_for_crypto">Settlement Method for Crypto<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    <select id="settlement_method_for_crypto" class="select2" name="settlement_method_for_crypto">
                                        <option value="">--Select--</option>
                                        <option value="USDT" {{ $application->settlement_method_for_crypto?'selected':'' }}>USDT</option>
                                        <option value="BTC" {{ $application->settlement_method_for_crypto?'selected':'' }}>BTC</option>
                                    </select>
                                </div>
                                @if ($errors->has('settlement_method_for_crypto'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('settlement_method_for_crypto') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="settlement_method_for_fiat">Settlement Method for Fiat<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    <select id="settlement_method_for_fiat" class="select2" name="settlement_method_for_fiat">
                                        <option value="">--Select--</option>
                                        <option value="USD" {{ $application->settlement_method_for_fiat?'selected':'' }}>USD</option>
                                        <option value="GBP" {{ $application->settlement_method_for_fiat?'selected':'' }}>GBP</option>
                                        <option value="EURO" {{ $application->settlement_method_for_fiat?'selected':'' }}>EURO</option>
                                    </select>
                                </div>
                                @if ($errors->has('settlement_method_for_fiat'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('settlement_method_for_fiat') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="mcc_codes">MCC Codes<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('mcc_codes', $application->mcc_codes, array('placeholder' => 'Enter here...','class' =>
                                    'multi-select','id'=>'mcc_codes',"multiple" => "multiple")) !!}
                                </div>
                                <small>Press <kbd>Tab</kbd> after each input and <kbd>left/right arrow keys</kbd> to move the cursor between values.</small>
                                @if ($errors->has('mcc_codes'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('mcc_codes') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="descriptors">Descriptors<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('descriptors', $application->descriptors, array('placeholder' => 'Enter here...','class' =>
                                    'multi-select','id'=>'descriptors')) !!}
                                </div>
                                <small>Press <kbd>Tab</kbd> after each input and <kbd>left/right arrow keys</kbd> to move the cursor between values.</small>
                                @if ($errors->has('descriptors'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('descriptors') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_license_applied" value="1" name="is_license_applied" {{ $application->is_license_applied?'checked':'' }}>
                                    <label class="custom-control-label" for="is_license_applied">Is License Applied</label>
                                </div>
                                @if ($errors->has('is_license_applied'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('is_license_applied') }}
                                    </span>
                                @endif
                                <div @if(!$application->is_license_applied)style="display: none;"@endif class="license">
                                    <label>Upload your licence.<span class="text-danger">*</span></label>
                                        
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input form-control" name="license_image">      
                                    </div>
                                            
                                    @if ($errors->has('license_image'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('license_image') }}
                                    </span>
                                    @endif

                                    @if ($application->is_license_applied)
                                    <div class="col-md-12 mt-2">
                                      <a href="{{ getS3Url($application->license_image) }}" target="_blank" class="mr-4 btn btn-primary btn-sm">Show</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3 mt-3">
                                <h5>Application Documents <small class="text-primary">The document size should not exceed 35MB</small>
                                </h5>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Passport <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile1" name="passport[]">
                                </div>
                                <div class="dynamicPassportFields"></div>
                                @if ($errors->has('passport.*'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('passport.*') }}
                                </span>
                                @endif
                                @if ($application->passport != null)
                                <div class="row">
                                @foreach (json_decode($application->passport) as $key => $value )
                                <div class="col-md-12 mt-2">
                                <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                <a href="{{ getS3Url($value) }}" target="_blank" class="mr-4 btn btn-primary btn-sm pull-right">Show</a>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Latest Bank Account Statement <span class="text-danger">*</span></label>
                                {{-- <i class="fa fa-info tol-info" data-toggle="tooltip" data-placement="top"
                                    title="In order to add multiple documents , please click on 'CTRL' and select the multiple files."></i> --}}
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile2"
                                        name="latest_bank_account_statement[]">
                                </div>
                                <div class="dynamicAccStatementFields"></div>
                                @if ($errors->has('latest_bank_account_statement.*'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('latest_bank_account_statement.*') }}
                                </span>
                                @endif
                                @if ($application->latest_bank_account_statement != null)
                                <div class="row">
                                @foreach (json_decode($application->latest_bank_account_statement) as $key => $value )
                                <div class="col-md-12 mt-2">
                                <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                <a href="{{ getS3Url($value) }}" target="_blank" class="mr-4 btn btn-primary btn-sm pull-right">Show</a>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Utility Bill <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile3" name="utility_bill[]">
                                </div>
                                <div class="dynamicUtilityBillFields"></div>
                                @if ($errors->has('utility_bill.*'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('utility_bill.*') }}
                                </span>
                                @endif
                                @if ($application->utility_bill != null)
                                <div class="row">
                                @foreach (json_decode($application->utility_bill) as $key => $value )
                                <div class="col-md-12 mt-2">
                                <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                <a href="{{ getS3Url($value) }}" target="_blank" class="btn mr-4 btn-primary btn-sm pull-right">Show</a>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Company certificate of incorporation <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile4" name="company_incorporation_certificate">
                                </div>
                                <div class="dynamicCertificateOfIncorporationFields"></div>
                                @if ($errors->has('company_incorporation_certificate'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('company_incorporation_certificate') }}
                                </span>
                                @endif
                                @if($application->company_incorporation_certificate != null)
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <a href="{{ getS3Url($application->company_incorporation_certificate) }}" target="_blank" class="btn btn-primary btn-sm pull-right">Show</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Article Of Accociasion</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile5"
                                        name="article_of_accociasion">
                                </div>
                                <div class="dynamicArticleOfAccoFields"></div>
                                @if ($errors->has('article_of_accociasion'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('article_of_accociasion') }}
                                </span>
                                @endif
                                @if($application->article_of_accociasion != null)
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <a href="{{ getS3Url($application->article_of_accociasion) }}" target="_blank" class="btn btn-primary btn-sm pull-right">Show</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tax Certificate</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" id="validationCustomFile6" name="tax_certificate">
                                </div>
                                <div class="dynamicTaxCertiFields"></div>
                                @if ($errors->has('tax_certificate'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('tax_certificate') }}
                                </span>
                                @endif
                                @if($application->tax_certificate != null)
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <a href="{{ getS3Url($application->tax_certificate) }}" target="_blank" class="btn btn-primary btn-sm pull-right">Show</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h5 class="mb-3 mt-3">Authorized Individual</h5>
                        <div id="sec_authorized_individual">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_name[]">Name<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_name[]', json_decode($application->authorised_individual)[0]->name, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_name[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_name[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_name[]') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_phone_number[]">Phone No.<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_phone_number[]', json_decode($application->authorised_individual)[0]->phone_number, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_phone_number[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_phone_number[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_phone_number[]') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_email[]">Email<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_email[]', json_decode($application->authorised_individual)[0]->email, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_email[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_email[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_email[]') }}
                                    </span>
                                    @endif
                                </div>
                                      
                                <div class="form-group col-lg-3" style="margin-top: 38px;"> 
                                    <button type="button" class="btn btn-danger btn-sm" id="btnPlus"><i class="fa fa-plus"></i> 
                                    </button> 
                                </div>
                            </div>
                            <?php 
                                $values = json_decode($application->authorised_individual);
                                unset($values[0]);
                                // dd($values[1]->name);
                            ?>
                            @foreach($values as $key=>$value)
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_name[]">Name<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_name[]', $value->name, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_name[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_name[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_name[]') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_phone_number[]">Phone No.<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_phone_number[]', $value->phone_number, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_phone_number[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_phone_number[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_phone_number[]') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="authorized_individual_email[]">Email<span class="text-danger">*</span></label>
                                    <div class="input-div">
                                        {!! Form::text('authorized_individual_email[]', $value->email, array('placeholder' => 'Enter here...','class' =>
                                        'form-control','id'=>'authorized_individual_email[]')) !!}
                                    </div>
                                    @if ($errors->has('authorized_individual_email[]'))
                                    <span class="text-danger help-block form-error">
                                        {{ $errors->first('authorized_individual_email[]') }}
                                    </span>
                                    @endif
                                </div>
                                      
                                <div class="form-group col-lg-3" style="margin-top: 38px;"> 
                                    <button type="button" class="btn btn-primary btn-sm btnMinus"> <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="col-md-12 text-right mt-2">
                            <button name="action" type="submit" id="submit_button" class="btn btn-danger btn-raised" value="save">Submit</button>
                            <a href="{{ route('bank.my-application.detail',$application->id) }}" class="btn btn-danger">Cancel</a>
                        </div>
                        
                    </form>
                    <div id="row_authorized_individual" style="display: none;">
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label for="authorized_individual_name[]">Name<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('authorized_individual_name[]', '', array('placeholder' => 'Enter here...','class' =>
                                    'form-control','id'=>'authorized_individual_name[]')) !!}
                                </div>
                                @if ($errors->has('authorized_individual_name[]'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('authorized_individual_name[]') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="authorized_individual_phone_number[]">Phone No.<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('authorized_individual_phone_number[]', '', array('placeholder' => 'Enter here...','class' =>
                                    'form-control','id'=>'authorized_individual_phone_number[]')) !!}
                                </div>
                                @if ($errors->has('authorized_individual_phone_number[]'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('authorized_individual_phone_number[]') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="authorized_individual_email[]">Email<span class="text-danger">*</span></label>
                                <div class="input-div">
                                    {!! Form::text('authorized_individual_email[]', '', array('placeholder' => 'Enter here...','class' =>
                                    'form-control','id'=>'authorized_individual_email[]')) !!}
                                </div>
                                @if ($errors->has('authorized_individual_email[]'))
                                <span class="text-danger help-block form-error">
                                    {{ $errors->first('authorized_individual_email[]') }}
                                </span>
                                @endif
                            </div>
                                  
                            <div class="form-group col-lg-3" style="margin-top: 38px;"> 
                                <button type="button" class="btn btn-primary btn-sm btnMinus"> <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customScript')
<script src="{{ storage_asset('ThemeCryptoStudio/custom_js/jquery.validate.min.js') }}"></script>
<script src="{{ storage_asset('ThemeCryptoStudio/js/selectize.min.js') }}"></script>
<script type="text/javascript">
    $('.multi-select').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
    $('#is_license_applied').on('change', function(){
        if($(this).is(':checked')){
            $('.license').show();
        }else{
            $('.license').hide();
        }
    });

    $('#btnPlus').on('click', function () {
        $('#sec_authorized_individual').append($('#row_authorized_individual').html());
    });

    $(document).on('click','.btnMinus', function () {
        $(this).parents('.form-row').remove();
    })

    $('.select2').select2();

</script>
@endsection