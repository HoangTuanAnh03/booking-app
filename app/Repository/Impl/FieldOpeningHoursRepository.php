<?php

namespace App\Repository\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\RecordExistsException;
use App\Models\FieldOpeningHours;
use App\Repository\IFieldOpeningHoursRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class FieldOpeningHoursRepository implements IFieldOpeningHoursRepository
{
    /**
     * @param $fieldOpeningHour
     * @return mixed
     */
    public function save($fieldOpeningHour): FieldOpeningHours{
        return FieldOpeningHours::create($fieldOpeningHour);
    }

    /**
     * @throws ErrorException|RecordExistsException
     */
    public function saveAll($fieldOpeningHours, $fieldId)
    {
        FieldOpeningHours::where('field_id', $fieldId)->delete();
        try{
            return DB::transaction(function () use ($fieldOpeningHours, $fieldId) {
                $result = [];
                foreach ($fieldOpeningHours['opening_hours'] as $hour) {
                    $record = FieldOpeningHours::create([
                        'field_id' => $fieldId,
                        'day_of_week' => $hour['day_of_week'],
                        'opening_time' => $hour['opening_time'],
                        'closing_time' => $hour['closing_time'],
                    ]);
                    $result[] = $record;
                }
                return $result;
            });
        } catch (QueryException $e) {
            throw new RecordExistsException("Ngày này đã tồn tại với sân này.");
        } catch (\Exception $e) {
            throw new ErrorException("Lỗi không xác định");
        }
    }

    /**
     * @throws ErrorException
     * @throws RecordExistsException
     */
    public function update($fieldOpeningHours, $fieldId)
    {
        return $this->saveAll($fieldOpeningHours, $fieldId);
    }

    public function getByFieldId($fieldId): array{
        return FieldOpeningHours::Where('field_id', $fieldId)->get()->toArray();
    }
}
