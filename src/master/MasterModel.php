<?php

namespace PanduputragmailCom\PhpNative\master;

abstract class MasterModel {
    protected array $fillable = [];

    /**
     * Filter only field in $fillable
     * @param array $data
     * @return array
     * @throws \Exception if there is unknow column in fillable
     */
    public function onlyFillable(array $data): array
    {
        $invalid = array_diff(array_keys($data), $this->fillable);

        if (!empty($invalid)) {
            throw new \Exception("Field does not exist: " . implode(', ', $invalid));
        }

        $filtered = array_intersect_key($data, array_flip($this->fillable));

        if (empty($filtered)) {
            throw new \Exception("There are no valid fields.");
        }

        return $filtered;
    }

    /**
     * only needs reads getter
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }

    /**
     * Optional: if want override table in model
     */
    abstract public function getTable(): string;
}