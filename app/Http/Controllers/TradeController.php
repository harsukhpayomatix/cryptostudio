<?php

namespace App\Http\Controllers;

use App\Events\AdminNotification;
use URL;
use Auth;
use View;
use File;
use Mail;
use Input;
use Session;
use Redirect;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TradeDeposit;

class TradeController extends HomeController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tradeDeposit = new TradeDeposit;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return view('front.trade.index');
    }

    public function indexFiat(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }

        $data = $this->tradeDeposit->getDataFiat($input, $noList);

        return view('front.trade.fiat', compact('data'));
    }

    public function indexCrypto(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }

        $data = $this->tradeDeposit->getDataCrypto($input, $noList);

        return view('front.trade.crypot', compact('data'));
    }

    public function show(Request $request, $id)
    {
        $data = $this->tradeDeposit->findData($id);

        return view('front.trade.show', compact('data'));
    }

    public function depositFiat(Request $request)
    {
        return view('front.trade.depositFiat');
    }

    public function depositFiatStore(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));

        $this->validate(
            $request,
            [
                'account_id' => 'required',
                'currency_type' => 'required',
                'amount' => 'required|numeric',
                'bank_name' => 'required',
                'bank_account_number' => 'required',
                'swift' => 'required',
                'transfer_date' => 'required',
                'phone_number' => 'required|numeric',
                'email' => 'required|string|email|max:255',
                'confirm_by_user' => 'required'
            ],
            [
                'confirm_by_user.required' => 'This conformation is required.'
            ],
        );

        if(empty($input['currency_type']) && !empty($input['currency_type_other'])){
            $input['currency_type'] = $input['currency_type_other'];
        }
        unset($input['currency_type_other']);

        $data = $this->tradeDeposit->storeData($input);

        notificationMsg('success', 'Deposit Successfully!');

        return redirect()->route('trade.fiat.index');
    }
    
    public function depositCrypto(Request $request)
    {
        return view('front.trade.depositCrypto');
    }

    public function depositCryptoStore(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));

        $this->validate(
            $request,
            [
                'account_id' => 'required',
                'currency_type' => 'required',
                'amount' => 'required|numeric',
                'sender_wallet_address' => 'required',
                'transfer_date' => 'required',
                'phone_number' => 'required|numeric',
                'email' => 'required|string|email|max:255',
                'confirm_by_user' => 'required'
            ],
            [
                'confirm_by_user.required' => 'This conformation is required.'
            ],
        );

        if(empty($input['currency_type']) && !empty($input['currency_type_other'])){
            $input['currency_type'] = $input['currency_type_other'];
        }
        unset($input['currency_type_other']);

        $data = $this->tradeDeposit->storeData($input);

        notificationMsg('success', 'Deposit Successfully!');

        return redirect()->route('trade.crypto.index');
    }
}