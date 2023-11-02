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
            <div class="col-lg-6">
                <div class="merchantTxnCard text-white" style="background-color: #82CD47;">
                    <h2 class="text-white">{{ round($transaction->successfullP,2) }} %</h2>
                    <p class="mb-1">Successful</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->successfullC }}</span></p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="merchantTxnCard text-white" style="background-color: #FF5858;">
                    <h2 class="text-white">{{round($transaction->declinedP,2)}} %</h2>
                    <p class="mb-1">Declined</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{$transaction->declinedC}}</span></p>
                </div>
            </div>
            <div class="col-lg-6 mb-2 mt-2">
                <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
                    <h2 class="text-white">{{ round($transaction->suspiciousP,2) }} %</h2>
                    <p class="mb-1">Marked</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->suspiciousC }}</span></p>
                </div>
            </div>
            <div class="col-lg-6 mb-2 mt-2">
                <div class="merchantTxnCard text-white" style="background-color: #b16ee7;">
                    <h2 class="text-white">{{ round($transaction->refundP,2) }} %</h2>
                    <p class="mb-1">Refund</p>
                    <p class="total">Total Count : <span style="color: var(--main-primary);"> {{ $transaction->refundC }}</span></p>
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
            <div class="card-body p-0">
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
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 mt-2 mb-2">
                                <a href="{{ route('transaction-volume') }}" class="btn btn-primary btn-sm">View All</a>
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
<script src="{{ storage_asset('ThemeCryptoStudio/js/apexcharts.js') }}"></script>

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