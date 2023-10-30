<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ storage_asset('ThemeCryptostudio/images/favicon.png') }}" />
    <title>{{ config('app.name') }} | Show Report</title>

    <style type="text/css" media="screen">
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        a {
            color: #B3ADAD;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 100%;
            margin: 0 auto;
            background: #202020;
            font-size: 14px;
            font-family: Arial;
            color: #B3ADAD;
        }

        main {
            width: 21cm;
            background: #3D3D3D;
            margin: 30px auto;
            padding: 30px 0px !important;
            position: relative;
            border-radius: 5px;
            color: #B3ADAD;

        }

        #logo {
            margin-bottom: 0px;
            float: left;
        }

        #logo img {
            width: 200px;
        }

        .title {
            color: #B3ADAD;
            float: right;
        }

        .title h1 {
            margin: 25px 0px 0px 0px;
        }

        #from {
            float: left;
            width: 33.33%;
            font-size: 14px;
            color: #B3ADAD;
        }

        #project {
            float: left;
            width: 50%;
            font-size: 14px;
            color: #B3ADAD;
        }

        #project span {
            color: #B3ADAD;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: left;
            text-align: left;
            text-align: left;
            width: 50%;
            font-size: 14px;
            color: #B3ADAD;
        }

        .header1 {
            padding: 0px 30px;
            margin-bottom: 15px;
        }

        .header2 {
            /* background: linear-gradient(to right, rgb(244, 67, 54), rgb(173 79 70)) !important; */
            background: #1B1919 !important;
            padding: 15px 30px;
            color: #B3ADAD;
        }

        #project div,
        #company div {
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .body-table {
            /*margin: 0px 30px 30px 30px;*/
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 15px;
        }

        .table th,
        .table td {
            text-align: left;
        }

        .table th {
            padding: 10px 20px;
            color: #B3ADAD;
            white-space: nowrap;
            font-weight: 900;
            background: #1B1919 !important;
        }

        .table .service,
        .table .desc {
            text-align: left;
        }

        .table td {
            padding: 5px 20px;
        }

        .table td.service,
        .table td.desc {
            vertical-align: top;
        }

        .table td.unit,
        .table td.qty,
        .table td.total {
            font-size: 1.2em;
        }

        .table td.grand {
            border-top: 1px solid #5D6975;
            ;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #1B1919;
            width: 100%;
            bottom: 0;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bluebg td {
            background: #1B1919 !important;
            color: #B3ADAD;
        }

        .greenbg td {
            background: #1B1919 !important;
            color: #B3ADAD;
        }

        .redbg td {
            background: #1B1919 !important;
            color: #B3ADAD;
        }

        .clear-header {
            clear: both;
        }
    </style>
</head>

<body>
    <main>
        <header class="clearfix">
            <div class="header1">
                <div id="logo">
                    <img src="{{ config('app.logo_url') }}">
                </div>
                <div class="title">
                    <h1>{{ $data->processor_name }}</h1>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="header2">
                <div id="company" class="clearfix">
                    <div>To</div>
                    <div><strong>{{ $data->company_name }}</strong></div>
                    <div><strong>{{ $data->phone_no }}</strong></div>
                </div>
                <div id="project">
                    <div style="float: right;">
                        <div><strong>Settlement Date</strong> {{ $data->start_date }} to {{ $data->end_date }}</div>
                        <div><strong>Settlement No.</strong> {{ $data->invoice_no }}</div>
                        <div><strong>MID</strong> {{ $data->user_id }}</div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </header>
        <div class="body-table">
            <?php
            $totalPayoutReport = 0;
            ?>
            @foreach ($childData as $key => $value)
                <?php
                $totalPayoutReport += $value->net_settlement_amount_in_usd;
                ?>
                <table style="width: 250px; margin-top: 15px; margin-left: -2px;">
                    <thead>
                        <tr>
                            <td
                                style="font-weight: bold;background: #1B1919 !important; color: #B3ADAD; padding: 10px 15px;">
                                Currency : {{ $value->currency }} for
                                {{ $value->card_type == 'Other' ? 'Visa' : $value->card_type }}
                            </td>
                        </tr>
                    </thead>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Summary</th>
                            <th>Tally</th>
                            <th class="right">Capital</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // echo "<pre>";print_r($value);
                        ?>
                        <tr>
                            <td>Successful transaction</td>
                            <td>{{ number_format($value->approve_transaction_count, 0) }}</td>
                            <td class="right">{{ round($value->approve_transaction_sum, 2) }}</td>
                        </tr>

                        <tr>
                            <td>Declined transaction </td>
                            <td>{{ number_format($value->declined_transaction_count, 0) }}</td>
                            <td class="right">{{ round($value->declined_transaction_sum, 2) }}</td>
                        </tr>

                        <tr>
                            <td>Total Transactions</td>
                            <td>{{ number_format($value->total_transaction_count, 0) }}</td>
                            <td class="right" style="font-weight: 900;">{{ round($value->total_transaction_sum, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Chargebacks</td>
                            <td>{{ number_format($value->chargeback_transaction_count, 0) }}</td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ round($value->chargeback_transaction_sum, 2) }}</td>
                        </tr>

                        <?php
                        $totalAmount = 0;
                        $totalCount = 0;
                        if ($value->remove_past_chargebacks > 0) {
                            $totalCount += number_format($value->remove_past_chargebacks, 0);
                            $totalAmount += $value->past_chargebacks_sum;
                        }
                        ?>
                        <tr>
                            <td>Refunds</td>
                            <td>{{ number_format($value->refund_transaction_count, 0) }}</td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ round($value->refund_transaction_sum, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Marked Transactions</td>
                            <td>{{ number_format($value->flagged_transaction_count, 0) }}</td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ round($value->flagged_transaction_sum, 2) }}
                            </td>
                        </tr>
                        <?php
                        if ($value->remove_past_flagged > 0) {
                            $totalCount += number_format($value->remove_past_flagged, 0);
                            $totalAmount += $value->past_flagged_sum;
                        }
                        ?>
                        <tr>
                            <td>Retrieval</td>
                            <td>{{ number_format($value->retrieval_transaction_count, 0) }}</td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ round($value->retrieval_transaction_sum, 2) }}</td>
                        </tr>
                        <?php
                        if ($value->remove_past_retrieval > 0) {
                            $totalCount += number_format($value->remove_past_retrieval, 0);
                            $totalAmount += $value->past_retrieval_sum;
                        }
                        if ($value->return_fee > 0) {
                            $totalCount += $value->return_fee_count;
                            $totalAmount += $value->return_fee;
                        }
                        ?>
                        @if ($totalCount > 0)
                            <tr>
                                <td>Reversed Transaction Value</td>
                                <td>{{ number_format($totalCount, 0) }}</td>
                                <td class="right" style="color: green; font-weight: 900;">{{ round($totalAmount, 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Total Settlement</td>
                            <td>{{ number_format($value->approve_transaction_count, 0) }}</td>
                            <td class="right" style="font-weight: 900;">{{ round($value->sub_total, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Merchant Discount Rate </td>
                            @if (!empty($value->apm_mdr) || $value->apm_mdr != null)
                                <td>{{ $value->apm_mdr }} %</td>
                                <td class="right" style="color: #F94687; font-weight: 900;">
                                    {{ $value->mdr }}
                                </td>
                            @elseif ($value->card_type == 'Amex')
                                <td>{{ $data->merchant_discount_rate_amex }} %</td>
                                <td class="right" style="color: #F94687; font-weight: 900;">
                                    {{ $value->mdr }}
                                </td>
                            @elseif($value->card_type == 'Discover')
                                <td>{{ $data->merchant_discount_rate_discover }} %</td>
                                <td class="right" style="color: #F94687; font-weight: 900;">
                                    {{ $value->mdr }}
                                </td>
                            @elseif($value->card_type == 'MasterCard')
                                <td>{{ $data->merchant_discount_rate_master }} %</td>
                                <td class="right" style="color: #F94687; font-weight: 900;">
                                    {{ $value->mdr }}
                                </td>
                            @else
                                <td>{{ $data->merchant_discount_rate }} %</td>
                                <td class="right" style="color: #F94687; font-weight: 900;">
                                    {{ $value->mdr }}
                                </td>
                            @endif


                        </tr>
                        <tr>
                            <td>Rolling Reserve (180 Days)</td>
                            <td>{{ $data->rolling_reserve_paercentage }} %</td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ $value->rolling_reserve }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Transaction Fees</strong></td>
                            <td><strong>Avg Fee Rate *</strong></td>
                            <td class="right"><strong>Fee Amount</strong></td>
                        </tr>
                        <tr>
                            <td>Total Transaction Fee</td>
                            <td>{{ $data->transaction_fee_paercentage }}</td>
                            <td class="right">{{ $value->transaction_fee }}</td>
                        </tr>
                        {{-- <tr>
            <td>Declined</td>
            <td>{{ $data->declined_fee_paercentage }}</td>
            <td class="right">{{ $value->declined_transaction_fee }}</td>
          </tr> --}}
                        <tr>
                            <td>Chargeback Fee</td>
                            <td>{{ $data->chargebacks_fee_paercentage }}</td>
                            <td class="right">{{ $value->chargeback_fee }}</td>
                        </tr>
                        <tr>
                            <td>Refund Fee</td>
                            <td>{{ $data->refund_fee_paercentage }}</td>
                            <td class="right">{{ $value->refund_fee }}</td>
                        </tr>
                        <tr>
                            <td>Marked Transactions Fee</td>
                            <td>{{ $data->flagged_fee_paercentage }}</td>
                            <td class="right">{{ $value->flagged_fee }}</td>
                        </tr>
                        <tr>
                            <td>Retrieval Fee</td>
                            <td>{{ $data->retrieval_fee_paercentage }}</td>
                            <td class="right">{{ $value->retrieval_fee }}</td>
                        </tr>
                        <tr>
                            <td>Calculate Total fees based on USD</td>
                            <td></td>
                            <td class="right" style="color: #F94687; font-weight: 900;">
                                {{ $value->transactions_fee_total }}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:15px;">Reversed fee</td>
                            <td style="padding-bottom:15px;">0</td>
                            <td class="right" style="color: green; font-weight: 900;padding-bottom:15px;">
                                {{ $value->past_flagged_fee }}
                            </td>
                        </tr>
                        <tr class="greenbg">
                            <td colspan="2"><strong>TOTAL PAYOUT</strong></td>
                            <td class="total" style="font-weight: 900;">{{ $value->net_settlement_amount }}</td>
                        </tr>
                        <tr class="greenbg">
                            <td colspan="2"><strong>TOTAL PAYOUT IN USD</strong></td>
                            <td class="total" style="font-weight: 900;">{{ $value->net_settlement_amount_in_usd }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endforeach
            <table class="table">
                <tbody>
                    <tr class="greenbg">
                        <td colspan="2"><strong>Total payout of all Currency in USD</strong></td>
                        <td class="total" style="font-weight: 900;">{{ $totalPayoutReport }}</td>
                    </tr>
                    <tr class="greenbg">
                        <td colspan="2"><strong>Pre arbitration penalty</strong></td>
                        <td class="total" style="font-weight: 900;">{{ $data->pre_arbitration_fee }}</td>
                    </tr>
                    <?php
                    $finalSettlementSub = $totalPayoutReport - $data->pre_arbitration_fee;
                    $settlement_fee = $data->user->settlement_fee ?? 2.5;
                    
                    $final_settlement = $finalSettlementSub - ($finalSettlementSub * $settlement_fee) / 100;
                    ?>
                    <tr class="greenbg">
                        <td colspan="2"><strong>Final Settlement Amount(After deduction of fee @
                                {{ $settlement_fee }}%)</strong></td>
                        <td class="total" style="font-weight: 900;">{{ round($final_settlement, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            @if ($data->id == 62 || $data->id == 63)
                <div style="margin-top: 10px; text-align: center; color:red;">
                    <strong>Pre-Arbitration penalty $5000</strong>
                </div>
            @endif
        </div>
    </main>
</body>

</html>
