@extends('layouts.user.default')

@section('title')
Dashboard
@endsection

@section('breadcrumbTitle')
Dashboard
@endsection

@section('customeStyle')
<script src="https://cdn.lordicon.com/libs/frhvbuzj/lord-icon-2.0.2.js"></script>
<link href="{{ storage_asset('/theme/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
<style type="text/css">
    .dropdown.bootstrap-select.default-select {
        width: 130px !important;
    }
</style>
@endsection

@section('content')
@php
$transactionPermission = 0;
$settingsPermission = 0;
@endphp
@if(Auth()->user()->main_user_id != '0')
@if(Auth()->user()->transactions == '1')
@php
$transactionPermission = 1;
@endphp
@endif
@if(Auth()->user()->settings == '1')
@php
$settingsPermission = 1;
@endphp
@endif
@endif
@if(!empty(Auth::user()->application))
@if(Auth::user()->application->status == 4 || Auth::user()->application->status == 5 ||
Auth::user()->application->status == 6 || Auth::user()->application->status == 10 || Auth::user()->application->status
== 11)
@php
$transactionPermission = 1;
$settingsPermission = 1;
@endphp
@endif
@endif
@if($transactionPermission == 1)
<div class="row">
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-6 mb-2">
                <div class="merchantTxnCard">
                    <h2>{{ round($transaction->successfullP,2) }} %</h2>
                    <p class="mb-1" style="color: #82CD47;">Successful</p>
                    <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->successfullC }}</span></p>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="merchantTxnCard">
                    <h2>{{round($transaction->declinedP,2)}} %</h2>
                    <p class="mb-1" style="color: #5F9DF7;">Declined</p>
                    <p class="total">Total Count : <span style="color: #B3ADAD;"> {{$transaction->declinedC}}</span></p>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="merchantTxnCard">
                    <h2>{{ round($transaction->suspiciousP,2) }} %</h2>
                    <p class="mb-1" style="color: #C47AFF;">Marked</p>
                    <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->suspiciousC }}</span></p>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="merchantTxnCard">
                    <h2>{{ round($transaction->refundP,2) }} %</h2>
                    <p class="mb-1" style="color: #FF5858;">Refund</p>
                    <p class="total">Total Count : <span style="color: #B3ADAD;"> {{ $transaction->refundC }}</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h5 class="card-title">Recent Transactions</h5>
                </div>
                <div class="card-header-toolbar d-flex align-items-center">
                    <a href="{!! url('transactions') !!}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive custom-table" id="latestTransactionsData">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="badge-primary spinner-grow" style="width: 3rem;height:3rem;" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- * Transaction Summary report --}}
