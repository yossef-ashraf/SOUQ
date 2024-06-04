<?php

namespace App\Http\Controllers\Web;

use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function show()
    {
     $UserWallet= UserWallet::where('user_id', auth()->User()->id)->first();
     return $this->apiResponse(200,__('lang.Successfully'),null,$UserWallet);
    }
    public function showOne()
    {
     $UserWallet= UserWallet::where('user_id', auth()->User()->id)->with('wallet_histories')->first();
     return $this->apiResponse(200,__('lang.Successfully'),null,$UserWallet);
    }
    public function update(Request $request)
    {
           $validator = Validator::make($request->all(), [
            'wallet' => 'required',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400,  __('lang.validationError'), $validator->errors());
        }
        $UserWallet= UserWallet::where('user_id', auth()->User()->id)->first();
        $UserWallet->update([
            'wallet' => $UserWallet->wallet + $request->wallet,
        ]);
        WalletHistory::create([
            'user_wallet_id' => $UserWallet->id,
            'value' => $request->wallet,
        ]);
        return $this->apiResponse(200,__('lang.Successfully'),null,$UserWallet);
    }

}
