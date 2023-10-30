@component('mail::message')

{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => ''])
<h1>{{ config('app.name') }}</h1>
@endcomponent
@endslot

<style>
  table {
    border-collapse: separate;
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
    width: 100%;
  }

  table td {
    font-family: sans-serif;
    font-size: 14px;
    vertical-align: top;
  }

  p {
    margin: 0px;
  }

  /* -------------------------------------
      BUTTONS
  ------------------------------------- */
  .btn {
    box-sizing: border-box;
    width: 100%;
  }

  .btn>tbody>tr>td {
    padding-bottom: 15px;
  }

  .btn table {
    width: auto;
  }

  .btn table td {
    border-radius: 5px;
    text-align: center;
  }

  .btn a {
    border: solid 1px #F44336 !important;
    border-radius: 5px;
    box-sizing: border-box;
    color: #F44336 !important;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    margin: 0;
    padding: 12px 25px;
    text-decoration: none;
    text-transform: capitalize;
  }

  /* -------------------------------------
      RESPONSIVE AND MOBILE FRIENDLY STYLES
  ------------------------------------- */
  @media only screen and (max-width: 620px) {
    table[class=body] h1 {
      font-size: 28px !important;
      margin-bottom: 10px !important;
    }

    table[class=body] p,
    table[class=body] ul,
    table[class=body] ol,
    table[class=body] td,
    table[class=body] span,
    table[class=body] a {
      font-size: 16px !important;
    }

    table[class=body] .wrapper,
    table[class=body] .article {
      padding: 10px !important;
    }

    table[class=body] .content {
      padding: 0 !important;
    }

    table[class=body] .container {
      padding: 0 !important;
      width: 100% !important;
    }

    table[class=body] .main {
      border-left-width: 0 !important;
      border-radius: 0 !important;
      border-right-width: 0 !important;
    }

    table[class=body] .btn table {
      width: 100% !important;
    }

    table[class=body] .btn a {
      width: 100% !important;
    }

    table[class=body] .img-responsive {
      height: auto !important;
      max-width: 100% !important;
      width: auto !important;
    }
  }
</style>

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <p style="text-transform: capitalize;">Dear Sir/Madam,</p>
    </td>
  </tr>
  <tr>
    <td>
      <p>We have received a notification from the acquiring bank stating that a transaction made with the below
        mentioned card details is marked as suspicious.</p><br>
      <p>Please find the Transaction details in the table below.</p><br />
      <table border="0" cellpadding="0" cellspacing="0"
        style="width: 100%; text-align: left; background-color: #2B2B2B; padding: 15px 15px 0px 15px; border-radius: 3px;">
        <tbody>
          <tr>
            <td>
              <p><strong>Order ID</strong></p>
            </td>
            <td>
              <p><span>{!! $order_id !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Name</strong></p>
            </td>
            <td>
              <p><span>{!! $first_name !!} {!! $last_name !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Email</strong></p>
            </td>
            <td>
              <p style="color: black;"><span>{!! $email !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Card Number</strong></p>
            </td>
            <td>
              <p><span>{!! $card_no !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Suspicious Transaction Date</strong></p>
            </td>
            <td>
              <p><span>{!! $flagged_date !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Transaction Date</strong></p>
            </td>
            <td>
              <p><span>{!! $created_at !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Amount</strong></p>
            </td>
            <td>
              <p><span>{!! $amount !!} {!! $currency !!}</span></p>
            </td>
          </tr>
        </tbody>
      </table>
      <br />
      <p>To resolve this dispute, we kindly request you to submit the KYC documentation of the card holder using the
        link
        provided below within 72 hours of receiving this notification.</p>
      <a href="{!! $url !!}" target="_blank" style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
            box-sizing: border-box;
            text-decoration: none;
            background-color: #F44336;
            padding: 15px 30px;
            border-radius: 30px;
            line-height: 60px;
            color: #FFF;
            font-weight: bold;
            border: 3px solid #fff;">Please Click Here
      </a>
      <p style="margin-top: 10px">
        Please note that failing to submit the required documentation within the given time period may result in the
        cancellation of the transaction and the amount being charged to your payments account and attributed to the
        card's issuing bank.
      </p>
      <br>
      <p>To assist you in submitting the relevant documents, we suggest submitting receipts, invoices, registration
        forms, signed transaction receipts, delivery notes, customer contact information, identity card copy, copy of
        the card used
        for the transaction (only the front side, intermediate digits of the card masked), communication exchanged with
        the
        customer.</p><br />
    </td>
  </tr>
</table>
<br>
<p style="font-weight: bold; line-height: 16px;"><small>
    Kindly upload below documents for the above flagged transactions as requested by the acquiring bank.<br>
    1) Proof of transaction (Bank confirmation email, transaction detail from the statement, etc.)<br>
    2) KYC details like proof of ID, Address and any other details deemed relevant.</small>
</p><br />
<p>Thank you for your cooperation.</p>

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}
@endcomponent
@endslot

@endcomponent