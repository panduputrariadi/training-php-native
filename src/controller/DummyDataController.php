<?php

namespace PanduputragmailCom\PhpNative\Controller;

use PanduputragmailCom\PhpNative\lib\Response;
use PanduputragmailCom\PhpNative\lib\Validator;
use PanduputragmailCom\PhpNative\Model\DummyData;

class DummyDataController
{
    public function GetDataDummy(){
        $dummyData = (new DummyData)->findAll();
        if(empty($dummyData)){
            return Response::success([], 'No Data Available');
        }

        return Response::success($dummyData, 'Success Retrive Data');
    }
    public function store(){
        $data = $_POST;

        // $errors = Validator($data, [
        //     'name'  => 'required|string|max:50',
        //     'email' => 'required|email',
        // ]);

        // if (!empty($errors)) {
        //     return Response::badRequest(['errors' => $errors], 'Validation failed');
        // }


        $dummyData = new DummyData();
        $response = $dummyData->storeData($data);

        return Response::created($response, 'Success Store Data');
    }
}