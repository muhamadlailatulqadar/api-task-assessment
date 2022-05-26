<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Imports\UpdateUsersImport;
use App\Imports\UsersImport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class UploadFile extends Controller
{
    public function upload(Request $request)
    {

        try {
            $file = $request->file('uploaded_file');
            Excel::import(new UsersImport, $file );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There is error in file or duplicated data'
            ], 201);


        }

    return response()->json([
        'message' => 'Successfully created user!'
    ], 201);

    }
    public function uploadupdate(Request $request)
    {

        try {
            $file = $request->file('uploaded_file');
            Excel::import(new UpdateUsersImport, $file );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There is error in file or duplicated data'
            ], 201);
        }
        return response()->json([
            'message' => 'Successfully Updated

             user!'
        ], 201);

    }

}
