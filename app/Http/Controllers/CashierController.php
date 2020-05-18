<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meal;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Caisse;
use Validator;


class CashierController extends Controller
{
    public function badis()
    {
        $products = Meal::all();
 
        return response()->json(  $products );
    }


    public function login(Request $request)
    {
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        
 
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('MySecret')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }


/* public $successStatus = 200;

public function login() { 
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
    } 
    else { 
        return response()->json(['error'=>'Unauthorised'], 401); 
    } 
}
 */
       
public function addOrderForm(Request $request) 
{
 


    $data =  Validator::make($request->all(),[
        'caisse_id' => 'required',
        'orderType' => 'required',
        'priceOrder' => '',
        ]);

    if($data->fails()){
        return response()->json([
            "error" => 'validation_error',
            "message" => $data->errors(),
        ], 422);
    }

    $order = new Order;
    $caisse = Caisse::find($request->caisse_id);
    $order->caisse()->associate($caisse);
    $order->taux = 213;
    $order->nOrder = 131;
    $order->orderType = $request->orderType;
    $order->orderStatus = "hada win tajouta";
    $order->priceOrder = $request->priceOrder;
    $order->paymentStatus ="fdfdsfdsfsd";
    $order->paymentMethod ="fdfdsfdsfsd";
    $order->save();

 //   dd($order);

    if ($order) {
        return response()->json('done'
        //     [
        //     'success' => true,
        //     'data' => $product->toArray()
        // ]
    );
    }
    else{
        return response()->json('sorry'
        //     [
        //     'success' => false,
        //     'message' => 'Product could not be added'
        // ]
        , 500);
    }




}


public function details()
{
    return response()->json(['user' => auth()->user()], 200);
}

}
