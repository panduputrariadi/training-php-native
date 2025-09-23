<?php

namespace PanduputragmailCom\PhpNative\Controller;

use PanduputragmailCom\PhpNative\lib\BodyRequest;
use PanduputragmailCom\PhpNative\lib\Validator;
use PanduputragmailCom\PhpNative\Model\DummyData;
use PanduputragmailCom\PhpNative\model\queries\DummyDataQueries;

class DummyDataController
{
    public function getDummyDataUsingQueryBuilder(): array
    {
        $data = (new DummyDataQueries(new DummyData()))->getAllDataUsingQueryBuilder();

        if (empty($data)) {
            return [
                'status' => 200,
                'message' => 'No Data Available',
                'data' => []
            ];
        }

        return [
            'status' => 200,
            'message' => 'Success Retrieve Data',
            'data' => $data
        ];
    }

    public function storeWithQueryBuilder(): array
    {
        $data = BodyRequest::bodyData();

        $validator = new Validator($data, [
            'name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => 'Validation failed',
                'data' => ['errors' => $validator->messages()]
            ];
        }

        $dummyData = new DummyData();
        $response = (new DummyDataQueries($dummyData))->storeDataWithQueryBuilder($data);

        return [
            'status' => 201,
            'message' => 'Success Store Data',
            'data' => $response
        ];
    }

    public function storeWithQueryBuilderNew(): array
    {
        $data = BodyRequest::bodyData();

        $validator = new Validator($data, [
            'name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => 'Validation failed',
                'data' => ['errors' => $validator->messages()]
            ];
        }

        $fillable = (new DummyData())->getFillable();
        $invalid = array_diff(array_keys($data), $fillable);

        if (!empty($invalid)) {
            return [
                'status' => 400,
                'message' => 'Request contains fields that are not allowed.',
                'data' => ['invalid_fields' => array_values($invalid)]
            ];
        }

        $filteredData = array_intersect_key($data, array_flip($fillable));

        $response = (new DummyDataQueries(new DummyData()))->storeDataWithQueryBuilder($filteredData);

        return [
            'status' => 201,
            'message' => 'Success Store Data',
            'data' => $response
        ];
    }
}