<div class="row">
    <div class="col-lg-12 transaction-summary-tbl">
        <div class="card">
            <div class="card-body bg-secondary p-0">
                <div class="row">
                    <div class="col-md-9" style="padding: 30px 30px 30px 45px;">
                        <div class="header-title">
                            <h5 class="card-title">Transaction Summary</h5>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active" id="SUCCESSFUL">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['success_count'] }}</td>
                                            <td>{{ $ts['success_amount'] }}</td>
                                            <td>{{ round($ts['success_percentage'],2) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="DECLINED">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['declined_count'] }}</td>
                                            <td>{{
                                                number_format($ts['declined_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['declined_percentage'],2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="CHARGEBACKS">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['chargebacks_count'] }}</td>
                                            <td>{{
                                                number_format($ts['chargebacks_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['chargebacks_percentage'],2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="REFUND">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['refund_count'] }}</td>
                                            <td>{{
                                                number_format($ts['refund_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['refund_percentage'],2) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="SUSPICIOUS">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['flagged_count'] }}</td>
                                            <td>{{
                                                number_format($ts['flagged_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['flagged_percentage'],2) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="RETRIEVAL">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['retrieval_count'] }}</td>
                                            <td>{{
                                                number_format($ts['retrieval_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['retrieval_percentage'],2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="BLOCK">
                                <div class="table-responsive custom-table">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th width="50px">Currency</th>
                                            <th>Count</th>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TransactionSummary) > 0)
                                        @foreach($TransactionSummary as $ts)
                                        <tr>
                                            <td>{{ $ts['currency'] }}</td>
                                            <td>{{ $ts['block_count'] }}</td>
                                            <td>{{
                                                number_format($ts['block_amount'],2,".",",") }}</td>
                                            <td>{{ round($ts['block_percentage'],2) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center text-white" colspan="4">No record found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 bg-secondary-2">
                        <div class="row">
                            <div class="col-md-12 mt-2 mb-2">
                                <a href="{{ route('transaction-volume') }}" class="btn btn-primary btn-sm">View All</a>

                                <div class="pull-right">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.7088 10.5417C13.7062 10.8377 13.7445 11.1325 13.8228 11.418L3.19384 11.418C2.96145 11.418 2.73857 11.3256 2.57424 11.1613C2.40991 10.997 2.31759 10.7741 2.31759 10.5417C2.31759 10.3093 2.40991 10.0864 2.57424 9.92211C2.73857 9.75778 2.96145 9.66547 3.19384 9.66547L13.8228 9.66547C13.7445 9.95089 13.7062 10.2458 13.7088 10.5417Z" fill="#B3ADAD"/>
                                    <path d="M7.57505 16.6755C7.57241 16.9714 7.61075 17.2663 7.68896 17.5518L3.1938 17.5518C2.96141 17.5518 2.73853 17.4594 2.5742 17.2951C2.40987 17.1308 2.31755 16.9079 2.31755 16.6755C2.31755 16.4431 2.40987 16.2202 2.5742 16.0559C2.73853 15.8916 2.96141 15.7993 3.1938 15.7993L7.68896 15.7993C7.61075 16.0847 7.57241 16.3796 7.57505 16.6755Z" fill="#B3ADAD"/>
                                    <path d="M18.9663 17.5518L14.4711 17.5518C14.623 16.9775 14.623 16.3735 14.4711 15.7993L18.9663 15.7993C19.1987 15.7993 19.4216 15.8916 19.5859 16.0559C19.7502 16.2202 19.8425 16.4431 19.8425 16.6755C19.8425 16.9079 19.7502 17.1308 19.5859 17.2951C19.4216 17.4594 19.1987 17.5518 18.9663 17.5518Z" fill="#B3ADAD"/>
                                    <path d="M18.9663 5.28424L8.33737 5.28424C8.41559 4.99881 8.45392 4.70393 8.45128 4.40799C8.45392 4.11205 8.41559 3.81717 8.33737 3.53174L18.9663 3.53174C19.1987 3.53174 19.4216 3.62406 19.5859 3.78839C19.7502 3.95271 19.8425 4.17559 19.8425 4.40799C19.8425 4.64038 19.7502 4.86326 19.5859 5.02759C19.4216 5.19192 19.1987 5.28424 18.9663 5.28424Z" fill="#B3ADAD"/>
                                    <path d="M4.94633 7.03673C4.46409 7.03808 3.99076 6.90675 3.57816 6.65711C3.16555 6.40747 2.82957 6.04914 2.60698 5.62134C2.38439 5.19354 2.28376 4.71275 2.31613 4.23159C2.34849 3.75043 2.51258 3.28744 2.79045 2.8933C3.06832 2.49915 3.44926 2.18904 3.89158 1.9969C4.3339 1.80476 4.82055 1.73799 5.29827 1.80391C5.77599 1.86983 6.22637 2.06589 6.60013 2.37063C6.97388 2.67537 7.25661 3.07706 7.41736 3.53173C7.62748 4.097 7.62748 4.71896 7.41736 5.28423C7.23657 5.79556 6.90206 6.23846 6.45967 6.55221C6.01728 6.86596 5.48868 7.0352 4.94633 7.03673Z" fill="#B3ADAD"/>
                                    <path d="M17.2137 13.1705C16.6714 13.1689 16.1428 12.9997 15.7004 12.6859C15.258 12.3722 14.9235 11.9293 14.7427 11.418C14.5326 10.8527 14.5326 10.2307 14.7427 9.66545C14.9034 9.21079 15.1862 8.8091 15.5599 8.50436C15.9337 8.19961 16.3841 8.00355 16.8618 7.93764C17.3395 7.87172 17.8262 7.93849 18.2685 8.13063C18.7108 8.32277 19.0917 8.63288 19.3696 9.02702C19.6475 9.42117 19.8116 9.88416 19.8439 10.3653C19.8763 10.8465 19.7757 11.3273 19.5531 11.7551C19.3305 12.1829 18.9945 12.5412 18.5819 12.7908C18.1693 13.0405 17.696 13.1718 17.2137 13.1705Z" fill="#B3ADAD"/>
                                    <path d="M11.0799 19.3042C10.5375 19.3027 10.0089 19.1334 9.56654 18.8197C9.12415 18.5059 8.78963 18.063 8.60885 17.5517C8.39873 16.9864 8.39873 16.3645 8.60885 15.7992C8.79304 15.2917 9.12902 14.8533 9.57112 14.5434C10.0132 14.2335 10.54 14.0673 11.0799 14.0673C11.6198 14.0673 12.1465 14.2335 12.5886 14.5434C13.0307 14.8533 13.3667 15.2917 13.5509 15.7992C13.761 16.3645 13.761 16.9864 13.5509 17.5517C13.3701 18.063 13.0356 18.5059 12.5932 18.8197C12.1508 19.1334 11.6222 19.3027 11.0799 19.3042Z" fill="#B3ADAD"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#SUCCESSFUL" data-bs-toggle="tab">
                                    Successful
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#DECLINED" data-bs-toggle="tab">
                                    Declined
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#CHARGEBACKS" data-bs-toggle="tab">
                                    Chargebacks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#REFUND" data-bs-toggle="tab">
                                    Refund
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#SUSPICIOUS" data-bs-toggle="tab">
                                    Marked Transactions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#RETRIEVAL" data-bs-toggle="tab">
                                    Retrieval
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#BLOCK" data-bs-toggle="tab">
                                    Block
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h5 class="card-title">Transactions Summary</h5>
                </div>
                <div class="card-header-toolbar d-flex align-items-center">
                    <a href="{!! route('transaction-summary') !!}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="myChart2"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h5 class="card-title">Transaction break-up</h5>
                </div>
                <div class="card-header-toolbar d-flex align-items-center form-dark">
                    <select class="default-select form-control-sm" id="breakUp">
                        <option value="1">Daily</option>
                        <option value="2" selected="selected">Weekly</option>
                        <option value="3">Monthly</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('customScript')
<script src="{{ storage_asset('ThemeFinvert/js/apexcharts.js') }}"></script>

<script src="{{ storage_asset('theme/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: [
            'Successful',
            'Declined',
            'Chargeback',
            'Marked',
            'Refund'
          ],
          datasets: [{
            label: 'Wallet Insight',
            data: [<?php echo ($transactionWeek->successfullC); ?>, <?php echo ($transactionWeek->declinedC); ?>, <?php echo ($transactionWeek->chargebackC); ?>, <?php echo ($transactionWeek->suspiciousC); ?>,<?php echo ($transactionWeek->refundC); ?>],
            backgroundColor: [
              '#56C65A',
              '#F44336',
              '#c1bcbc',
              '#D58944',
              '#ffd956'
            ],

            hoverOffset: 4
          }]
        },
        options: {
          plugins: {
            legend: {
                  display: true,
                  position: 'right'
              }
          }
        }
    });

    $('#breakUp').change((function(){
        var selectedValue = document.getElementById("breakUp").value;
        $.ajax({
            url: "{{ URL::route('get-transaction-break-up') }}",
            method:"POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'selectedValue':selectedValue
            },
            success:function(data)
            {
                console.log(data.status);
                if(data.status == 1)
                {
                    console.log(data.successfullC,data.declinedC,data.chargebackC,data.suspiciousC,data.refundC);
                    myChart.data.datasets[0].data[0] = [data.successfullC];
                    myChart.data.datasets[0].data[1] = [data.declinedC];
                    myChart.data.datasets[0].data[2] = [data.chargebackC];
                    myChart.data.datasets[0].data[3] = [data.suspiciousC];
                    myChart.data.datasets[0].data[4] = [data.refundC];
                    myChart.update();
                }else{
                    toastr.error('Something went wrong !!');
                    window.setTimeout(function(){location.reload(true)},2000);
               }

            }
        });
    }));

    <?php 
        $transactionsDate = array_column($transactionsLine, 'date');
        $successTransactions = array_column($transactionsLine, 'successTransactions');
        $declinedTransactions = array_column($transactionsLine, 'declinedTransactions');
    ?>

    var ctx2 = document.getElementById("myChart2").getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'line',
          data: {
          labels: <?php echo json_encode($transactionsDate); ?>,
          datasets: [{ 
              data: <?php echo json_encode($successTransactions); ?>,
              label: "Successful",
              borderColor: "#56C65A",
              fill: false
            }, { 
              data: <?php echo json_encode($declinedTransactions); ?>,
              label: "Declined",
              borderColor: "#F44336",
              fill: false
            }
          ]
        },
        options: {
          title: {
            display: true,
            text: 'Wallet Insight'
          }
        }
    });
</script>
<script src="{{ storage_asset('theme/vendor/peity/jquery.peity.min.js') }}"></script>
<script src="{{ storage_asset('theme/vendor/apexchart/apexchart.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            getDashboardData();
        }, 100);

        function getDashboardData() {
            $.ajax({
                url: '{{ route("get-user-dashbaord-data") }}',
                type: 'GET',
                success: function (data) {
                    $('#latestTransactionsData').html(data.latestTransactionsData);
                },
            });
        }

        var radialBar = function(){
            var options = {
              series: ['<?php echo number_format($transaction->successfullP,2);?>'],
              chart: {
              height: 280,
              type: 'radialBar',
              offsetY: -10
            },
            plotOptions: {
              radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                  name: {
                    fontSize: '16px',
                    color: undefined,
                    offsetY: 120
                  },
                  value: {
                    offsetY: 0,
                    fontSize: '34px',
                    color: 'black',
                    formatter: function (val) {
                      return val + "%";
                    }
                  }
                }
              }
            },
            fill: {
              type: 'gradient',
              colors:'#56C65A',
              gradient: {
                  shade: 'dark',
                  shadeIntensity: 0.15,
                  inverseColors: false,
                  opacityFrom: 1,
                  opacityTo: 1,
                  stops: [0, 50, 65, 91]
              },
            },
            stroke: {
                lineCap: 'round',
              colors:'#56C65A'
            },
            labels: [''],
            };

            var chart = new ApexCharts(document.querySelector("#radialBar"), options);
            chart.render();
        }
        radialBar();

        var donutChart = function(){
            $("span.donut").peity("donut", {
                width: "90",
                height: "90"
            });
        }   
        donutChart();
    });
</script>

@endsection