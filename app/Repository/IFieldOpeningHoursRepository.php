<?php

namespace App\Repository;

interface IFieldOpeningHoursRepository
{
    /**
     * Lưu giờ mở cửa cho một sân.
     *
     * @param mixed $fieldOpeningHour Dữ liệu giờ mở cửa cần lưu.
     * @return mixed
     */
    public function save($fieldOpeningHour);

    /**
     * Lưu nhiều giờ mở cửa cho một sân.
     *
     * @param array $fieldOpeningHours Danh sách giờ mở cửa.
     * @param int|string $fieldId ID của sân.
     * @return mixed
     */
    public function saveAll($fieldOpeningHours, $fieldId);

    /**
     * Cập nhật danh sách giờ mở cửa cho một sân.
     *
     * @param array $fieldOpeningHours Danh sách giờ mở cửa mới.
     * @param int|string $fieldId ID của sân.
     * @return mixed
     */
    public function update($fieldOpeningHours, $fieldId);

    /**
     * Lấy danh sách giờ mở cửa theo ID sân.
     *
     * @param int|string $fieldId ID của sân.
     * @return array Danh sách giờ mở cửa.
     */
    public function getByFieldId($fieldId): array;

    public function getByFieldIdAndDayOfWeek($fieldId, $dayOfWeek);
}
