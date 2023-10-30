@extends('layouts.admin.default')
@section('title')
    Edit Application
@endsection
@section('breadcrumbTitle')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.applications.list') }}">Applications</a>
    / Edit
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card mt-1">
                <div class="card-header">
                    <div class="iq-header-title">
                        <h4 class="card-title">Edit Application</h4>
                    </div>
                    <a href="{{ route('admin.applications.list') }}" class="btn btn-primary btn-sm"> <i
                            class="fa fa-arrow-left" aria-hidden="true"></i></a>
                </div>
                <div class="card-body p-0">
                    {{ Form::model($data, ['route' => ['admin.applications.update', $data->id], 'method' => 'PUT', 'class' => 'form-dark w-100', 'enctype' => 'multipart/form-data']) }}
                    @csrf
                    <input type=hidden name="id" value="{{ $data->id }}">
                    <input type="hidden" name="user_id" value="{{ $data->user_id }}">
                    <div class="row mt-1">
                        @include('partials.application.applicationFrom', ['isEdit' => true])
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="header-title mb-3"> Document List <small class="text-danger">The document size should
                                    not exceed
                                    35MB</small></h4>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Passport <span class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-lg-12 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" multiple id="validationCustomFile1"
                                                name="passport[]">
                                        </div>
                                        <div class="dynamicPassportFields"></div>
                                        @if ($errors->has('passport.*'))
                                            <span class="text-danger help-block form-error">
                                                {{ $errors->first('passport.*') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    @if ($data->passport != null)
                                        @foreach (json_decode($data->passport) as $key => $value)
                                            <div class="col-md-12 mt-2">
                                                <p class="pull-left mb-0">File - {{ $key + 1 }}</p>
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="mr-4 btn btn-danger btn-sm pull-right">Show</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Utility Bill <span class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-lg-12 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile5"
                                                name="utility_bill[]">

                                        </div>
                                        <div class="dynamicUtilityBillFields"></div>
                                        @if ($errors->has('utility_bill.*'))
                                            <span class="text-danger help-block form-error">
                                                {{ $errors->first('utility_bill.*') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    @if ($data->utility_bill != null)
                                        @foreach (json_decode($data->utility_bill) as $key => $value)
                                            <div class="col-md-12 mt-2">
                                                <p class="pull-left mb-0">File - {{ $key + 1 }}</p>
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn mr-4 btn-danger btn-sm pull-right">Show</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Company's Bank Statement (last 180 days)<span class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-lg-12 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile4"
                                                name="latest_bank_account_statement[]">

                                        </div>
                                        <div class="dynamicBankStatementFields"></div>
                                        @if ($errors->has('latest_bank_account_statement.*'))
                                            <span class="text-danger help-block form-error">
                                                <span>{{ $errors->first('latest_bank_account_statement.*') }}</span>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    @if ($data->latest_bank_account_statement != null)
                                        @foreach (json_decode($data->latest_bank_account_statement) as $key => $value)
                                            <div class="col-md-12 mt-2">
                                                <p class="pull-left mb-0">File - {{ $key + 1 }}</p>
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn mr-4 btn-danger btn-sm pull-right">Show</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Articles Of Incorporation <span class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-10 col-md-10 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile2"
                                                name="company_incorporation_certificate">

                                            @if ($errors->has('company_incorporation_certificate'))
                                                <span class="text-danger help-block form-error">
                                                    <span>{{ $errors->first('company_incorporation_certificate') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($data->company_incorporation_certificate != null)
                                        <div class="col-2 col-md-2">
                                            <a href="{{ getS3Url($data->company_incorporation_certificate) }}"
                                                target="_blank" class="btn btn-danger">Show</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>UBO's Bank Statement (last 90 days)</label>
                                <div class="row mx-auto">
                                    <div class="col-10 col-md-10 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile9"
                                                name="owner_personal_bank_statement">

                                        </div>
                                    </div>
                                    @if ($data->owner_personal_bank_statement != null)
                                        <div class="col-2 col-md-2">
                                            <a href="{{ getS3Url($data->owner_personal_bank_statement) }}"
                                                target="_blank" class="btn btn-danger">Show</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Processing History (if any)</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile8"
                                                name="previous_processing_statement[]" multiple>

                                        </div>
                                        <div class="row">
                                            @if (isset($data->previous_processing_statement) && $data->previous_processing_statement != null)
                                                @php
                                                    $previous_processing_statement_files = json_decode($data->previous_processing_statement);
                                                @endphp
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($previous_processing_statement_files as $key => $value)
                                                    <div class="col-md-12 mt-2">
                                                        <p class="pull-left mb-0">File - {{ $count }}</p>
                                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                                            class="btn btn-danger pull-right mr-4 btn-sm">Show</a>
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    </div>
                                                    @php
                                                        $count++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>MOA (Memorandum of Association)</label>
                                @if ($data->moa_document != null)
                                    <div class="row">
                                        <div class="col-md-10 p-0">
                                            <div class="custom-file">
                                                <input type="file" class="form-control extra-document"
                                                    name="moa_document">

                                            </div>
                                            @if ($errors->has('moa_document'))
                                                <span class="text-danger help-block form-error">
                                                    {{ $errors->first('moa_document') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <a href="{{ getS3Url($data->moa_document) }}" target="_blank"
                                                class="btn btn-primary btn-sm">Show</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="custom-file">
                                        <input type="file" class="form-control extra-document" name="moa_document">

                                    </div>
                                    @if ($errors->has('moa_document'))
                                        <span class="text-danger help-block form-error">
                                            {{ $errors->first('moa_document') }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Domain Ownership <span class="text-danger">*</span></label>
                                @if ($data->domain_ownership != null)
                                    <div class="row">
                                        <div class="col-md-10 p-0">
                                            <div class="custom-file">
                                                <input type="file" class="form-control" name="domain_ownership">

                                            </div>
                                            @if ($errors->has('domain_ownership'))
                                                <span class="text-danger help-block form-error">
                                                    {{ $errors->first('domain_ownership') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{ getS3Url($data->domain_ownership) }}" target="_blank"
                                                class="btn btn-danger">Show</a>
                                        </div>

                                    </div>
                                @else
                                    <div class="custom-file">
                                        <input type="file" class="form-control" name="domain_ownership">

                                    </div>
                                    @if ($errors->has('domain_ownership'))
                                        <span class="text-danger help-block form-error">
                                            {{ $errors->first('domain_ownership') }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label>Additional Document</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custom-file">
                                            <input type="file" class="form-control extra_document"
                                                name="extra_document[]" multiple>

                                        </div>
                                        <div class="row">
                                            @if (isset($data->extra_document) && $data->extra_document != null)
                                                @php
                                                    $extra_document_files = json_decode($data->extra_document);
                                                @endphp
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($extra_document_files as $key => $value)
                                                    <div class="col-md-12 mt-2">
                                                        <p class="pull-left mb-0">File - {{ $count }}</p>
                                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                                            class="btn mr-4 btn-primary btn-sm pull-right">Show</a>
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12 mt-2">
                            <button type="submit" class="btn btn-primary ">
                                Submit
                            </button>
                            <a href="{{ route('admin.applications.list') }}" class="me-2 btn btn-danger "> Cancel
                            </a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customScript')
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/jquery.validate.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/front/applications/edit.js') }}"></script>
    <script>
        var isEditPage = true;
    </script>
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/front/applications/applications.js') }}"></script>
    <script type="text/javascript">
        $("#processing_country").select2({
            placeholder: "Select",
            maximumSelectionLength: 5,
            allowClear: true
        });
        $("#processing_currency").select2({
            placeholder: "Select",
            maximumSelectionLength: 5,
            allowClear: true,
        });
        $("#technology_partner_id").select2({
            placeholder: "Select",
            maximumSelectionLength: 3,
            allowClear: true,
        });
        $('.form-control').on('select2:open', function(e) {
            var y = $(window).scrollTop();
            $(window).scrollTop(y + 0.1);
        });
    </script>

    <script></script>
@endsection
