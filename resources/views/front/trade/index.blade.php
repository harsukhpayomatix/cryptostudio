@extends('layouts.user.default')

@section('title')
    Trade
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / Trade
@endsection

@section('content')
<style type="text/css">
    .app-content.content{
        display: table;
        width: 100%;
        padding-bottom: 2rem !important;
    }
    .app-content.content .content-wrapper{
        vertical-align: middle;
        display: table-cell;
        width: 100%;
    }
</style>
<div class="row justify-content-center">
    <div class="col-md-3">
        <a href="{{ route('trade.fiat.index') }}">
            <div class="card">
                <div class="card-body gateway-card">
                    <div class="row">
                        <div class="col-md-9">
                            <h2 class="mb-0 mt-2">Fiat To Crypto</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <svg width="70px" height="70px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#FBE7AA" d="M12 4c-4.411 0-8 3.589-8 8s3.589 8 8 8 8-3.589 8-8-3.589-8-8-8zm1 12.915V18h-2v-1.08c-2.339-.367-3-2.002-3-2.92h2c.011.143.159 1 2 1 1.38 0 2-.585 2-1 0-.324 0-1-2-1-3.48 0-4-1.88-4-3 0-1.288 1.029-2.584 3-2.915V6.012h2v1.109c1.734.41 2.4 1.853 2.4 2.879h-1l-1 .018C13.386 9.638 13.185 9 12 9c-1.299 0-2 .516-2 1 0 .374 0 1 2 1 3.48 0 4 1.88 4 3 0 1.288-1.029 2.584-3 2.915z"/>
                                <path fill="#F5AA35" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
                                <path fill="#3B434A" d="M12 11c-2 0-2-.626-2-1 0-.484.701-1 2-1 1.185 0 1.386.638 1.4 1.018l1-.018h1c0-1.026-.666-2.469-2.4-2.879V6.012h-2v1.073C9.029 7.416 8 8.712 8 10c0 1.12.52 3 4 3 2 0 2 .676 2 1 0 .415-.62 1-2 1-1.841 0-1.989-.857-2-1H8c0 .918.661 2.553 3 2.92V18h2v-1.085c1.971-.331 3-1.627 3-2.915 0-1.12-.52-3-4-3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('trade.crypto.index') }}">
            <div class="card">
                <div class="card-body gateway-card">
                    <div class="row">
                        <div class="col-md-9">
                            <h2 class="mb-0 mt-2">Crypto To Fiat</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <svg width="70px" height="70px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#FBE7AA">
                                <path stroke="#F5AA35" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                <path stroke="#3B434A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8h5m1 4c.667 0 2-.4 2-2s-1.333-2-2-2h-1m1 4c.667 0 2 .4 2 2s-1.333 2-2 2h-1m1-4h-4m-2 4h5M10 6v6m0 6v-6m3-6v2m0 8v2"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
