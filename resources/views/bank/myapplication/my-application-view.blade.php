@extends('layouts.bank.default')

@section('title')
    Application Detail
@endsection

@section('breadcrumbTitle')
    Application Detail
@endsection

@section('customeStyle')
    <link href="{{ storage_asset('ThemeFinvert/css/selectize.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .selectize-control.multi .selectize-input>div {
            cursor: pointer;
            background: #3D3D3D;
            color: #212529;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-8 col-xxl-8">
            <div class="card border-card height-auto">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">My Application</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive custom-table">
                        <table class="table table-borderless table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>Company Name</strong></td>
                                    <td>{{ $data->company_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Your Website URL</strong></td>
                                    <td>{{ $data->website_url }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Address</strong></td>
                                    <td>{{ $data->company_address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Register Number / Year</strong></td>
                                    <td>{{ $data->company_registered_number_year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Settlement Method for Crypto</strong></td>
                                    <td>{{ $data->settlement_method_for_crypto }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Settlement Method for Fiat</strong></td>
                                    <td>{{ $data->settlement_method_for_fiat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>MCC Codes</strong></td>
                                    <td>{{ $data->mcc_codes }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Descriptors</strong></td>
                                    <td>{{ $data->descriptors }}</td>
                                </tr>
                                @foreach (json_decode($data->authorised_individual) as $key => $record)
                                    <tr>
                                        <td><strong>Authorised Individual {{ $key + 1 }}</strong></td>
                                        <td><strong>Name:</strong> {{ $record->name }}<br><strong>Phone Number:
                                            </strong>{{ $record->phone_number }}<br><strong>Email:
                                            </strong>{{ $record->email }}</td>
                                    </tr>
                                @endforeach
                                @if ($data->license_image != null)
                                    <tr>
                                        <td><strong>Licence Document</strong></td>
                                        <td>
                                            <a href="{{ getS3Url($data->license_image) }}" target="_blank"
                                                class="btn btn-primary btn-sm">Show</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card border-card height-auto">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Application Documents</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive custom-table">
                        <table class="table table-borderless table-striped">
                            @if (isset($data->passport) && $data->passport != null)
                                <tr>
                                    <td>Passport</td>
                                    <td>
                                        <div class="row">
                                            @foreach (json_decode($data->passport) as $key => $passport)
                                                <div class="col-md-4">File - {{ $key + 1 }}</div>
                                                <div class="col-md-8">
                                                    <a href="{{ getS3Url($passport) }}" target="_blank"
                                                        class="btn btn-primary btn-sm">Show</a>
                                                    <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $passport]) }}"
                                                        class="btn btn-primary btn-sm">Download</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if (isset($data->latest_bank_account_statement) && $data->latest_bank_account_statement != null)
                                <tr>
                                    <td>Latest Bank Account Statement</td>
                                    <td>
                                        <div class="row">
                                            @foreach (json_decode($data->latest_bank_account_statement) as $key => $statement)
                                                <div class="col-md-4">File - {{ $key + 1 }}</div>
                                                <div class="col-md-8">
                                                    <a href="{{ getS3Url($statement) }}" target="_blank"
                                                        class="btn btn-primary btn-sm">Show</a>
                                                    <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $statement]) }}"
                                                        class="btn btn-primary btn-sm">Download</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if (isset($data->utility_bill) && $data->utility_bill != null)
                                <tr>
                                    <td>Utility Bill
                    </div>
                    <td>
                        <div class="row">
                            @foreach (json_decode($data->utility_bill) as $key => $utilityBill)
                                <div class="col-md-4">File - {{ $key + 1 }}</div>
                                <div class="col-md-8">
                                    <a href="{{ getS3Url($utilityBill) }}" target="_blank"
                                        class="btn btn-primary btn-sm">Show</a>
                                    <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $utilityBill]) }}"
                                        class="btn btn-primary btn-sm">Download</a>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    </tr>
                    @endif
                    @if (isset($data->company_incorporation_certificate) && $data->company_incorporation_certificate != null)
                        <td>Articles Of Incorporation</td>
                        <td>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <a href="{{ getS3Url($data->company_incorporation_certificate) }}" target="_blank"
                                        class="btn btn-primary btn-sm">Show</a>
                                    <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $data->company_incorporation_certificate]) }}"
                                        class="btn btn-primary btn-sm">Download</a>
                                </div>
                            </div>
                        </td>
                    @endif
                    @if (isset($data->article_of_accociasion) && $data->article_of_accociasion != null)
                        <tr>
                            <td>Article Of Accociasion</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        <a href="{{ getS3Url($data->article_of_accociasion) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $data->article_of_accociasion]) }}"
                                            class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if (isset($data->tax_certificate) && $data->tax_certificate != null)
                        <tr>
                            <td>Tax Certificate</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        <a href="{{ getS3Url($data->tax_certificate) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeBank', ['file' => $data->tax_certificate]) }}"
                                            class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-xxl-4">
        <div class="card border-card height-auto">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Status</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9 mt-50">
                        @if ($data->status == '0')
                            <i class="fa fa-circle text-info mr-1"></i>
                            Pending
                        @elseif($data->status == '1')
                            <i class="fa fa-circle text-success mr-1"></i>
                            Approved
                        @elseif($data->status == '2')
                            <i class="fa fa-circle text-danger mr-1"></i>
                            Rejected
                        @elseif($data->status == '3')
                            <i class="fa fa-circle text-info mr-1"></i>
                            Reassigned
                        @endif
                    </div>
                    <div class="col-md-3">
                        @if ($data->status == '0' || $data->status == '3')
                            <a href="{{ route('bank.my-application.edit') }}" class="btn btn-primary pull-right"
                                title="Edit">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
