<div class="row">
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #82CD47;">
            <h2 class="text-white">{{ round($transaction->successfullP, 2) }} %</h2>
            <p class="mb-1">Successful</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->successfullC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #FF5858;">
            <h2 class="text-white">{{ round($transaction->declinedP, 2) }} %</h2>
            <p class="mb-1">Declined</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->declinedC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #4F738E;">
            <h2 class="text-white">{{ round($transaction->suspiciousP, 2) }} %</h2>
            <p class="mb-1">Marked</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->suspiciousC }}</span></p>
        </div>
    </div>
    <div class="col-lg-3 mb-2">
        <div class="merchantTxnCard text-white" style="background-color: #b16ee7;">
            <h2 class="text-white">{{ round($transaction->refundP, 2) }} %</h2>
            <p class="mb-1">Refund</p>
            <p class="total">Total Count : <span style="color: var(--main-primary);">
                    {{ $transaction->refundC }}</span></p>
        </div>
    </div>
</div>
