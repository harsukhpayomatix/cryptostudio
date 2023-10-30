@extends($agentUserTheme)

@section('title')
    My Application Create
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('rp.dashboard') }}">Dashboard</a> / My Application Detail
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-8 col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Application</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Company Name</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->company_name }}</div>
                        <div class="col-md-6 mb-2"><strong>Your Website URL</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->website_url }}</div>
                        <div class="col-md-6 mb-2"><strong>Company Address</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->company_address }}</div>
                        <div class="col-md-6 mb-2"><strong>Company Email</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->company_email }}</div>
                        <div class="col-md-6 mb-2"><strong>Company Registration Number</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->company_registered_number }}</div>
                        <div class="col-md-6 mb-2"><strong>Year of registration</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->company_registered_number_year }}</div>
                        <div class="col-md-6 mb-2"><strong>Average No. of Applications Per Month</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->avg_no_of_app }}</div>
                        <div class="col-md-6 mb-2"><strong>Average Volume Commited Per Month (In USD)</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->commited_avg_volume_per_month }}</div>
                        <div class="col-md-6 mb-2"><strong>Payment Solutions Needed</strong></div>
                        <div class="col-md-6 mb-2">
                            @if ($data->payment_solutions_needed != null)
                                <?php
                                $payment_solution = json_decode($data->payment_solutions_needed);
                                if (is_array($payment_solution) && !empty($payment_solution)) {
                                    foreach ($payment_solution as $key => $value) {
                                        echo "<span class='badge badge-sm badge-primary'>" . \App\TechnologyPartner::find($value)->name . '</span> ';
                                    }
                                }
                                ?>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2"><strong>Industries Referred</strong></div>
                        <div class="col-md-6 mb-2">
                            @if ($data->industries_reffered != null)
                                <?php
                                $indutry_types = json_decode($data->industries_reffered);
                                if (is_array($indutry_types) && !empty($indutry_types)) {
                                    foreach ($indutry_types as $key => $value) {
                                        echo "<span class='badge badge-sm badge-primary'>" . \App\Categories::find($value)->name . '</span> ';
                                    }
                                }
                                ?>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2"><strong>Major Regions</strong></div>
                        <div class="col-md-6 mb-2">
                            @if ($data->major_regious != null)
                                <?php
                                $a = json_decode($data->major_regious);
                                if (is_array($a)) {
                                    if (!empty($a)) {
                                        foreach ($a as $key => $value) {
                                            echo "<span class='badge badge-sm badge-primary'>" . $value . '</span> ';
                                        }
                                    }
                                }
                                ?>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2"><strong>How are the leads generated?</strong></div>
                        <div class="col-md-6 mb-2">{{ $data->generated_lead }}</div>
                        @if ($data->authorised_individual != null)
                            <?php 
                    $b = json_decode($data->authorised_individual);
                    if(is_array($b)){
                        if(!empty($b)){ ?>
                            @foreach ($b as $key => $record)
                                <div class="col-md-6 mb-2"><strong>Authorised Individual {{ $key + 1 }}</strong></div>
                                <div class="col-md-6 mb-2"><strong>Name:</strong> {{ $record->name }}<br><strong>Phone
                                        Number: </strong>{{ $record->phone_number }}<br><strong>Email:
                                    </strong>{{ $record->email }}</div>
                            @endforeach
                            <?php } } ?>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-xxl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            @if ($data->status == '0')
                                <i class="fa fa-circle text-primary mr-1"></i>
                                Pending
                            @elseif($data->status == '1')
                                <i class="fa fa-circle text-success mr-1"></i>
                                Approved
                            @elseif($data->status == '2')
                                <i class="fa fa-circle text-danger mr-1"></i>
                                Rejected
                            @elseif($data->status == '3')
                                <i class="fa fa-circle text-primary mr-1"></i>
                                Reassigned
                            @endif
                        </div>
                        @if ($data->status == '0' || $data->status == '3')
                            <div class="col-md-6 mt-2">
                                <div class="col-sm-12">
                                    <a href="{{ route('rp.my-application.edit') }}"
                                        class="btn btn-warning btn-sm btn-block" title="Edit">Edit</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if ($data->status == 1)
                <div class="card">
                    <div class="card-header">

                        <h4 class="card-title">Agreement</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-xxl-5">
                                <a href="{{ $data->agent->agreementDocument->sent_files ? getS3Url($data->agent->agreementDocument->sent_files) : '#' }}"
                                    target="_blank" class="btn badge-primary"><i class="fa fa-eye"></i> Show</a>
                            </div>
                            <div class="col-xl-6 col-xxl-5">
                                <a href="{{ $data->agent->agreementDocument->sent_files ? route('downloadDocumentsUploade', ['file' => $data->agent->agreementDocument->sent_files]) : '#' }}"
                                    class="btn badge-success"><i class="fa fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">

                    <h4 class="card-title">Application Documents</h4>

                </div>
                <div class="card-body">
                    <div class="row">
                        @if (isset($data->passport) && $data->passport != null)
                            <div class="col-md-4 mt-2">Passport</div>
                            <div class="col-md-8 mb-2">
                                <div class="row">

                                    @foreach (json_decode($data->passport) as $key => $passport)
                                        <div class="col-md-4 mt-2">File - {{ $key + 1 }}</div>
                                        <div class="col-md-8 mt-2 pl-0 pr-0">
                                            <a href="{{ getS3Url($passport) }}" target="_blank"
                                                class="btn badge-primary btn-sm"> <i class="fa fa-eye"></i> Show</a>
                                            <a href="{{ route('downloadDocumentsUploadRpApplication', ['file' => $passport]) }}"
                                                class="btn badge-success btn-sm"><i class="fa fa-download"></i> Download</a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        @if (isset($data->utility_bill) && $data->utility_bill != null)
                            @if (isset($data->utility_bill))
                                <div class="col-md-4 mt-2">Utility Bill</div>
                                <div class="col-md-8 mb-2">
                                    <div class="row">
                                        @foreach (json_decode($data->utility_bill) as $key => $utilityBill)
                                            <div class="col-md-4 mt-2">File - {{ $key + 1 }}</div>
                                            <div class="col-md-8 mt-2 pl-0 pr-0">
                                                <a href="{{ getS3Url($utilityBill) }}" target="_blank"
                                                    class="btn badge-primary btn-sm"><i class="fa fa-eye"></i> Show</a>
                                                <a href="{{ route('downloadDocumentsUploadRpApplication', ['file' => $utilityBill]) }}"
                                                    class="btn badge-success btn-sm"><i class="fa fa-download"></i>
                                                    Download</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row">
                        @if (isset($data->company_incorporation_certificate) && $data->company_incorporation_certificate != null)
                            <div class="col-md-4 mt-2">Articles Of Incorporation</div>
                            <div class="col-md-8 mb-2">
                                <div class="row">
                                    <div class="col-md-4 mt-2"></div>
                                    <div class="col-md-8 mt-2 pl-0 pr-0">
                                        <a href="{{ getS3Url($data->company_incorporation_certificate) }}"
                                            target="_blank" class="btn badge-primary btn-sm"><i class="fa fa-eye"></i>
                                            Show</a>
                                        <a href="{{ route('downloadDocumentsUploadRpApplication', ['file' => $data->company_incorporation_certificate]) }}"
                                            class="btn badge-success btn-sm"><i class="fa fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($data->tax_id) && $data->tax_id != null)
                            <div class="col-md-4 mt-2">Tax ID</div>
                            <div class="col-md-8 mb-2">
                                <div class="row">
                                    <div class="col-md-4 mt-2"></div>
                                    <div class="col-md-8 mt-2 pl-0 pr-0">
                                        <a href="{{ getS3Url($data->tax_id) }}" target="_blank"
                                            class="btn badge-primary btn-sm"><i class="fa fa-eye"></i> Show</a>
                                        <a href="{{ route('downloadDocumentsUploadRpApplication', ['file' => $data->tax_id]) }}"
                                            class="btn badge-success btn-sm"><i class="fa fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if (isset($data->agent->agreementDocument->cross_signed_agreement) &&
                    !empty($data->agent->agreementDocument->cross_signed_agreement))
                <div class="card">
                    <div class="card-header">

                        <h4 class="card-title">Cross Signed Agreement</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-xxl-3">
                                <a href="{{ getS3Url($data->agent->agreementDocument->cross_signed_agreement) }}"
                                    target="_blank" class="btn badge-primary btn-sm"><i class="fa fa-eye"></i> Show</a>
                            </div>
                            <div class="col-xl-6 col-xxl-4">
                                <a href="{{ route('downloadDocumentsUploadRpApplication', ['file' => $data->agent->agreementDocument->cross_signed_agreement]) }}"
                                    class="btn btn-success btn-sm"><i class="fa fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-12 col-xxl-12">

        </div>
    </div>
@endsection
