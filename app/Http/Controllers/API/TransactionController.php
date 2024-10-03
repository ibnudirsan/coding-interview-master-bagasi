<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'product_id'    =>'required|exists:products,id',
                'voucher_code'  =>'nullable|exists:vouchers,code',
            ]);

            if($validator->fails()){
                $resultError = [
                    'code'  =>  400,
                    'error'     =>  true,
                    'data'      =>  [
                        'message'   =>'Validation error.',
                        'errors'    =>$validator->errors(),
                    ]
                ];
                    return response()->json($resultError,400);
            }

            $product = Product::where('id', $request->product_id)->first();
            if($request->has('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher->expires_at < Carbon::now()) {
                    $resultError = [
                        'code'  =>  400,
                        'error'     =>  true,
                        'data'      =>  [
                            'message'   =>'Voucher Expired.',
                        ]
                    ];
                        return response()->json($resultError,400);

                } else if($voucher->status == 'inactive') {
                    $resultError = [
                        'code'  =>  400,
                        'error'     =>  true,
                        'data'      =>  [
                            'message'   =>'Voucher Inactive.',
                        ]
                    ];
                        return response()->json($resultError,400);
                }
            }
                Transaction::create([
                    'user_id'           =>  $request->user()->id,
                    'product_id'        =>  $request->product_id,
                    'voucher_id'        =>  $request->has('voucher_code') ? $voucher->id : null,
                    'total_price'       =>  $product->price,
                    'discount_amount'   =>  $request->has('voucher_code') ? $voucher->discount : 0,
                    'total_pay'         =>  $request->has('voucher_code') ? $product->price - $voucher->discount : $product->price,
                ]);
    
                $result = [
                    'code'  =>  200,
                    'error' =>  false,
                    'data'  =>  [
                        'message'   =>'Transaction created successfully.'
                    ]
                ];
                    return response()->json($result,200);

        } catch (\Throwable $th) {
            $resultError = [
                'code'  =>  500,
                'error'     =>  true,
                'data'      =>  [
                    'message'   =>'Internal server error.',
                ]
            ];
                return response()->json($resultError,500);
        }
    }
}
