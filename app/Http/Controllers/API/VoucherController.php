<?php

namespace App\Http\Controllers\API;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'          => 'required|string|unique:vouchers,code',
            'discount'      => 'required|numeric',
            'starts_at'     => 'required|date_format:Y-m-d H:i:s',
            'expires_at'    => 'required|date_format:Y-m-d H:i:s',
        ]);

        if($validator->fails()){
            $resultError = [
                'httpcode'  =>  400,
                'error'     =>  true,
                'data'      =>  [
                    'message'   =>'Validation error.',
                    'errors'    =>$validator->errors(),
                ],
            ];
                return response()->json($resultError,400);
        }

        try {
            
            $voucher = Voucher::create([
                'code'          =>  $request->code,
                'discount'      =>  $request->discount,
                'starts_at'     =>  $request->starts_at,
                'expires_at'    =>  $request->expires_at,
            ]);

            $result = [
                'httpcode'  =>  200,
                'error'     =>  false,
                'data'      =>  [
                    'message'   =>'Voucher created successfully.',
                    'code'      =>  $voucher->code,
                    'discount'  =>  $voucher->discount,
                ]
            ];
                return response()->json($result,200);
        } catch (\Throwable $th) {
            $resultError = [
                'code'      =>  500,
                'error'     =>  true,
                'data'      =>  [
                    'message'   =>'Internal server error.',
                ],
            ];
                return response()->json($resultError,500);
        }
    }
}
