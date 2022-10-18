<?php

namespace App\Traits;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

trait ApiResponser{

    public static function  set_response($data, $status_code, $status, $details)
    {
        $resData = Response::json(
                [
                    'status'        =>  $status, // 1 / 0
                    'code'          =>  $status_code,
                    'data'          =>  $data,
                    'message'       =>  $details
                ]
        , 200, [], JSON_NUMERIC_CHECK )
        ->header('Content-Type', 'application/json');

        $data = [];

        return $resData;
    }

}
