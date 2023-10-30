@extends('layouts.user.default')

@section('title')
    IP Whitelist
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / IP Whitelist
@endsection

@section('content')
    @if (!empty($data->api_key))
        <div class="row">
            <div class="col-md-6">
                <h4 class="mt-50">IP Whitelist</h4>
            </div>
            <div class="col-xl-12">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-1 col-xxl-1 text-center">
                                <i class="fa fa-key text-primary" style="font-size: 56px;"></i>
                            </div>
                            <div class="col-xl-11 col-xxl-11">
                                <h4>
                                    API Key <br>
                                    <span class="badge badge-danger mt-1" id="link"
                                        data-link="{{ $data->api_key }}">{{ $data->api_key }}</span>
                                    <span class="btn btn-primary btn-sm" id="Copy" style="cursor: pointer;">Copy</span>

                                </h4>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4 class="mt-50">IP List</h4>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('whitelist-ip-csv-export') }}" class="btn btn-primary btn-sm" id="ExcelLink">
                    <i class="fa fa-download"></i>
                    Export Excel
                </a>
                <a href="{{ route('whitelist-ip-add') }}" class="btn btn-danger btn-sm">Add IP</a>
            </div>
            <div class="col-xl-12 col-xxl-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <div></div>
                        <div>
                            <form style="float:left;" class="me-50 form-dark" id="noListform" method="GET">
                                <select class="form-control-sm form-control" name="noList" id="noList">
                                    <option value="">--No of Records--</option>
                                    <option value="30" {{ request()->get('noList') == '30' ? 'selected' : '' }}>30
                                    </option>
                                    <option value="50" {{ request()->get('noList') == '50' ? 'selected' : '' }}>50
                                    </option>
                                    <option value="100" {{ request()->get('noList') == '100' ? 'selected' : '' }}>100
                                    </option>
                                </select>
                            </form>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-table">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Website URL</th>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($apiWebsiteUrlIP as $key => $value)
                                        <tr>
                                            <td>{{ $value->website_name }}</td>
                                            <td>{{ $value->ip_address }}</td>
                                            <td>
                                                @if ($value->is_active == '0')
                                                    <label class="badge badge-sm badge-danger">Pending</label>
                                                @else
                                                    <label class="badge badge-sm badge-primary">Approved</label>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-danger btn-sm delete_modal"
                                                    data-id="{{ $value->id }}"
                                                    data-url="{{ route('deleteWebsiteUrl', $value->id) }}"><i
                                                        class="fa fa-trash"></i></a>
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
    @endif
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
            toastr.success("API Key copied successfully!");
        }
    </script>
    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/common.js') }}"></script>
@endsection
