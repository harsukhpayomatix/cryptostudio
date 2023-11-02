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
        <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
            <h2 class="text-white">100 %</h2>
            <p class="mb-1">Total Applications</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ count($application) }}</span></p>
        </div>
    </div>
    <div class="col-lg-4 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #82CD47;">
            <h2 class="text-white">{{ count($application)?(count($application->where('status','1'))*100)/count($application):'0' }} %</h2>
            <p class="mb-1">Approved Applications</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ count($application->where('status','1')) }}</span></p>
        </div>
    </div>
    <div class="col-lg-4 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #FF5858;">
            <h2 class="text-white">{{ count($application)?(count($application->where('status','2'))*100)/count($application):'0' }} %</h2>
            <p class="mb-1">Declined Applications</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ count($application->where('status','2')) }}</span></p>
        </div>
    </div>
</div>	
@endsection