   @if (auth()->guard('admin')->user()->can(['overview-transaction-statistics']))
       @php
           $liveMerchantP = $merchants->totalMerchant ? round($merchants->liveMerchant / $merchants->totalMerchant, 4) * 100 : '0';
           $notLiveMerchantP = $merchants->totalMerchant ? round($merchants->notLiveMerchant / $merchants->totalMerchant, 4) * 100 : '0';
       @endphp
   @endif

   <div class="row">
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <h2>{{ $merchants->totalMerchant }}</h2>
               <p class="mb-1" style="color: #82CD47;">Total Merchant</p>
               <p class="total">Total Count : <span style="color: var(--main-primary);">
                       {{ $merchants->totalMerchant }}</span></p>
           </div>
       </div>
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <h2>{{ $merchants->liveMerchant }}</h2>
               <p class="mb-1" style="color: #5F9DF7;">Live Merchant</p>
               <p class="total">Total Percentage : <span style="color: var(--main-primary);"> {{ $liveMerchantP }}
                       %</span></p>
           </div>
       </div>
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <h2>{{ $merchants->notLiveMerchant }}</h2>
               <p class="mb-1" style="color: #C47AFF;">Pending for Live Merchant</p>
               <p class="total">Total Percentage : <span style="color: var(--main-primary);">
                       {{ $notLiveMerchantP }}
                       %</span></p>
           </div>
       </div>
   </div>
