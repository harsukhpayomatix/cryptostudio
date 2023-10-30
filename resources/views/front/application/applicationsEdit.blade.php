@extends('layouts.user.default')

@section('title')
My Application Edit
@endsection

@section('breadcrumbTitle')
<a href="{{ route('dashboardPage') }}">Dashboard</a> / <a href="{{ route('my-application') }}">My Application</a> / Edit
@endsection

@section('customeStyle')
<style type="text/css">
    .error {
        color: #bd2525;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card border-card">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">My Application Edit</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    {{ Form::model($data, array('route' => array('applications-update', $data->id), 'method' => 'PUT', 'class' => 'form form-dark', 'enctype'=>'multipart/form-data','id'=>'application-form')) }}
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $data->user_id }}">
                    <div class="form-row row">
                        @include('partials.application.applicationFrom' ,['isEdit' => true])

                        <div class="col-md-12 mb-2 mt-2">
                            <h5>My Documents <small class="text-info">The document size should not exceed 35MB</small>
                            </h5>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Passport <span class="text-danger">*</span></label>
                                {{-- <i class="fa fa-info tol-info" data-toggle="tooltip" data-placement="top" title="In order to add multiple documents , please click on 'CTRL' and select the multiple files."></i> --}}
                                <div class="row mx-auto">
                                    <div class="col-lg-12 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile1"
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
                                <div class="row">
                                    @if (isset($data->passport))
                                    @foreach (json_decode($data->passport) as $key => $value )
                                    <div class="col-md-12 mt-2">
                                        <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                            class="btn btn-primary btn-sm pull-right mr-4">Show</a>
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
                                <div class="row">
                                    @if ($data->utility_bill != null)
                                    @foreach (json_decode($data->utility_bill) as $key => $value )
                                    <div class="col-md-12 mt-2">
                                        <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                            class="btn btn-primary btn-sm pull-right mr-4">Show</a>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Company's Bank Statement (last 180 days) <span
                                        class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-lg-12 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile4"
                                                name="latest_bank_account_statement[]">
                                        </div>
                                        <div class="dynamicBankStatementFields"></div>
                                        @if ($errors->has('latest_bank_account_statement.*'))
                                        <span class="text-danger help-block form-error">
                                            {{ $errors->first('latest_bank_account_statement.*') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    @if ($data->latest_bank_account_statement != null)
                                    @foreach (json_decode($data->latest_bank_account_statement) as $key => $value )
                                    <div class="col-md-12 mt-2">
                                        <p class="pull-left mb-0">File - {{ $key+1 }}</p>
                                        <a href="{{ getS3Url($value) }}" target="_blank"
                                            class="btn btn-primary btn-sm pull-right mr-4">Show</a>
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
                                    <div class="col-9 col-md-9 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile2"
                                                name="company_incorporation_certificate">
                                            @if ($errors->has('company_incorporation_certificate'))
                                            <span class="text-danger help-block form-error">
                                                {{ $errors->first('company_incorporation_certificate') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($data->company_incorporation_certificate != null)
                                        <div class="col-2 col-md-3">
                                            <a href="{{ getS3Url($data->company_incorporation_certificate) }}"
                                                target="_blank" class="btn btn-primary btn-sm">Show</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>UBO's Bank Statement (last 90 days)</label>
                                <div class="row mx-auto">
                                    <div class="col-md-9 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="validationCustomFile9"
                                                name="owner_personal_bank_statement">
                                        </div>
                                    </div>
                                    @if ($data->owner_personal_bank_statement != null)
                                        <div class="col-md-3">
                                            <a href="{{ getS3Url($data->owner_personal_bank_statement) }}" target="_blank"
                                                class="btn btn-primary btn-sm">Show</a>
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
                                            <input type="file" class="form-control"
                                                name="previous_processing_statement[]" multiple>
                                        </div>

                                        <div class="row">
                                            @if(isset($data->previous_processing_statement) &&
                                            $data->previous_processing_statement
                                            != null)
                                            @php
                                            $previous_processing_statement_files =
                                            json_decode($data->previous_processing_statement);
                                            @endphp
                                            @php
                                            $count = 1;
                                            @endphp
                                            @foreach($previous_processing_statement_files as $key => $value)
                                            <div class="col-md-12 mt-2">
                                                <p class="pull-left mb-0">File - {{ $count }}</p>
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm pull-right mr-4">Show</a>
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
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>MOA (Memorandum of Association)</label>
                                <div class="row mx-auto">
                                    <div class="col-md-9 p-0">
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
                                    @if ($data->moa_document != null)
                                    <div class="col-md-3">
                                        <a href="{{ getS3Url($data->moa_document) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Domain Ownership <span class="text-danger">*</span></label>
                                <div class="row mx-auto">
                                    <div class="col-md-9 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="form-control"
                                                name="domain_ownership">
                                        </div>
                                        @if ($errors->has('domain_ownership'))
                                        <span class="text-danger help-block form-error">
                                            {{ $errors->first('domain_ownership') }}
                                        </span>
                                        @endif
                                    </div>
                                    @if ($data->domain_ownership != null)
                                    <div class="col-md-3">
                                        <a href="{{ getS3Url($data->domain_ownership) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Additional Document</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custom-file">
                                            <input type="file" class="form-control extra_document"
                                                id="validationCustomFile8" name="extra_document[]" multiple>
                                        </div>
                                        <div class="row">
                                            @if(isset($data->extra_document) && $data->extra_document != null)
                                            @php
                                            $extra_document_files = json_decode($data->extra_document);
                                            @endphp
                                            @php
                                            $count = 1;
                                            @endphp

                                            @foreach($extra_document_files as $key => $value)
                                            <div class="col-md-12 mt-1">
                                                <p class="pull-left mb-0">File - {{ $count }}</p>
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm pull-right mr-4">Show</a>
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


                        <div class="col-md-12 mt-2 text-right">
                            <button type="submit" class="btn btn-danger">Submit</button>
                            <a href="{{route('my-application')}}" class="btn btn-danger"> Cancel </a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('customScript')
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/jquery.validate.min.js') }}"></script>
    <script>
        var isEditPage = true;
    </script>
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/front/applications/applications.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/custom_js/front/applications/edit.js') }}"></script>

    @endsection