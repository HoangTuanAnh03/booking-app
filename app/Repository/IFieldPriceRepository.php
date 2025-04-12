<?php

namespace App\Repository;

interface IFieldPriceRepository
{
//    public function save($data);

    public function saveAll($data, $fieldId);

    public function get($fieldId, $dayOfWeek);

}
