<?php

namespace PanduputragmailCom\PhpNative\Controller;

use PanduputragmailCom\PhpNative\lib\BodyRequest;
use PanduputragmailCom\PhpNative\lib\Response;
use PanduputragmailCom\PhpNative\lib\Validator;
use PanduputragmailCom\PhpNative\Model\DummyData;
use PanduputragmailCom\PhpNative\model\queries\DummyDataQueries;

class DummyDataController
{
    public function GetDataDummy(){
        $dummyData = (new DummyDataQueries(new DummyData()))->getAllData();
        if(empty($dummyData)){
            return Response::success([], 'No Data Available');
        }

        return Response::success($dummyData, 'Success Retrive Data');
    }
    public function store(){
        $data = BodyRequest::bodyData();       

        $validator = new Validator($data, [
            'name'  => 'required|string|max:50',            
        ]);

        if ($validator->fails()) {
            return Response::badRequest(['errors' => $validator->messages()], 'Validation failed');
        }

        $dummyData = new DummyData();
        // $response = $dummyData->storeData($data);
        $response = (new DummyDataQueries($dummyData))->storeData($data);

        return Response::created($response, 'Success Store Data');
    }

    public function getDummyDataUsingQueryBuilder(): array {
        $data = (new DummyDataQueries(new DummyData()))->getAllDataUsingQueryBuilder();
        if(empty($data)){
            return Response::success([], 'No Data Available');
        }

        return Response::success($data, 'Success Retrive Data');
    }
}