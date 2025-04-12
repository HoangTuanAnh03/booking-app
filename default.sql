--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uuid`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('05b35154-d7bd-45ca-bb17-666d093c8324', 'Admin', 'admin@gmail.com', '+84987654321', '2025-04-09 12:07:50', '$2y$12$nGB6TXDB5bTNRv3X5Rgv8exDXc.4uBQJA7bmSYeljH/oH75TQMWrG', 'admin', NULL, '2025-04-10 05:02:10', '2025-04-10 05:02:10'),
('2adacea2-7414-4ed3-9bac-4632f29cca5c', 'OWNER 1', 'owner1@gmail.com', '+84987654321', '2025-04-09 12:07:50', '$2y$12$43fbpisJuV4zlaiPBPjmjuAOxg2Z4fka92OBj12MJFTC.kvSAoH.W', 'owner', NULL, '2025-04-10 05:02:33', '2025-04-10 05:02:33'),
('5c6fd6fd-4391-4ff9-bc97-535a202fe94b', 'User 1', 'user1@gmail.com', '+84987654321', '2025-04-09 12:07:50', '$2y$12$x.4xg4ciAdHzVL4IfY3BQuLR3WSYI4pVduxMEvIAd/DSNOLkw3SvG', 'user', NULL, '2025-04-10 05:01:23', '2025-04-10 05:01:23'),
('6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe', 'OWNER 2', 'owner2@gmail.com', '+84987654321', '2025-04-09 12:07:50', '$2y$12$2OggiY1pQdJqoDZxnManYuNSe.RBblg8BR5Y4/GtfNFd.RkAhK18C', 'owner', NULL, '2025-04-10 05:02:50', '2025-04-10 05:02:50'),
('a721ac9c-c392-4c97-8a01-37e4c803b9ac', 'User 2', 'user2@gmail.com', '+84987654321', '2025-04-09 12:07:50', '$2y$12$2uzj9fcMmhvlIdxubx0K4e/dGvaiL4LJ4zEMAU7FzQC7PjQWnrO2K', 'user', NULL, '2025-04-10 05:07:39', '2025-04-10 05:07:39');

--
-- Dumping data for table `sport_types`
--

INSERT INTO `sport_types` (`sport_type_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cầu Lông', 'Môn thể thao cầu lông', '2025-04-10 06:46:46', '2025-04-10 06:46:46'),
(2, 'Bóng Đá', 'Môn thể thao đồng đội phổ biến nhất thế giới', '2025-04-10 06:47:31', '2025-04-10 06:47:31'),
(3, 'Bóng Rổ', 'Môn thể thao thi đấu trên sân với bóng và rổ', '2025-04-10 06:47:44', '2025-04-10 06:47:44'),
(4, 'Bóng Chuyền', 'Môn thể thao đánh bóng qua lưới bằng tay', '2025-04-10 06:47:52', '2025-04-10 06:47:52'),
(5, 'Tenis', 'Môn thể thao đánh bóng qua lưới bằng vợt', '2025-04-10 06:48:25', '2025-04-10 06:48:25'),
(6, 'Bóng Bàn', 'Môn thể thao đánh bóng qua lưới trên bàn nhỏ bằng vợt nhỏ', '2025-04-10 06:53:45', '2025-04-10 06:53:45'),
(7, 'Pickleball', 'Môn thể thao kết hợp giữa tennis, bóng bàn và cầu lông, chơi với vợt và bóng nhựa', '2025-04-10 06:53:51', '2025-04-10 06:53:51');

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`venue_id`, `owner_id`, `name`, `address`, `longitude`, `latitude`, `coordinates`, `status`, `created_at`, `updated_at`) VALUES
('7d465e0a-ce6b-4bf5-9327-228de8b96302', '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe', 'Venue 2 của Owner 2', 'HCM', 123.12412000, 63.11230000, 0x000000000101000000e4310395f1c75e409fabadd85f8e4f40, 'locked', '2025-04-10 19:36:01', '2025-04-10 19:36:01'),
('8aa84f24-773d-4e16-a40d-cc449abb4151', '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe', 'Venue 1 của Owner 2', 'Hà Nội', 123.12412000, 63.11230000, 0x000000000101000000e4310395f1c75e409fabadd85f8e4f40, 'locked', '2025-04-10 19:36:08', '2025-04-10 19:36:08'),
('ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93', '2adacea2-7414-4ed3-9bac-4632f29cca5c', 'Venue 2 của Owner 1', 'HCM', 123.12412000, 63.11230000, 0x000000000101000000e4310395f1c75e409fabadd85f8e4f40, 'locked', '2025-04-10 19:35:48', '2025-04-10 19:35:48'),
('d2d1ec4a-7b73-4740-815a-87a4d6b9146f', '2adacea2-7414-4ed3-9bac-4632f29cca5c', 'Venue 1 của Owner 1', 'Hà nội', 123.12412000, 63.11230000, 0x000000000101000000e4310395f1c75e409fabadd85f8e4f40, 'locked', '2025-04-10 19:24:46', '2025-04-10 19:24:46');

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `venue_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f', '5c6fd6fd-4391-4ff9-bc97-535a202fe94b', 5, 'Great venue and friendly staff!', '2025-04-10 19:45:46', '2025-04-10 19:45:46'),
(2, 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93', '5c6fd6fd-4391-4ff9-bc97-535a202fe94b', 4, 'Great venue and friendly staff!', '2025-04-10 19:47:07', '2025-04-10 19:47:07'),
(3, '8aa84f24-773d-4e16-a40d-cc449abb4151', '5c6fd6fd-4391-4ff9-bc97-535a202fe94b', 3, 'Great venue and friendly staff!', '2025-04-10 19:47:18', '2025-04-10 19:47:18'),
(4, '7d465e0a-ce6b-4bf5-9327-228de8b96302', '5c6fd6fd-4391-4ff9-bc97-535a202fe94b', 2, 'Great venue and friendly staff!', '2025-04-10 19:47:36', '2025-04-10 19:47:36'),
(5, '7d465e0a-ce6b-4bf5-9327-228de8b96302', 'a721ac9c-c392-4c97-8a01-37e4c803b9ac', 4, 'Great venue and friendly staff!', '2025-04-10 19:48:20', '2025-04-10 19:48:20'),
(6, '8aa84f24-773d-4e16-a40d-cc449abb4151', 'a721ac9c-c392-4c97-8a01-37e4c803b9ac', 3, 'Great venue and friendly staff!', '2025-04-10 19:48:29', '2025-04-10 19:48:29'),
(7, 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93', 'a721ac9c-c392-4c97-8a01-37e4c803b9ac', 1, 'Great venue and friendly staff!', '2025-04-10 19:48:39', '2025-04-10 19:48:39'),
(8, 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f', 'a721ac9c-c392-4c97-8a01-37e4c803b9ac', 2, 'Great venue and friendly staff!', '2025-04-10 19:48:54', '2025-04-10 19:48:54');

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`field_id`, `venue_id`, `sport_type_id`, `field_name`, `default_price`, `is_active`, `created_at`, `updated_at`) VALUES
('d0ee0d1c-7aeb-42a3-835c-dbe7b78bbd94', 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f', 7, 'PickerBall', 200000.00, 1, '2025-04-10 19:56:16', '2025-04-10 19:56:16'),
('dcb38c24-adb0-4c57-9551-ed4413becf4e', 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f', 1, 'Cầu Lông', 150000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4h', 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93', 7, 'PickerBall', 200000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4k', 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93', 1, 'Cầu Lông', 150000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', '8aa84f24-773d-4e16-a40d-cc449abb4151', 7, 'PickerBall', 200000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', '8aa84f24-773d-4e16-a40d-cc449abb4151', 1, 'Cầu Lông', 150000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4n', '7d465e0a-ce6b-4bf5-9327-228de8b96302', 7, 'PickerBall', 200000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55'),
('dcb38c24-adb0-4c57-9551-ed4413becf4p', '7d465e0a-ce6b-4bf5-9327-228de8b96302', 1, 'Cầu Lông', 150000.00, 1, '2025-04-10 19:54:55', '2025-04-10 19:54:55');


--
-- Dumping data for table `field_opening_hours`
--

INSERT INTO field_opening_hours (field_id, day_of_week, opening_time, closing_time, created_at, updated_at)
VALUES
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'monday',    '06:00:00', '22:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'tuesday',   '07:00:00', '21:30:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'wednesday', '08:00:00', '22:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'thursday',  '07:30:00', '21:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'friday',    '06:30:00', '23:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'saturday',  '07:00:00', '23:30:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4o', 'sunday',    '08:00:00', '20:00:00', NOW(), NOW()),

('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'monday',    '06:00:00', '22:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'tuesday',   '07:00:00', '21:30:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'wednesday', '08:00:00', '22:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'thursday',  '07:30:00', '21:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'friday',    '06:30:00', '23:00:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'saturday',  '07:00:00', '23:30:00', NOW(), NOW()),
('dcb38c24-adb0-4c57-9551-ed4413becf4m', 'sunday',    '08:00:00', '20:00:00', NOW(), NOW());
