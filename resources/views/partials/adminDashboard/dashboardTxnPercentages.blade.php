<div class="row">
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ round($transaction->successfullP, 2) }} %</h2>
            <p class="mb-1" style="color: #82CD47;">Successful</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->successfullC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ round($transaction->declinedP, 2) }} %</h2>
            <p class="mb-1" style="color: #5F9DF7;">Declined</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->declinedC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ round($transaction->suspiciousP, 2) }} %</h2>
            <p class="mb-1" style="color: #C47AFF;">Marked</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->suspiciousC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard">
            <h2>{{ round($transaction->refundP, 2) }} %</h2>
            <p class="mb-1" style="color: #FF5858;">Refund</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->refundC }}</span></p>
        </div>
    </div>
</div>
