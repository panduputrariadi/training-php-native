<?php

namespace PanduputragmailCom\PhpNative\Model;

use PanduputragmailCom\PhpNative\master\MasterModel;

class DummyData extends MasterModel{
    protected $table = 'dummy_data';
    protected array $fillable = ['name'];

    public function getTable(): string
    {
        return $this->table;
    }
}