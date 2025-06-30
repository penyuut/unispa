-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 01:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unispa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `trainee_id` varchar(10) NOT NULL,
  `workshop_id` int(11) DEFAULT NULL,
  `status` enum('Present','Absent') NOT NULL DEFAULT 'Absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `trainee_id`, `workshop_id`, `status`) VALUES
(8, 'T001', 101, 'Present'),
(9, 'T001', 1, 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookID` int(11) NOT NULL,
  `customerName` varchar(100) DEFAULT NULL,
  `serviceName` varchar(100) DEFAULT NULL,
  `bookingDate` date DEFAULT NULL,
  `bookingTime` time DEFAULT NULL,
  `paymentStatus` varchar(20) DEFAULT NULL,
  `paymentProof` varchar(255) DEFAULT NULL,
  `extraRequest` text DEFAULT NULL,
  `serviceID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookID`, `customerName`, `serviceName`, `bookingDate`, `bookingTime`, `paymentStatus`, `paymentProof`, `extraRequest`, `serviceID`) VALUES
(1008, 'penyu', 'Signature Normal Facial', '2025-06-24', '12:00:00', 'Pending', 'photo_6262787669281065756_y.jpg', '', 'S001'),
(1012, 'penyu', 'Dry Hair Cut : Long', '2025-06-26', '10:00:00', 'Pending', NULL, '', 'S024'),
(1013, 'penyu', 'Hydrating Facial', '2025-06-30', '11:00:00', 'Pending', NULL, '', 'S004');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `custID` varchar(10) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `custType` enum('staff','student','others') NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`custID`, `username`, `custType`, `name`, `email`, `contact_number`, `dob`, `password`, `profile_picture`, `gender`, `profile_pic`) VALUES
('C001', 'alia001', 'student', 'Alia Binti Ahmad', 'alia@example.com', '012-3456789', '2000-04-12', 'alia123', NULL, 'Female', NULL),
('C002', 'hafiz95', 'staff', 'Muhammad Hafiz', 'hafiz@example.com', '013-9876543', '1995-08-20', 'hafiz456', NULL, 'Male', NULL),
('C003', 'penyu', 'student', 'penyu masak hijau', 'penyu@gmail.com', '011-29464789', '2003-12-03', '123', 'uploads/customers/C003_1750927677.jpg', 'Female', NULL),
('C004', 'Wahid', 'staff', '', 'wahid@gmail.com', '012-9192027', '1998-12-12', '123', NULL, 'Male', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `logbook_id` int(11) NOT NULL,
  `trainee_id` varchar(50) DEFAULT NULL,
  `date_submitted` date DEFAULT NULL,
  `content` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `workshop_id` int(11) DEFAULT NULL,
  `workshop_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook`
--

INSERT INTO `logbook` (`logbook_id`, `trainee_id`, `date_submitted`, `content`, `remarks`, `workshop_id`, `workshop_title`) VALUES
(1, 'T001', '2025-06-21', 'Today I learned massage basics.', 'Good job!', 1, NULL),
(2, '234', '2025-06-23', 'Im learning nothing', NULL, 1, NULL),
(3, '234', '2025-06-23', 'Im learning nothing', NULL, 2, NULL),
(5, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceID` varchar(20) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `promo_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceID`, `service_name`, `category`, `price`, `description`, `duration`, `promo_details`) VALUES
('S001', 'Signature Normal Facial', 'Facial Treatments', 50.00, 'Basic facial treatment', 30, ''),
('S002', 'Deep Cleansing Facial', 'Facial Treatments', 100.00, 'Cleanses deep impurities', 60, ''),
('S003', 'Anti-Aging Facial', 'Facial Treatments', 99.00, 'Normal price: RM 150.00\r\nNew Price : RM 99.00', 90, 'Promo'),
('S004', 'Hydrating Facial', 'Facial Treatments', 250.00, 'Hydrates dry skin', 90, ''),
('S005', 'Whitening Facial', 'Facial Treatments', 50.00, 'Normal price: RM 150.00\r\nNew price : RM 50.00', 90, 'Promo'),
('S006', 'Aromatherapy Massage (60 mins)', 'Message Therapies', 120.00, 'Relaxing aroma oil massage', 60, ''),
('S007', 'Aromatherapy Massage (90 mins)', 'Message Therapies', 150.00, 'Extended aroma oil massage', 90, ''),
('S008', 'Sport Massage (60 mins)', 'Message Therapies', 120.00, 'Deep muscle massage', 60, ''),
('S009', 'Sport Massage (90 mins)', 'Message Therapies', 150.00, 'Extended sport massage', 90, ''),
('S010', 'Stress Massage (60 mins)', 'Message Therapies', 70.00, 'Relieves stress & tension', 60, ''),
('S011', 'Foot Reflexology (30 mins)', 'Message Therapies', 60.00, 'Soothes tired feet', 30, ''),
('S012', 'Classic Manicure', 'Nail & Foot Care', 55.00, 'A basic nail care treatment', 45, ''),
('S013', 'Spa Pedicure', 'Nail & Foot Care', 40.00, 'A luxurious foot care treatment', 60, ''),
('S014', 'Gel Polish Add-On ', 'Nail & Foot Care', 20.00, 'A long-lasting, high-shine polish applied over natural nails or enhancements, cured under UV or LED light for instant drying and extended wear without chipping', 20, ''),
('S015', 'Foot Soak & Massage', 'Nail & Foot Care', 60.00, 'A calming soak to soften feet, followed by a gentle massage to ease tension and boost circulation', 30, ''),
('S016', 'Combo : Classic Manicure + Spa Pedicure', 'Nail & Foot Care', 69.90, 'A complete hand and foot care package that includes nail shaping, cuticle care, exfoliation, massage, and polish for both hands and feet', 90, ''),
('S018', 'Head Spa Treatment ', 'Muslimah Hair Cut & Hair Spa (Women)', 150.00, 'A rejuvenating scalp therapy that includes massage, cleansing, and conditioning to relieve stress, improve circulation, and promote healthy hair and scalp', 45, ''),
('S019', 'Wash and Blow', 'Muslimah Hair Cut & Hair Spa (Women)', 45.00, 'A hair service that includes shampooing, conditioning, and professional blow-drying for smooth, styled, and refreshed hair', 30, ''),
('S020', 'Scalp Massage Aromatherapy', 'Muslimah Hair Cut & Hair Spa (Women)', 25.00, 'A relaxing scalp massage using essential oils ', 30, ''),
('S021', 'Dry Hair Cut : Short', 'Muslimah Hair Cut & Hair Spa (Women)', 25.00, 'A quick trim or style cut on short, dry hair without washing', 20, ''),
('S022', 'Dry Hair Cut : Long', 'Muslimah Hair Cut & Hair Spa (Women)', 55.00, 'A shape or length trim on long, dry hair, ideal for maintaining style', 30, ''),
('S023', 'Dry Hair Cut : Short', 'Barber & Hair Spa (Men)', 18.00, 'A quick trim or style adjustment on short, dry hair without washing', 20, ''),
('S024', 'Dry Hair Cut : Long', 'Barber & Hair Spa (Men)', 28.00, 'A shape or length trim on long, dry hair, done without washing', 30, ''),
('S025', 'Head Spa Treatment', 'Barber & Hair Spa (Men)', 100.00, 'A rejuvenating scalp therapy', 45, ''),
('S026', 'Wash and Blow', 'Barber & Hair Spa (Men)', 45.00, 'A hair service that includes shampooing, conditioning, and professional blow-drying for smooth, styled, and refreshed hair', 30, ''),
('S027', 'Scalp Massage Aromatherapy ', 'Barber & Hair Spa (Men)', 25.00, 'A relaxing scalp massage using essential oil', 30, ''),
('S028', 'Graduation', 'Makeup', 100.00, 'A polished and long-lasting makeup look tailored for graduation day', 60, ''),
('S029', 'Event', 'Makeup', 120.00, 'A professional and long-lasting makeup application tailored for special occasions ', 75, '');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` varchar(10) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `staff_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `role`, `staff_name`, `email`, `contact_number`, `date_of_birth`, `gender`, `username`, `password`) VALUES
('A001', 'Admin', 'Tasya Diana', 'aliahtasya04@gmail.com', '0198989007', '1998-09-19', 'Female', 'tasya', '123'),
('S001', 'Staff', 'Alif Farhan', 'aliff@gmail.com', '0197876601', '1977-09-09', 'Male', 'alif', '123');

