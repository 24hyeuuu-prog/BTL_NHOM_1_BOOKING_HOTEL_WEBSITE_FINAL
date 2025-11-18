-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:8889
-- Thời gian đã tạo: Th10 17, 2025 lúc 05:31 AM
-- Phiên bản máy phục vụ: 8.0.40
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lavalle_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `hotel_id` int NOT NULL,
  `room_id` int NOT NULL,
  `room_numbers` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_quantity` int DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_guests` int NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Chưa xác nhận','Đã xác nhận','Đã hoàn thành','Đã hủy') COLLATE utf8mb4_general_ci DEFAULT 'Chưa xác nhận',
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `hotel_id`, `room_id`, `room_numbers`, `room_quantity`, `check_in_date`, `check_out_date`, `num_guests`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 4, 4, 11, 'D102', 1, '2025-11-14', '2025-11-15', 1, 1600000.00, 'Chưa xác nhận', NULL, '2025-11-14 03:52:04', '2025-11-14 03:52:04'),
(6, 4, 4, 10, 'D101', 1, '2025-11-14', '2025-11-16', 4, 2200000.00, 'Đã xác nhận', NULL, '2025-11-14 05:51:33', '2025-11-14 11:41:17'),
(7, 4, 6, 14, 'F101', 1, '2025-11-14', '2025-11-17', 10, 2550000.00, 'Chưa xác nhận', NULL, '2025-11-14 06:07:35', '2025-11-14 13:47:05'),
(8, 4, 4, 10, 'D101,D102', 2, '2025-11-20', '2025-11-21', 4, 2697000.00, 'Đã hủy', 'Không có yêu cầu gì đặc biệt', '2025-11-14 15:42:50', '2025-11-16 17:31:03'),
(9, 4, 1, 1, 'A101', 1, '2025-11-18', '2025-11-19', 1, 800000.00, 'Chưa xác nhận', '', '2025-11-16 17:32:14', '2025-11-16 17:32:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachsan`
--

CREATE TABLE `khachsan` (
  `MaKS` int NOT NULL,
  `Ten` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `hangkhachsan` enum('cao cấp','sang trọng','bình dân') COLLATE utf8mb4_general_ci NOT NULL,
  `diemdg` decimal(2,1) DEFAULT '0.0',
  `xemhang` enum('xuất sắc','tốt','bình thường','kém','rất tệ') COLLATE utf8mb4_general_ci DEFAULT 'bình thường',
  `vitri` text COLLATE utf8mb4_general_ci,
  `khuvuc` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mota` text COLLATE utf8mb4_general_ci,
  `giatri1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `giatri2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `giatri3` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `giatri4` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anhmain` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anh1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anh2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anh3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anh4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `motachitiet` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachsan`
--

INSERT INTO `khachsan` (`MaKS`, `Ten`, `hangkhachsan`, `diemdg`, `xemhang`, `vitri`, `khuvuc`, `mota`, `giatri1`, `giatri2`, `giatri3`, `giatri4`, `anhmain`, `anh1`, `anh2`, `anh3`, `anh4`, `motachitiet`, `price`, `created_at`) VALUES
(1, 'La Siesta Classic', 'cao cấp', 5.0, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.9609033664537!2d105.84846969678952!3d21.034250300000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc090f166ef%3A0xc47de571ccee8f97!2zS2jDoWNoIHPhuqFuIExhIFNpZXN0YQ!5e0!3m2!1svi!2sus!4v1749550050138!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Mang lại sự sang trọng, thoải mái và riêng tư giữa lòng thủ đô Hà Nội tấp nập. Phòng cách Tây Âu mang lại sự đẳng cấp cùng trang nhã.', 'Riêng Tư', 'Thoải Mái', 'Sang Trọng', 'Hợp lý', 'img/La Seita.jpg', 'img/la sei1.jpg', 'img/la sei2.jpg', 'img/la sei3.jpg', 'img/la sei4.jpg', 'WiFi miễn phí, Hồ bơi, Phòng gym, Spa, Nhà hàng, Bãi đỗ xe, Điều hòa không khí, Máy sưởi, Wifi tốc độ cao, Máy pha cà phê, Dịch vụ phòng 24/7', 15000000.00, '2025-11-14 02:37:47'),
(2, 'Hanoi La Siesta Hotel & Spa', 'sang trọng', 2.9, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.9609038509257!2d105.85065837438455!3d21.03425028061646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc090f166ef%3A0xc47de571ccee8f97!2zS2jDoWNoIHPhuqFuIExhIFNpZXN0YQ!5e0!3m2!1svi!2sus!4v1749550098985!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ spa cao cấp và nhà hàng ẩm thực đa dạng.', 'Spa cao cấp', 'Ẩm thực đa dạng', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'img/La Siesta1.jpg', 'img/la siesta1.jpg', 'img/la siesta2.jpg', 'img/la siesta3.jpg', 'img/la siesta4.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao, Xem phim free, Điều hòa nóng lạnh, Phục vụ 3 bữa.', 20000000.00, '2025-11-14 02:37:47'),
(3, 'Hotel Nikko Hanoi', 'sang trọng', 4.7, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.373999200102!2d105.83931867438399!3d21.017716280628814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab8f9af04521%3A0xdccfaebef264a0b1!2sHotel%20du%20Parc%20Hanoi!5e0!3m2!1svi!2sus!4v1749550125241!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/nikko1.jpg', 'img/nikko2.jpg', 'img/nikko3.jpg', 'img/nikko4.jpg', 'img/nikko5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 25000000.00, '2025-11-14 02:37:47'),
(4, 'JW Marriott Hotel Hanoi 2', 'sang trọng', 5.0, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.6414257407946!2d105.7831221!3d21.007006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135acacbb621a31%3A0x6b9d241f84cd960!2sJW%20Marriott%20Hotel%20Hanoi!5e0!3m2!1svi!2s!4v1763116186953!5m2!1svi!2s\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/jw1.jpg', 'img/jw2.jpg', 'img/jw3.jpg', 'img/jw4.jpg', 'img/jw5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 30000000.00, '2025-11-14 02:37:47'),
(5, 'InterContinental Hanoi Landmark72', 'sang trọng', 4.6, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.395986943854!2d105.78172247438401!3d21.016835880629483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab54df748467%3A0xbce19e4662752253!2sInterContinental%20Hanoi%20Landmark72!5e0!3m2!1svi!2sus!4v1749550188352!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với tầm nhìn toàn cảnh thành phố.', 'Tầm nhìn đẹp', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/inter1.jpg', 'img/inter2.jpg', 'img/inter3.jpg', 'img/inter4.jpg', 'img/inter5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 28000000.00, '2025-11-14 02:37:47'),
(6, 'Melia Hanoi', 'sang trọng', 4.8, 'xuất sắc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.208371071103!2d105.84604827438426!3d21.024346980623893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab939165e8d9%3A0xf3952b1fa755bdcc!2sMeli%C3%A1%20Hanoi!5e0!3m2!1svi!2sus!4v1749550214444!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/melia1.jpg', 'img/melia2.jpg', 'img/melia3.jpg', 'img/melia4.jpg', 'img/melia5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 22000000.00, '2025-11-14 02:37:47'),
(7, 'Hilton Hanoi Opera', 'sang trọng', 4.7, 'tốt', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.235274328285!2d105.85534817438418!3d21.023270080624638!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abec09e8c48b%3A0x56aec868a7a3d467!2sKh%C3%A1ch%20s%E1%BA%A1n%20Hilton%20Hanoi%20Opera!5e0!3m2!1svi!2sus!4v1749550238510!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/hilton1.jpg', 'img/hilton2.jpg', 'img/hilton3.jpg', 'img/hilton4.jpg', 'img/hilton5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 24000000.00, '2025-11-14 02:37:47'),
(8, 'Sofitel Legend Metropole Hanoi', 'sang trọng', 4.3, 'tốt', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.18175131539!2d105.85380007438427!3d21.02541248062305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abeb98f8b54d%3A0x90d6982234a65f25!2sSofitel%20Legend%20Metropole%20Hotel!5e0!3m2!1svi!2sus!4v1749550261328!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/sofitel1.jpg', 'img/sofitel2.jpg', 'img/sofitel3.jpg', 'img/sofitel4.jpg', 'img/sofitel5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 35000000.00, '2025-11-14 02:37:47'),
(9, 'Pan Pacific Hanoi Hotel & Spa ', 'sang trọng', 4.2, 'tốt', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.572930750838!2d105.83730767438509!3d21.049767480604828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abea6bc08943%3A0x26fe222a6d057b05!2sPan%20Pacific%20Hanoi!5e0!3m2!1svi!2sus!4v1749550290729!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ spa cao cấp và nhà hàng ẩm thực đa dạng.', 'Spa cao cấp', 'Ẩm thực đa dạng', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'img/pan1.jpg', 'img/pan2.jpg', 'img/pan3.jpg', 'img/pan4.jpg', 'img/pan5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 27000000.00, '2025-11-14 02:37:47'),
(10, 'Grand Mercure Hanoi', 'sang trọng', 4.1, 'tốt', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.1201524351604!2d105.83089437438434!3d21.02787788062121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab30b503f8d7%3A0x9df51330f1236556!2sGrand%20Mercure%20Hanoi!5e0!3m2!1svi!2sus!4v1749550317097!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/grand1.jpg', 'img/grand2.jpg', 'img/grand3.jpg', 'img/grand4.jpg', 'img/grand5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 20000000.00, '2025-11-14 02:37:47'),
(11, 'Hanoi Pearl Hotel ', 'sang trọng', 4.0, 'tốt', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.053826114425!2d105.84775747438445!3d21.030532180619193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab955e103283%3A0x61f637b23642247b!2sHanoi%20Pearl%20Hotel!5e0!3m2!1svi!2sus!4v1749550345485!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn sang trọng với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/pearl1.jpg', 'img/pearl2.jpg', 'img/pearl3.jpg', 'img/pearl4.jpg', 'img/pearl5.jpg', 'Khách sạn sang trọng tại trung tâm Hà Nội với dịch vụ 5 sao.', 23000000.00, '2025-11-14 02:37:47'),
(12, 'Hanoi La Selva Hotel', 'bình dân', 3.9, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0342362939195!2d105.85226847438446!3d21.03131608061864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc03722e303%3A0xff7326b812e7fb9a!2sHanoi%20La%20Selva%20Hotel!5e0!3m2!1svi!2sus!4v1749550367233!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/selva1.jpg', 'img/selva2.jpg', 'img/selva3.jpg', 'img/selva4.jpg', 'img/selva5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 8000000.00, '2025-11-14 02:37:47'),
(13, 'Hanoi Golden Moon Hotel', 'bình dân', 3.8, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.996024266938!2d105.85199537438453!3d21.032845080617502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc05976f9dd%3A0xfbed05dd9685ee3c!2sHanoi%20Golden%20Moon%20Hotel%20%26%20Travels!5e0!3m2!1svi!2sus!4v1749550391913!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/golden1.jpg', 'img/golden2.jpg', 'img/golden3.jpg', 'img/golden4.jpg', 'img/golden5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 7000000.00, '2025-11-14 02:37:47'),
(14, 'Hanoi Old Quarter Hotel', 'bình dân', 3.7, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.032391981!2d105.84779767438445!3d21.031389880618594!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab7a2ffc294b%3A0x90d2fd3cdd24227f!2sHanoi%20Old%20Quarter%20Hotel!5e0!3m2!1svi!2sus!4v1749550436981!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/oldquarter1.jpg', 'img/oldquarter2.jpg', 'img/oldquarter3.jpg', 'img/oldquarter4.jpg', 'img/oldquarter5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 6000000.00, '2025-11-14 02:37:47'),
(15, 'Hanoi Boutique Hotel', 'bình dân', 3.6, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.911665530795!2d105.84568359678956!3d21.0362202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abb8a9fbabbd%3A0x16cc928d14a06f1a!2sHanoi%20Boutique%20Hotel%20%26%20Spa!5e0!3m2!1svi!2sus!4v1749550472535!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/boutique1.jpg', 'img/boutique2.jpg', 'img/boutique3.jpg', 'img/boutique4.jpg', 'img/boutique5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 7500000.00, '2025-11-14 02:37:47'),
(16, 'Hanoi City Center Hotel', 'bình dân', 3.5, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.911665530795!2d105.84568359678956!3d21.0362202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab6fc8a87131%3A0xd9f6234d22d886e8!2sHanoi%20Central%20Hotel%20%26%20R%C3%A9idencess!5e0!3m2!1svi!2sus!4v1749550525225!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/citycenter1.jpg', 'img/citycenter2.jpg', 'img/citycenter3.jpg', 'img/citycenter4.jpg', 'img/citycenter5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 8500000.00, '2025-11-14 02:37:47'),
(17, 'Hanoi Riverside Hotel', 'bình dân', 3.4, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59580.033185654436!2d105.72882494863282!3d21.042603900000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab3d86582333%3A0xc9094cb34288aa8b!2sRiverside%20Hanoi%20Hotel!5e0!3m2!1svi!2sus!4v1749550565407!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn bình dân với dịch vụ tốt và giá cả hợp lý.', 'Giá cả hợp lý', 'Dịch vụ tốt', 'Vị trí thuận lợi', 'Phòng sạch sẽ', 'img/riverside1.jpg', 'img/riverside2.jpg', 'img/riverside3.jpg', 'img/riverside4.jpg', 'img/riverside5.jpg', 'Khách sạn bình dân tại trung tâm Hà Nội với dịch vụ tốt.', 9000000.00, '2025-11-14 02:37:47'),
(18, 'Hanoi Luxury Hotel', 'cao cấp', 3.3, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0728704802077!2d105.8455597743844!3d21.029770080619794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abc0ac58e70b%3A0xf3b522da2dbcc2dc!2sLuxury%20Hotel!5e0!3m2!1svi!2sus!4v1749550601701!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Hà Nội', 'Khách sạn cao cấp với dịch vụ hoàn hảo và tiện nghi hiện đại.', 'Tiện nghi hiện đại', 'Dịch vụ hoàn hảo', 'Vị trí trung tâm', 'Ẩm thực đa dạng', 'img/luxury1.jpg', 'img/luxury2.jpg', 'img/luxury3.jpg', 'img/luxury4.jpg', 'img/luxury5.jpg', 'Khách sạn cao cấp tại trung tâm Hà Nội với dịch vụ 5 sao.', 18000000.00, '2025-11-14 02:37:47'),
(19, 'Havana Nha Trang Hotel', 'cao cấp', 3.2, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5215162351005!2d106.69780847412375!3d10.771311489377087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4098efffff%3A0x8b94c60b58c59db1!2sHavana%20Nha%20Trang%20Hotel!5e0!3m2!1svi!2sus!4v1749550630877!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'Khánh Hòa', 'Khách sạn sang trọng tại bãi biển Nha Trang với dịch vụ 5 sao.', 'View biển', 'Spa cao cấp', 'Ẩm thực', 'An toàn', 'img/Havana1.jpg', 'img/havana2.jpg', 'img/havana3.jpg', 'img/havana4.jpg', 'img/havana5.jpg', 'WiFi miễn phí, Hồ bơi infinity, Phòng gym, Spa, 3 nhà hàng, Bãi đỗ xe miễn phí', 27000000.00, '2025-11-14 02:37:47'),
(20, 'The Anam', 'bình dân', 3.0, 'bình thường', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5550435448213!2d106.69563507308054!3d10.768734961995364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f05809b5a99%3A0x7a780755a9c66e01!2sAnam%20Cafe%20%26%20HomeStay!5e0!3m2!1svi!2sus!4v1749550844076!5m2!1svi!2sus\" width=\"1300\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'TP.HCM', 'Khách sạn phong cách hiện đại tại trung tâm thành phố.', 'Tiện lợi', 'Sạch sẽ', 'Thân thiện', 'Giá tốt', 'img/anam1.jpg', 'img/anam2.jpg', 'img/anam3.jpg', 'img/anam4.jpg', 'img/anam5.jpg', 'WiFi miễn phí, Phòng gym, Nhà hàng, Bar, Dịch vụ 24h', 23000000.00, '2025-11-14 02:37:47'),
(25, 'Khách sạn ma ám', 'cao cấp', 0.0, 'bình thường', 'https://console.cloud.google.com/auth/clients/create?project=vast-incline-478021-v9', 'Đà Lạt', '<p><strong>Kh&aacute;ch sạn n&agrave;y nhi&ecirc;u ma lắm</strong></p>', 'Chạy đê', 'Đói qua', 'trả mạng cho t', 'đến đây', 'dảgdb', 'dừA', 'qr3t4wg', 'DỪE', 'QFEG', '<p><em>Kh&aacute;ch sạn đẳng cấp 5 sao d&agrave;nh cho giới thượng lưu</em></p>', 1200000.00, '2025-11-16 01:57:51'),
(26, '12345', '', 0.0, 'bình thường', '345', '56', '<p>rth</p>', 'g', '', '', '', '234', 'dfg', 'fg', 'fgh', 'fgh', '', 23456.00, '2025-11-16 18:12:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `Mauser` int NOT NULL,
  `Tendangnhap` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Sdt` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matkhau` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `linkavatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `google_uid` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`Mauser`, `Tendangnhap`, `Email`, `Sdt`, `matkhau`, `linkavatar`, `admin`, `created_at`, `google_uid`) VALUES
(1, 'chenfeiyu', 'chenfeiyu@gmail.com', '0347982982', 'password123', 'img/chen feiyu.jpg', 0, '2025-11-14 02:37:23', NULL),
(2, 'Dan', 'dangvandan205@gmail.com', '0987654321', 'password456', 'img/cha1.jpg', 0, '2025-11-14 02:37:23', NULL),
(3, 'Thanh', 'Thanhthanh207@gmail.com', '0123456789', 'password789', 'img/chenfeiyu.jpg', 0, '2025-11-14 02:37:23', NULL),
(4, 'ĐẶNG ĐAN', 'dangvandan1206@gmail.com', '034 727 8235', '$2y$10$U2QgMIaT/Ahe9JOqUiPAt.63NMJQ0sKBp7hlFjnbjkKvXI5GHVH/m', 'https://lh3.googleusercontent.com/a/ACg8ocKF-UQ2SlIPuf3pLQHQ83XEFsgg3cmq8drCDVqmNRq3ds-wr1al=s96-c', 1, '2025-11-14 03:51:41', 'qlPtGIlqsjgScsWe9bhEJcIxwPC3'),
(5, 'ĐANN', 'dangvandan2005@gmail.com', '111 123 4567', '$2y$10$7UUZkeFX.jX7dxHdreIl..TFKYiO10MaKn3eN/HIKpvNTDPUt/kHC', NULL, 0, '2025-11-17 04:17:20', NULL),
(6, 'tuananh11092803_SFQkX', 'tuananh11092803@gmail.com', NULL, '$2y$10$xSfG7fSKx81fFvgaUIYw6eYe2zjfJXU.TgMWccmvln6H0aHvVMPim', 'https://lh3.googleusercontent.com/a/ACg8ocIwoPjVAFnLwPIkIgr1OGrgSA0wQmEKy6gWNeBKfa2-9n2fKA=s96-c', 0, '2025-11-16 22:29:42', 'SFQkXIcQQ2PiO3kHr0D6r3mZ8Hm1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review`
--

CREATE TABLE `review` (
  `Mareview` int NOT NULL,
  `Mauser` int NOT NULL,
  `MaKS` int NOT NULL,
  `diemreview` decimal(2,1) NOT NULL,
  `mucdich` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dicung` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `noidung` text COLLATE utf8mb4_general_ci,
  `tieude` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `thoigian` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `linkavatar` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `review`
--

INSERT INTO `review` (`Mareview`, `Mauser`, `MaKS`, `diemreview`, `mucdich`, `dicung`, `noidung`, `tieude`, `thoigian`, `linkavatar`) VALUES
(1, 1, 1, 4.5, 'Công tác', 'Một mình', 'Chuyến công tác của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi.', 'Chuyến công tác của tôi thật tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(2, 1, 2, 4.8, 'Du lịch', 'Bạn bè', 'Khách sạn sang trọng với dịch vụ hoàn hảo. Tôi rất hài lòng với kỳ nghỉ của mình.', 'Kỳ nghỉ tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(3, 1, 3, 4.7, 'Công tác', 'Đối tác', 'Dịch vụ chuyên nghiệp và tiện nghi hiện đại. Rất thích hợp cho các cuộc họp và hội nghị.', 'Cuộc họp thành công', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(4, 1, 4, 4.9, 'Du lịch', 'Gia đình', 'Một trong những khách sạn tốt nhất mà tôi từng ở. Dịch vụ hoàn hảo và không gian sang trọng.', 'Trải nghiệm sang trọng', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(5, 1, 5, 4.6, 'Công tác', 'Đối tác', 'Khách sạn tuyệt vời với dịch vụ hoàn hảo. Tôi sẽ quay lại đây trong tương lai.', 'Dịch vụ hoàn hảo', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(6, 1, 6, 4.5, 'Du lịch', 'Bạn bè', 'Một kỳ nghỉ tuyệt vời với bạn bè. Không gian thoải mái và dịch vụ tốt.', 'Kỳ nghỉ cùng bạn bè', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(7, 1, 7, 4.4, 'Công tác', 'Đối tác', 'Khách sạn sang trọng với dịch vụ hoàn hảo. Rất thích hợp cho các cuộc họp và hội nghị.', 'Cuộc họp thành công', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(8, 1, 8, 4.3, 'Du lịch', 'Gia đình', 'Trải nghiệm tuyệt vời cùng gia đình. Dịch vụ tốt và không gian thoải mái.', 'Kỳ nghỉ gia đình hoàn hảo', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(9, 1, 9, 4.2, 'Công tác', 'Một mình', 'Chuyến công tác của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi.', 'Chuyến công tác của tôi thật tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(10, 1, 10, 4.1, 'Du lịch', 'Bạn bè', 'Khách sạn sang trọng với dịch vụ hoàn hảo. Tôi rất hài lòng với kỳ nghỉ của mình.', 'Kỳ nghỉ tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(11, 1, 11, 4.0, 'Công tác', 'Đối tác', 'Dịch vụ chuyên nghiệp và tiện nghi hiện đại. Rất thích hợp cho các cuộc họp và hội nghị.', 'Cuộc họp thành công', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(12, 1, 12, 3.9, 'Du lịch', 'Gia đình', 'Một trong những khách sạn tốt nhất mà tôi từng ở. Dịch vụ hoàn hảo và không gian sang trọng.', 'Trải nghiệm sang trọng', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(13, 1, 13, 3.8, 'Công tác', 'Đối tác', 'Khách sạn tuyệt vời với dịch vụ hoàn hảo. Tôi sẽ quay lại đây trong tương lai.', 'Dịch vụ hoàn hảo', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(14, 1, 14, 3.7, 'Du lịch', 'Bạn bè', 'Một kỳ nghỉ tuyệt vời với bạn bè. Không gian thoải mái và dịch vụ tốt.', 'Kỳ nghỉ cùng bạn bè', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(15, 1, 15, 3.6, 'Công tác', 'Đối tác', 'Khách sạn sang trọng với dịch vụ hoàn hảo. Rất thích hợp cho các cuộc họp và hội nghị.', 'Cuộc họp thành công', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(16, 1, 16, 3.5, 'Du lịch', 'Gia đình', 'Trải nghiệm tuyệt vời cùng gia đình. Dịch vụ tốt và không gian thoải mái.', 'Kỳ nghỉ gia đình hoàn hảo', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(17, 1, 17, 3.4, 'Công tác', 'Một mình', 'Chuyến công tác của tôi tại khách sạn này rất tuyệt vời. Nhân viên thân thiện, phòng sạch sẽ và vị trí thuận lợi.', 'Chuyến công tác của tôi thật tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(18, 1, 18, 3.3, 'Du lịch', 'Bạn bè', 'Khách sạn sang trọng với dịch vụ hoàn hảo. Tôi rất hài lòng với kỳ nghỉ của mình.', 'Kỳ nghỉ tuyệt vời', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(19, 1, 19, 3.2, 'Công tác', 'Đối tác', 'Dịch vụ chuyên nghiệp và tiện nghi hiện đại. Rất thích hợp cho các cuộc họp và hội nghị.', 'Cuộc họp thành công', '2025-11-14 02:37:47', 'img/chenfeiyu.jpg'),
(20, 4, 1, 1.0, 'Du lịch', 'sdcvc', 'dfdfeddrewfsda', 'tệ', '2025-11-14 06:21:33', NULL),
(21, 4, 4, 5.0, 'Du lịch', 'Gia đình', 'Thật sự chuyến đi này tôi thấy rất vui và ý nghĩa vô cùng :_)', 'Oke', '2025-11-14 17:02:13', 'img/cha1.jpg'),
(22, 4, 6, 5.0, 'gia đình', 'bạn', 'Chuyến đi rất vui cùng daddy', 'Chuyến đi vui vẻ', '2025-11-14 21:13:58', 'img/chenfe.png'),
(23, 4, 2, 1.0, 'công tác', 'một mình', 'bình thường bình thường bình thường bình thường bình thường', 'i dont know', '2025-11-16 17:34:46', 'img/chenfe.png'),
(24, 4, 7, 5.0, 'erwfgrer', 'waerfsgdwefwrgt', 'thfjggfdsfsbgfndgsfadcgvhr', 'ertehryj', '2025-11-17 03:13:56', 'img/chenfe.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int NOT NULL,
  `hotel_id` int NOT NULL,
  `room_type` enum('Phòng Đơn','Phòng Đôi') COLLATE utf8mb4_general_ci NOT NULL,
  `room_number` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `amenities` text COLLATE utf8mb4_general_ci,
  `capacity` int NOT NULL DEFAULT '1',
  `status` enum('Có sẵn','Đặt trước','Đang sử dụng','Bảo trì') COLLATE utf8mb4_general_ci DEFAULT 'Có sẵn',
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_type`, `room_number`, `price_per_night`, `amenities`, `capacity`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Phòng Đơn', 'A101', 800000.00, 'WiFi, Điều hòa, TV, Phòng tắm riêng 2', 3, 'Có sẵn', 'Phòng tiêu chuẩn với tiện nghi cơ bản', '2025-11-14 02:41:21', '2025-11-16 10:17:09'),
(2, 1, 'Phòng Đôi', 'A102', 1200000.00, 'WiFi, Điều hòa, TV, Phòng tắm riêng, Ban công', 5, 'Có sẵn', 'Phòng spacious với view đẹp', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(3, 1, 'Phòng Đơn', 'A103', 800000.00, 'WiFi, Điều hòa, TV, Phòng tắm riêng', 2, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(4, 1, 'Phòng Đôi', 'A104', 1200000.00, 'WiFi, Điều hòa, TV, Phòng tắm riêng, Ban công', 4, 'Có sẵn', 'Phòng spacious', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(6, 2, 'Phòng Đôi', 'B102', 1500000.00, 'WiFi, Điều hòa, TV, Phòng tắm hiện đại, Minibar, Jacuzzi', 5, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(7, 2, 'Phòng Đơn', 'B103', 1000000.00, 'WiFi, Điều hòa, TV, Phòng tắm hiện đại, Minibar', 2, 'Bảo trì', 'Phòng cao cấp', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(8, 3, 'Phòng Đơn', 'C101', 950000.00, 'WiFi, Điều hòa, TV, Phòng tắm sang trọng', 4, 'Có sẵn', 'Phòng sang trọng', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(9, 3, 'Phòng Đôi', 'C102', 1400000.00, 'WiFi, Điều hòa, TV, Phòng tắm sang trọng, Ban công', 6, 'Có sẵn', 'Phòng executive', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(10, 4, 'Phòng Đôi', 'D101', 1100000.00, 'có nhiều', 3, 'Có sẵn', 'Phòng xịn nhất Việt nam 2025', '2025-11-14 02:41:21', '2025-11-14 11:47:04'),
(11, 4, 'Phòng Đôi', 'D102', 1597000.00, '0', 4, 'Có sẵn', '0', '2025-11-14 02:41:21', '2025-11-14 04:29:42'),
(12, 5, 'Phòng Đơn', 'E101', 900000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(13, 5, 'Phòng Đôi', 'E102', 1300000.00, 'WiFi, Điều h&amp;amp;ograve;a, TV, Ph&amp;amp;ograve;ng tắm, Ban c&amp;amp;ocirc;ng', 4, 'Có sẵn', 'Ph&amp;amp;ograve;ng deluxe', '2025-11-14 02:41:21', '2025-11-16 15:24:38'),
(14, 6, 'Phòng Đơn', 'F101', 850000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 2, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(15, 6, 'Phòng Đôi', 'F102', 1250000.00, '<p><strong>&Ocirc; k&igrave;a</strong></p>', 3, 'Có sẵn', '<p>kh&ocirc;ng</p>', '2025-11-14 02:41:21', '2025-11-16 15:40:20'),
(16, 7, 'Phòng Đơn', 'G101', 800000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 4, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(17, 7, 'Phòng Đôi', 'G102', 1200000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 5, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(18, 8, 'Phòng Đơn', 'H101', 800000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(19, 8, 'Phòng Đôi', 'H102', 1200000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(20, 9, 'Phòng Đơn', 'I101', 820000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(21, 9, 'Phòng Đôi', 'I102', 1250000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(22, 10, 'Phòng Đơn', 'J101', 800000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 2, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(23, 10, 'Phòng Đôi', 'J102', 1200000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 3, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(24, 11, 'Phòng Đơn', 'K101', 600000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 5, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(25, 11, 'Phòng Đôi', 'K102', 900000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 6, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(26, 12, 'Phòng Đơn', 'L101', 550000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 4, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(27, 12, 'Phòng Đôi', 'L102', 850000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 5, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(28, 13, 'Phòng Đơn', 'M101', 500000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(29, 13, 'Phòng Đôi', 'M102', 750000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(30, 14, 'Phòng Đơn', 'N101', 600000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 4, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(31, 14, 'Phòng Đôi', 'N102', 900000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 5, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(32, 15, 'Phòng Đơn', 'O101', 550000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(33, 15, 'Phòng Đôi', 'O102', 850000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(34, 16, 'Phòng Đơn', 'P101', 700000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(35, 16, 'Phòng Đôi', 'P102', 1000000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(36, 17, 'Phòng Đơn', 'Q101', 650000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(37, 17, 'Phòng Đôi', 'Q102', 950000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(38, 18, 'Phòng Đơn', 'R101', 700000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(39, 18, 'Phòng Đôi', 'R102', 1050000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(40, 19, 'Phòng Đơn', 'S101', 500000.00, 'WiFi, Điều hòa, TV, Phòng tắm', 3, 'Có sẵn', 'Phòng tiêu chuẩn', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(41, 19, 'Phòng Đôi', 'S102', 750000.00, 'WiFi, Điều hòa, TV, Phòng tắm, Ban công', 4, 'Có sẵn', 'Phòng deluxe', '2025-11-14 02:41:21', '2025-11-14 02:41:21'),
(42, 1, 'Phòng Đơn', 'F102', 100000.00, '<p><strong>Chả c&oacute; g&igrave;</strong></p>', 9, 'Có sẵn', '<p><em>oke em neh</em></p>', '2025-11-16 16:38:00', '2025-11-16 16:38:21'),
(43, 26, 'Phòng Đơn', '12', 75000.00, '<p>ggn</p>', 1, 'Có sẵn', '', '2025-11-16 18:13:15', '2025-11-16 18:13:46'),
(44, 26, 'Phòng Đơn', 'ẻtghj', 12000.00, '', 1, '', '', '2025-11-16 18:14:10', '2025-11-16 18:14:10');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `khachsan`
--
ALTER TABLE `khachsan`
  ADD PRIMARY KEY (`MaKS`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`Mauser`),
  ADD UNIQUE KEY `Tendangnhap` (`Tendangnhap`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `google_uid` (`google_uid`);

--
-- Chỉ mục cho bảng `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Mareview`),
  ADD KEY `Mauser` (`Mauser`),
  ADD KEY `MaKS` (`MaKS`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `khachsan`
--
ALTER TABLE `khachsan`
  MODIFY `MaKS` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `Mauser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `review`
--
ALTER TABLE `review`
  MODIFY `Mareview` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nguoidung` (`Mauser`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `khachsan` (`MaKS`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`Mauser`) REFERENCES `nguoidung` (`Mauser`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`MaKS`) REFERENCES `khachsan` (`MaKS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `khachsan` (`MaKS`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
