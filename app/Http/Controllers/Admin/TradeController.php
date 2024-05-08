<?php

namespace App\Http\Controllers\Admin;

use URL;
use Input;
use View;
use File;
use Session;
use Redirect;
use Validator;
use App\TradeDeposit;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class TradeController extends AdminController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tradeDeposit = new TradeDeposit;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 10;
        }

        $data = $this->tradeDeposit->getDataForAdmin($input, $noList);

        return view('admin.trade.index', compact('data'));
    }

    public function show($id)
    {
        $data = $this->tradeDeposit->findData($id);
        if ($data->user) {
            return view($this->moduleTitleP . '.show', compact('data'));
        }
        return  back()->with('warning', 'There is no user account with this ticket !');
    }
}
