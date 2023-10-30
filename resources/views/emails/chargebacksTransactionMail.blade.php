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
    border: solid 1px #F44336;
    border-radius: 5px;
    box-sizing: border-box;
    color: #F44336;
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
      <p style="text-transform: capitalize;">Dear {{ $userName }},</p>
    </td>
  </tr>
  <tr>
    <td>
      <p>We regret to inform you that a dispute has been raised regarding a transaction made by your client using the
        below
        mentioned card details:
      </p><br>
      <table border="0" cellpadding="5" cellspacing="0"
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
              <p><span>{!! $email !!}</span></p>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Card Number</strong></p>
            </td>
            <td>
              <p><span>{!! $card_no !!}</span>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>Chargeback Date</strong></p>
            </td>
            <td>
              <p><span>{!! $chargebacks_date !!}</span></p>
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
              <p> <strong>Amount</strong></p>
            </td>
            <td>
              <p> <span>{!! $amount !!} {!! $currency !!}</span></p>
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <p>In order to resolve this dispute, we require you to submit the card holder’s KYC documentation within 72 hours
        of receiving this notification by clicking on the button below:
      </p>
      <a href="{!! $url !!}" target="_blank" style="font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
        box-sizing: border-box;text-decoration: none;background-color: #F44336;padding: 15px 30px;border-radius: 30px;line-height: 60px;
        color: #FFF;
        font-weight: bold;
        border: 3px solid #fff;">Please Click Here
      </a>
      <p style="margin-top: 10px">Please note that failure to submit or provide non-readable documentation within the
        given time frame will result in the transaction being cancelled, and your PAYMENTS ACCOUNT will be charged with
        the above amount. This will be attributed to
        the card's issuing bank. As a reference, the following documents are indicative but not limited to, what you can
        submit:</p>
      <br>
      <p>
        - Receipts <br />
        - Invoices <br />
        - Registration forms <br />
        - Signed transaction receipts <br />
        - Delivery notes <br />
        - Customer contact information <br />
        - Identity card copy <br />
        - Copy of the card used for the transaction (only the front side, intermediate digits of the card masked)<br />
        - Communication exchanged with the customer
      </p>
  </tr>
</table>
<br>
<p style="font-weight: bold; line-height: 16px;">
  <small>
    <span>Kindly upload the required documents for the above Chargeback transaction as requested by the acquiring
      bank.</span><br>
    1) Proof of transaction (Bank confirmation email, transaction details from the statement, etc.)<br>
    2) KYC details like proof of ID, Address and any other details deemed relevant.
  </small>
</p>
@endcomponent