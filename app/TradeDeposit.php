<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeDeposit extends Model
{
    use SoftDeletes;

    protected $table = 'trade_deposits';
    protected $guarded = array();

    // Deposit Type = 1 fiat, 2 crypto

    public function storeData($input)
    {
        return static::create($input);
    }

    public function getDataFiat($input, $noList)
    {
        if (\Auth::user()->main_user_id != '0')
            $userID = \Auth::user()->main_user_id;
        else
            $userID = \Auth::user()->id;

        return static::orderBy('created_at', 'desc')->where('deposit_type','1')->where('user_id', $userID)->paginate($noList);
    }

    public function getDataCrypto($input, $noList)
    {
        if (\Auth::user()->main_user_id != '0')
            $userID = \Auth::user()->main_user_id;
        else
            $userID = \Auth::user()->id;

        return static::orderBy('created_at', 'desc')->where('deposit_type','2')->where('user_id', $userID)->paginate($noList);
    }

    public function getDataForAdmin($input, $noList)
    {
        return static::orderBy('created_at', 'desc')->paginate($noList);
    }

    public function findData($id)
    {
        return static::find($id);
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}