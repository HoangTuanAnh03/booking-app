<?php

namespace App\Repository\Impl;

use App\Exceptions\ErrorException;
use App\Models\FieldPrice;
use App\Repository\IFieldPriceRepository;
use Illuminate\Support\Facades\DB;

class FieldPriceRepository implements IFieldPriceRepository
{

    public function save($data)
    {
        FieldPrice::create($data);
    }

    /**
     * @throws ErrorException
     */
    public function saveAll($data, $fieldId){
        DB::beginTransaction();
        try {
            FieldPrice::where('field_id', $fieldId)->delete();
            foreach ($data as $day => $slots) {
                foreach ($slots as $slot) {
                    FieldPrice::create([
                        'field_id' => $fieldId,
                        'day_of_week' => $day,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'price' => $slot['price'],
                        'min_rental' => $slot['min_rental'],
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ErrorException("Có lỗi trong quá trình lưu");
        }
    }

    public function get($fieldId, $dayOfWeek){
        $query = FieldPrice::where('field_id', $fieldId);
        if ($dayOfWeek) {
            $dayOfWeek = ucfirst(strtolower($dayOfWeek));
            $query->where('day_of_week', $dayOfWeek);
        }
        return $query->select('field_price_id', 'field_id', 'day_of_week', 'start_time', 'end_time', 'price', 'min_rental')->get()->toArray();
    }
}
