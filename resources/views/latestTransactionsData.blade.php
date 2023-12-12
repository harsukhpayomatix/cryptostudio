<table id="latest_transactions" class="table mb-0 table-borderless table-striped">
   <thead>
      <tr>
         <th>Order No.</th>
         <th class="text-center">Date</th>
         <th class="text-center">Amount</th>
         <th class="text-center">Currency</th>
         <th class="text-center">Status</th>
      </tr>
   </thead>
    <tbody>
        @if(isset($latestTransactionsData) && count($latestTransactionsData)>0)
            @foreach($latestTransactionsData as $allTransaction)
                <tr>
                    <td>{{ $allTransaction->order_id }}</td>
                    <td class="text-center">{{ convertDateToLocal($allTransaction->created_at, 'd-m-Y') }}</td>
                    <td class="text-right">{{ $allTransaction->amount }}</td>
                    <td class="text-center">{{ $allTransaction->currency }}</td>
                    <td class="text-center">
                        @if($allTransaction->status == '1')
                            <label class="badge badge-success">Success</label>
                        @elseif($allTransaction->status == '2')
                            <label class="badge badge-warning">Pending</label>
                        @elseif($allTransaction->status == '3')
                            <label class="badge badge-yellow">Canceled</label>
                        @elseif($allTransaction->status == '4')
                            <label class="badge badge-primary">To Be Confirm</label>
                        @elseif($allTransaction->status == '5')
                            <label class="badge badge-primary">Blocked</label>
                        @else
                            <label class="badge badge-danger">Declined</label>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
               <td colspan="10">No Record found!.</td>
            </tr>
        @endif
    </tbody>
</table>