-- --------------------------------------------------------

--
-- Table structure for table `trainee`
--

CREATE TABLE `trainee` (
  `trainee_id` varchar(10) NOT NULL,
  `trainee_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `progress_level` varchar(50) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(20) DEFAULT 'Trainee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainee`
--

INSERT INTO `trainee` (`trainee_id`, `trainee_name`, `email`, `contact_number`, `date_of_birth`, `gender`, `progress_level`, `enrollment_date`, `password`, `username`, `role`) VALUES
('T001', 'Yasmin Nadira', 'yasmin04@gmail.com', '011-29464789', '1988-12-12', 'Female', 'Beginner', '2027-12-09', '123', 'yasmin', 'Trainee'),
('T002', 'Farah Delisya', 'alrizy12@gmail.com', '01112345678', '2002-04-01', 'Female', 'Beginner', '2027-02-22', '123', 'delisya', 'Trainee');

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `trainer_id` varchar(10) NOT NULL,
  `trainer_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL DEFAULT 'Female',
  `speciality` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT 'defaultpass',
  `username` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT 'Trainer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`trainer_id`, `trainer_name`, `email`, `contact_number`, `date_of_birth`, `gender`, `speciality`, `qualification`, `password`, `username`, `role`) VALUES
('TR001', 'Cik Farah', 'farah@example.com', '011-12345678', '1992-05-14', 'Female', 'Facial Therapy', 'Diploma in Beauty Therapy', '123', 'farah01', 'Trainer'),
('TR002', 'Encik Adam', 'adam@example.com', '011-23456789', '1985-11-02', 'Male', 'Massage Techniques', 'Certificate in Wellness Training', '123', 'adam02', 'Trainer'),
('TR003', 'Farisha Jamal', 'farishajamal99@gmail.com', '019-3741494', '1996-08-08', 'Female', 'Haircut Muslimah', 'Certified Muslimah Stylist', '123', 'farisha', 'Trainer'),
('TR004', 'Aleefa Najwa', 'alrizy12@gmail.com', '0178896655', '2004-12-24', 'Female', 'Massage Techniques', 'Certificate in Wellness Training', '123', 'aleefa', 'Trainer');

-- --------------------------------------------------------

--
-- Table structure for table `workshop`
--

CREATE TABLE `workshop` (
  `workshop_id` int(11) NOT NULL,
  `trainer_id` varchar(10) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshop`
--

INSERT INTO `workshop` (`workshop_id`, `trainer_id`, `title`, `description`, `date`, `location`, `capacity`) VALUES
(1, 'TR001', 'Introduction to Spa Therapy', 'Learn the basics of spa therapy and customer care.', '2025-06-21', 'Room A', 200),
(2, 'TR002', 'Massage Techniques', 'Hands-on training for professional massage skills.', '2025-06-22', 'Room B', 15),
(3, 'TR001', 'Customer Service in Spa', 'Best practices for interacting with clients.', '2025-06-23', 'Room C', 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `workshop_id` (`workshop_id`),
  ADD KEY `attendance_ibfk_1` (`trainee_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`custID`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`logbook_id`),
  ADD KEY `fk_logbook_workshop` (`workshop_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`serviceID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `trainee`
--
ALTER TABLE `trainee`
  ADD PRIMARY KEY (`trainee_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`trainer_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `workshop`
--
ALTER TABLE `workshop`
  ADD PRIMARY KEY (`workshop_id`),
  ADD KEY `workshop_ibfk_1` (`trainer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `logbook_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workshop`
--
ALTER TABLE `workshop`
  MODIFY `workshop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`trainee_id`) REFERENCES `trainee` (`trainee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logbook`
--
ALTER TABLE `logbook`
  ADD CONSTRAINT `fk_logbook_workshop` FOREIGN KEY (`workshop_id`) REFERENCES `workshop` (`workshop_id`);

--
-- Constraints for table `workshop`
--
ALTER TABLE `workshop`
  ADD CONSTRAINT `workshop_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`trainer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
