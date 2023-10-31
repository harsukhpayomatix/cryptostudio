@extends($bankUserTheme)

@section('title')
Dashboard
@endsection

@section('breadcrumbTitle')
Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 mb-2">
        <div class="merchantTxnCard">
            <h2>100 %</h2>
            <p class="mb-1" style="color: #5F9DF7;">Total Applications</p>
            <p class="total">Total Count : <span style="color: #4e738e;"> {{ count($application) }}</span></p>
        </div>
    </div>
    <div class="col-lg-4 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ count($application)?(count($application->where('status','1'))*100)/count($application):'0' }} %</h2>
            <p class="mb-1" style="color: #82CD47;">Approved Applications</p>
            <p class="total">Total Count : <span style="color: #4e738e;"> {{ count($application->where('status','1')) }}</span></p>
        </div>
    </div>
    <div class="col-lg-4 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ count($application)?(count($application->where('status','2'))*100)/count($application):'0' }} %</h2>
            <p class="mb-1" style="color: #FF5858;">Declined Applications</p>
            <p class="total">Total Count : <span style="color: #4e738e;"> {{ count($application->where('status','2')) }}</span></p>
        </div>
    </div>
</div>	
@endsection