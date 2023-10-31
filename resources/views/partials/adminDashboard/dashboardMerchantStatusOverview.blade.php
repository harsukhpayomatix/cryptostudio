   @if (auth()->guard('admin')->user()->can(['overview-transaction-statistics']))
       @php
           $liveMerchantP = $merchants->totalMerchant ? round($merchants->liveMerchant / $merchants->totalMerchant, 4) * 100 : '0';
           $notLiveMerchantP = $merchants->totalMerchant ? round($merchants->notLiveMerchant / $merchants->totalMerchant, 4) * 100 : '0';
       @endphp
   @endif

   <div class="row">
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <div class="icon-flag">
                   <svg width="20" height="46" viewBox="0 0 20 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <path d="M10.1908 45.023L0.4375 39.0992V0.0229797H10.1908H19.9442V39.1092L10.1908 45.023Z"
                           fill="url(#paint0_linear_112_1408)" />
                       <defs>
                           <linearGradient id="paint0_linear_112_1408" x1="31.2716" y1="2.42082" x2="10.1908"
                               y2="45.023" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#4AA900" />
                               <stop offset="1" stop-color="#82CD47" />
                           </linearGradient>
                       </defs>
                   </svg>
               </div>
               <h2>{{ $merchants->totalMerchant }}</h2>
               <p class="mb-1" style="color: #82CD47;">Total Merchant</p>
               <p class="total">Total Count : <span style="color: #4e738e;">
                       {{ $merchants->totalMerchant }}</span></p>
           </div>
       </div>
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <div class="icon-flag">
                   <svg width="21" height="46" viewBox="0 0 21 46" fill="none"
                       xmlns="http://www.w3.org/2000/svg">
                       <path d="M10.518 45.023L0.764648 39.0992V0.0229797H10.518H20.2713V39.1092L10.518 45.023Z"
                           fill="url(#paint0_linear_112_1407)" />
                       <defs>
                           <linearGradient id="paint0_linear_112_1407" x1="10.518" y1="0.0229797" x2="10.518"
                               y2="45.023" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#0960DD" />
                               <stop offset="1" stop-color="#5F9DF7" />
                           </linearGradient>
                       </defs>
                   </svg>
               </div>
               <h2>{{ $merchants->liveMerchant }}</h2>
               <p class="mb-1" style="color: #5F9DF7;">Live Merchant</p>
               <p class="total">Total Percentage : <span style="color: #4e738e;"> {{ $liveMerchantP }}
                       %</span></p>
           </div>
       </div>
       <div class="col-lg-4 mb-2">
           <div class="merchantTxnCard">
               <div class="icon-flag">
                   <svg width="20" height="46" viewBox="0 0 20 46" fill="none"
                       xmlns="http://www.w3.org/2000/svg">
                       <path d="M9.84173 45.023L0.0883789 39.0992V0.0229797H9.84173H19.5951V39.1092L9.84173 45.023Z"
                           fill="url(#paint0_linear_112_1406)" />
                       <defs>
                           <linearGradient id="paint0_linear_112_1406" x1="9.84173" y1="0.0229797" x2="9.84173"
                               y2="45.023" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#9618FB" />
                               <stop offset="1" stop-color="#C47AFF" />
                           </linearGradient>
                       </defs>
                   </svg>
               </div>
               <h2>{{ $merchants->notLiveMerchant }}</h2>
               <p class="mb-1" style="color: #C47AFF;">Pending for Live Merchant</p>
               <p class="total">Total Percentage : <span style="color: #4e738e;">
                       {{ $notLiveMerchantP }}
                       %</span></p>
           </div>
       </div>
   </div>
