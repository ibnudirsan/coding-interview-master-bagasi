<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $product = Product::select('id','name','price','description')->latest()->get();
            if(!$product->count()){
                $resultError = [
                    'code'      =>  404,
                    'error'     =>  true,
                    'data'      =>  [
                        'message'   =>'Product not found.',
                    ],
                ];
                    return response()->json($resultError,404);
            }
                $result = [
                    'code'      =>  200,
                    'error'     =>  false,
                    'data'      =>  [
                        'products'  =>  $product,
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name'          =>  'required|string|max:100',
                'price'         =>  'required|numeric',
                'description'   =>  'required|string|max:250',
            ]);

            if($validator->fails()){
                $resultError = [
                    'code'      =>  400,
                    'error'     =>  true,
                    'data'      =>  [
                        'message'   =>'Validation error.',
                        'errors'    =>$validator->errors(),
                    ],
                ];
                    return response()->json($resultError,400);
            }
                Product::create([
                    'name'          =>  $request->name,
                    'price'         =>  $request->price,
                    'description'   =>  $request->description,
                ]);

                $result = [
                    'code'      =>  200,
                    'error'     =>  false,
                    'data'      =>  [
                        'message'   =>'Product created successfully.',
                    ],
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
            $product = Product::select('id','name','price','description')->find($id);
            if(!$product){
                $resultError = [
                    'code'  =>  404,
                    'error' =>  true,
                    'data'  =>  [
                        'message'   =>'Product not found.',
                    ],
                ];
                    return response()->json($resultError,404);
            }
                $result = [
                    'code'  =>  200,
                    'error' =>  false,
                    'data'  =>  [
                        'product' =>  $product,
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'          =>  'required|string|max:100',
            'price'         =>  'required|numeric',
            'description'   =>  'required|string|max:250',
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
            $product = Product::find($id);
            if(!$product){
                $resultError = [
                    'code'  =>  404,
                    'error' =>  true,
                    'data'  =>  [
                        'message' =>'Product not found.',
                    ],
                ];
                    return response()->json($resultError,404);
            }
                $product->update([
                    'name'          =>  $request->name,
                    'price'         =>  $request->price,
                    'description'   =>  $request->description,
                ]);
                $result = [
                    'code'  => 200,
                    'error' =>  false,
                    'data'  =>  [
                        'message' =>'Product updated successfully.',
                    ],
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

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if(!$product){
                $resultError = [
                    'code'  =>404,
                    'error' =>true,
                    'data'  =>[
                        'message' =>'Product not found.',
                    ],
                ];
                    return response()->json($resultError,404);
            }
                $product->delete();
                $result = [
                    'code'  =>  200,
                    'error'     =>  false,
                    'data'      =>  [
                        'message'   =>'Product deleted successfully.',
                    ],
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
