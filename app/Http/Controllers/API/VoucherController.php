<?php

namespace App\Http\Controllers\API;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $voucher = Voucher::select('id','code','discount','starts_at','expires_at')->latest()->get();
        if($voucher->count() < 1){
            $resultError = [
                'code'  =>  404,
                'error' =>  false,
                'data'  =>  [
                    'message'   =>'No vouchers found.',
                    'vouchers'  =>$voucher,
                ]
            ];
                return response()->json($resultError,404);
        }

        $result = [
            'httpcode'  =>  200,
            'error'     =>  false,
            'data'      =>  [
                'message'   =>'Vouchers retrieved successfully.',
                'vouchers'  =>$voucher,
            ]
        ];
            return response()->json($result,200);
    }

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
                'code'  =>  400,
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
                'code'  =>  200,
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

    public function find($id)
    {
        try {
            $voucher = Voucher::select('id','code','discount','starts_at','expires_at')->find($id);
            if(!$voucher){
                $resultError = [
                    'code'  =>  404,
                    'error' =>  true,
                    'data'  =>  [
                        'message'   =>'Voucher not found.',
                    ]
                ];
                    return response()->json($resultError,404);
            }
    
            $result = [
                'httpcode'  =>  200,
                'error'     =>  false,
                'message'   =>'Voucher retrieved successfully.',
                'data'      =>  [
                    'voucher'   =>  $voucher,
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'code'          =>  'required|string|unique:vouchers,code,' . $id,
            'discount'      =>  'required|numeric',
            'starts_at'     =>  'required|date_format:Y-m-d H:i:s',
            'expires_at'    =>  'required|date_format:Y-m-d H:i:s',
        ]);

        if($validator->fails()){
            $resultError = [
                'code'  =>  400,
                'error' =>  true,
                'data'  =>  [
                    'message' =>'Validation error.',
                    'errors'  =>$validator->errors(),
                ],
            ];
                return response()->json($resultError,400);
        }

        try {
            $voucher = Voucher::find($id);
            if(!$voucher){
                $resultError = [
                    'code'  =>  404,
                    'error' =>  true,
                    'data'  =>  [
                        'message'   =>'Voucher not found.',
                    ]
                ];
                    return response()->json($resultError,404);
            }

            $voucher->update([
                'code'          =>  $request->code,
                'discount'      =>  $request->discount,
                'starts_at'     =>  $request->starts_at,
                'expires_at'    =>  $request->expires_at,
            ]);

            $result = [
                'code'  =>  200,
                'error'     =>  false,
                'data'      =>  [
                    'message'   =>'Voucher updated successfully.',
                    'code'      =>  $voucher->code,
                    'discount'  =>  $voucher->discount,
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

    public function destroy($id)
    {
        try {
            $voucher = Voucher::find($id);
            if(!$voucher){
                $resultError = [
                    'code'  =>  404,
                    'error' =>  true,
                    'data'  =>  [
                        'message'   =>'Voucher not found.',
                    ]
                ];
                    return response()->json($resultError,404);
            }
                $voucher->delete();
                $result = [
                    'code'  =>  200,
                    'error' =>  false,
                    'data'  =>  [
                        'message'   =>'Voucher deleted successfully.',
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
