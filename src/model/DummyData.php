<?php

namespace PanduputragmailCom\PhpNative\Model;

class DummyData{
    protected $table = 'dummy_data';
    protected $fillable = ['name'];

    public function getTable() {
        return $this->table;
    }

    public function getFillable() {
        return $this->fillable;
    }
}