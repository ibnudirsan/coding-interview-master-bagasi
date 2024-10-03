<?php

namespace App\Traits;

trait handleHttp
{
    protected function HandleCode($statusCode)
    {
        if ($statusCode == 400) {
            $response = [
                'httpcode'  =>400,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Bad Request',
                ],
            ];
                return response()->json($response,400);
        } elseif ($statusCode == 401) {
            $response = [
                'httpcode'  =>401,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Unauthorized Access',
                ],
            ];
                return response()->json($response,401);
        } elseif ($statusCode == 403) {
            $response = [
                'httpcode'  =>403,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Access Forbidden',
                ],
            ];
                return response()->json($response,403);
        } elseif ($statusCode == 404) {
            $response = [
                'httpcode'  =>404,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Check Incorrect URL',
                ],
            ];
                return response()->json($response,404);
        } elseif ($statusCode == 405) {
            $response = [
                'httpcode'  =>405,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Check Incorrect Method',
                ],
            ];
                return response()->json($response,405);
        } elseif ($statusCode == 500) {
            $response = [
                'httpcode'  =>500,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Internal Server Error',
                ],
            ];
                return response()->json($response,500);
        }
    }
}