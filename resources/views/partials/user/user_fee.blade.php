<div class="row table-responsive custom-table">
    <div class="col-md-6 mb-2">
        <table class="table table-striped table-borderless">
            <tr>
                <td><b> Visa -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td><b> Master -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate_master_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td><b> Amex -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate_amex_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td><b> Discover -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate_discover_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td><b> UPI -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate_upi }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td><b> Crypto -</b> Merchant Discount Rate (%)</td>
                <td>{{ \auth::user()->merchant_discount_rate_crypto }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>

            <tr>
                <td>Transaction Fee</td>
                <td>{{ \auth::user()->transaction_fee }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Chargeback Fee</td>
                <td>{{ \auth::user()->chargeback_fee }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Retrieval Fee</td>
                <td>{{ \auth::user()->retrieval_fee }}</td>
            </tr>
        </table>

    </div>
    <div class="col-md-6 mb-2">
        <table class="table table-striped table-borderless">
            <tr>
                <td><b> Visa -</b> Setup Fee</td>
                <td>{{ \auth::user()->setup_fee }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td><b> Master -</b> Setup Fee</td>
                <td>{{ \auth::user()->setup_fee_master_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td><b> Amex -</b> Setup Fee</td>
                <td>{{ \auth::user()->setup_fee_amex_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td><b> Discover -</b> Setup Fee</td>
                <td>{{ \auth::user()->setup_fee_discover_card }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Rolling Reserve (%)</td>
                <td>{{ \auth::user()->rolling_reserve_paercentage }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Refund Fee</td>
                <td>{{ \auth::user()->refund_fee }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Marked Transaction Fee</td>
                <td>{{ \auth::user()->flagged_fee }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px 0px;"></td>
            </tr>
            <tr>
                <td>Minimum Settlement Amount</td>
                <td>{{ \auth::user()->minimum_settlement_amount }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6 mb-2">
        <table class="table table-striped table-borderless">
            <tr>
                <td>Payment Frequency</td>
                <td>Weekly payment with
                    {{ auth::user()->payment_frequency }} weeks arrears</td>
            </tr>
        </table>
    </div>
    @if (isset(\auth::user()->apm))
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header">
                    <h4>APM & Rates</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>APM</th>
                                <th>Rates %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $userApms = json_decode(\auth::user()->apm, true);
                            @endphp
                            @foreach ($userApms as $userApm)
                                <tr>
                                    <td>
                                        {{ $userApm['bank_name'] }}
                                    </td>
                                    <td>{{ $userApm['apm_mdr'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
