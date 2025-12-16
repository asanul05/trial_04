-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 04:38 AM
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
-- Database: `osca_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `device` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL COMMENT 'For branch and barangay admins',
  `barangay_id` int(11) DEFAULT NULL COMMENT 'For barangay admins only',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `employee_id`, `username`, `password_hash`, `first_name`, `middle_name`, `last_name`, `extension`, `position`, `gender_id`, `mobile_number`, `email`, `role_id`, `branch_id`, `barangay_id`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'OSCA-ADMIN-001', 'mainadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Main', NULL, 'Administrator', NULL, 'System Administrator', 1, '09171234567', 'admin@osca.gov.ph', 1, NULL, NULL, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10'),
(2, 'OSCA-BRANCH-001', 'branch1admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Branch', NULL, 'Admin One', NULL, 'Field Office Manager', 1, '09171234568', 'field1@osca.gov.ph', 2, 2, NULL, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10'),
(3, 'OSCA-BRANCH-002', 'branch2admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Branch', NULL, 'Admin Two', NULL, 'Field Office Manager', 2, '09171234569', 'field2@osca.gov.ph', 2, 3, NULL, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10'),
(4, 'OSCA-BRGY-001', 'tetuan.admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tetuan', NULL, 'Admin', NULL, 'Barangay OSCA Coordinator', 1, '09171234570', 'tetuan@osca.gov.ph', 3, 2, 79, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10'),
(5, 'OSCA-BRGY-002', 'santamaria.admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Santa Maria', NULL, 'Admin', NULL, 'Barangay OSCA Coordinator', 2, '09171234571', 'santamaria@osca.gov.ph', 3, 2, 68, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10'),
(6, 'OSCA-BRGY-003', 'guiwan.admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Guiwan', NULL, 'Admin', NULL, 'Barangay OSCA Coordinator', 1, '09171234572', 'guiwan@osca.gov.ph', 3, 6, 26, 1, NULL, '2025-12-15 01:34:10', '2025-12-15 01:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `target_audience` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `published_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `type_id`, `title`, `description`, `event_date`, `event_time`, `location`, `target_audience`, `created_by`, `is_published`, `published_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Medical Mission 2025', 'Free medical checkup and medicines for senior citizens', '2025-10-25', '09:00:00', 'Barangay Hall, Tetuan', 'All Senior Citizens', 1, 1, '2025-01-10 00:00:00', '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(2, 1, 'Quarterly Pension (Octogenarian)', 'Distribution of quarterly pension for seniors aged 80-89', '2025-10-25', '09:00:00', 'DSWD Office', 'Ages 80-89', 1, 1, '2025-01-11 00:00:00', '2025-12-15 01:34:59', '2025-12-15 01:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_media`
--

CREATE TABLE `announcement_media` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `media_type` enum('image','video','document') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_types`
--

CREATE TABLE `announcement_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_types`
--

INSERT INTO `announcement_types` (`id`, `name`) VALUES
(3, 'Alert'),
(1, 'Event'),
(2, 'News'),
(4, 'Program');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `application_number` varchar(50) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `application_type_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Draft',
  `submitted_by` int(11) DEFAULT NULL,
  `submission_date` timestamp NULL DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `printed_by` int(11) DEFAULT NULL,
  `print_date` timestamp NULL DEFAULT NULL,
  `claimed_by` int(11) DEFAULT NULL,
  `claim_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `application_number`, `senior_id`, `application_type_id`, `status`, `submitted_by`, `submission_date`, `verified_by`, `verification_date`, `approved_by`, `approval_date`, `printed_by`, `print_date`, `claimed_by`, `claim_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'APP-2025-001', 1, 1, 'Verified', 4, '2025-01-15 02:00:00', 1, '2025-01-15 06:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(2, 'APP-2025-002', 2, 1, 'Printed', 5, '2025-01-16 01:00:00', 1, '2025-01-16 07:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(3, 'APP-2025-003', 3, 1, 'Draft', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_types`
--

CREATE TABLE `application_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `required_documents` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_types`
--

INSERT INTO `application_types` (`id`, `name`, `description`, `required_documents`) VALUES
(1, 'New ID', 'New senior citizen ID application', 'Birth Certificate, Barangay Certificate, Photo'),
(2, 'Renewal', 'Renewal of existing ID', 'Old ID, Photo'),
(3, 'Lost ID', 'Replacement for lost ID', 'Affidavit of Loss, Barangay Certificate, Photo'),
(4, 'Damaged ID', 'Replacement for damaged ID', 'Damaged ID, Photo'),
(5, 'Update', 'Update of information', 'Supporting documents for changes');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `district` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT 'Zamboanga City',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `code`, `name`, `district`, `city`, `created_at`, `updated_at`) VALUES
(1, 'ZC-001', 'Arena Blanco', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(2, 'ZC-002', 'Ayala', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(3, 'ZC-003', 'Baliwasan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(4, 'ZC-004', 'Baluno', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(5, 'ZC-005', 'Boalan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(6, 'ZC-006', 'Bolong', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(7, 'ZC-007', 'Buenavista', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(8, 'ZC-008', 'Bunguiao', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(9, 'ZC-009', 'Busay', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(10, 'ZC-010', 'Cabaluay', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(11, 'ZC-011', 'Cabatangan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(12, 'ZC-012', 'Cacao', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(13, 'ZC-013', 'Calabasa', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(14, 'ZC-014', 'Calarian', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(15, 'ZC-015', 'Campo Islam', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(16, 'ZC-016', 'Canelar', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(17, 'ZC-017', 'Capisan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(18, 'ZC-018', 'Cawit', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(19, 'ZC-019', 'Culianan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(20, 'ZC-020', 'Curuan', 'District 1', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(21, 'ZC-021', 'Dita', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(22, 'ZC-022', 'Divisoria', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(23, 'ZC-023', 'Dulian (Upper Bunguiao)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(24, 'ZC-024', 'Dulian (Lower Pasonanca)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(25, 'ZC-025', 'Guisao', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(26, 'ZC-026', 'Guiwan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(27, 'ZC-027', 'La Paz', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(28, 'ZC-028', 'Labuan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(29, 'ZC-029', 'Lamisahan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(30, 'ZC-030', 'Landang Gua', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(31, 'ZC-031', 'Landang Laum', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(32, 'ZC-032', 'Lanzones', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(33, 'ZC-033', 'Lapakan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(34, 'ZC-034', 'Latuan (Curuan)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(35, 'ZC-035', 'Licomo', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(36, 'ZC-036', 'Limaong', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(37, 'ZC-037', 'Limpapa', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(38, 'ZC-038', 'Lubigan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(39, 'ZC-039', 'Lumayang', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(40, 'ZC-040', 'Lumbangan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(41, 'ZC-041', 'Lunzuran', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(42, 'ZC-042', 'Maasin', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(43, 'ZC-043', 'Malagutay', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(44, 'ZC-044', 'Mampang', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(45, 'ZC-045', 'Manalipa', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(46, 'ZC-046', 'Mangusu', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(47, 'ZC-047', 'Manicahan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(48, 'ZC-048', 'Mariki', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(49, 'ZC-049', 'Mercedes', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(50, 'ZC-050', 'Muti', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(51, 'ZC-051', 'Pamucutan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(52, 'ZC-052', 'Pangapuyan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(53, 'ZC-053', 'Panubigan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(54, 'ZC-054', 'Pasilmanta (Sacol Island)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(55, 'ZC-055', 'Pasonanca', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(56, 'ZC-056', 'Patalon', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(57, 'ZC-057', 'Putik', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(58, 'ZC-058', 'Quiniput', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(59, 'ZC-059', 'Recodo', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(60, 'ZC-060', 'Rio Hondo', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(61, 'ZC-061', 'Salaan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(62, 'ZC-062', 'San Jose Cawa-cawa', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(63, 'ZC-063', 'San Jose Gusu', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(64, 'ZC-064', 'San Roque', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(65, 'ZC-065', 'Sangali', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(66, 'ZC-066', 'Santa Barbara', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(67, 'ZC-067', 'Santa Catalina', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(68, 'ZC-068', 'Santa Maria', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(69, 'ZC-069', 'Santo Ni?o', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(70, 'ZC-070', 'Sibulao (Caruan)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(71, 'ZC-071', 'Sinubung', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(72, 'ZC-072', 'Sinunuc', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(73, 'ZC-073', 'Tagasilay', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(74, 'ZC-074', 'Taguiti', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(75, 'ZC-075', 'Talabaan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(76, 'ZC-076', 'Talisayan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(77, 'ZC-077', 'Talon-talon', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(78, 'ZC-078', 'Taluksangay', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(79, 'ZC-079', 'Tetuan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(80, 'ZC-080', 'Tictapul', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(81, 'ZC-081', 'Tigbalabag', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(82, 'ZC-082', 'Tigtabon', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(83, 'ZC-083', 'Tolosa', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(84, 'ZC-084', 'Tugbungan', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(85, 'ZC-085', 'Tulungatung', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(86, 'ZC-086', 'Tumaga', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(87, 'ZC-087', 'Tumalutab', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(88, 'ZC-088', 'Tumitus', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(89, 'ZC-089', 'Victoria', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(90, 'ZC-090', 'Vitali', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(91, 'ZC-091', 'Zambowood', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(92, 'ZC-092', 'Zone I (Poblacion)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(93, 'ZC-093', 'Zone II (Poblacion)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(94, 'ZC-094', 'Zone III (Poblacion)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54'),
(95, 'ZC-095', 'Zone IV (Poblacion)', 'District 2', 'Zamboanga City', '2025-12-15 01:31:54', '2025-12-15 01:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `branch_head` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `contact_number`, `email`, `branch_head`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'OSCA-MAIN', 'OSCA Main Office', 'City Hall, Zamboanga City', '062-991-1234', 'osca.main@zamboanga.gov.ph', 'Main Administrator', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(2, 'OSCA-F1', 'Field Office 1 - East District', 'Tetuan, Zamboanga City', '062-991-1235', 'osca.field1@zamboanga.gov.ph', 'Branch Head 1', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(3, 'OSCA-F2', 'Field Office 2 - West District', 'Baliwasan, Zamboanga City', '062-991-1236', 'osca.field2@zamboanga.gov.ph', 'Branch Head 2', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(4, 'OSCA-F3', 'Field Office 3 - North District', 'Pasonanca, Zamboanga City', '062-991-1237', 'osca.field3@zamboanga.gov.ph', 'Branch Head 3', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(5, 'OSCA-F4', 'Field Office 4 - South District', 'Taluksangay, Zamboanga City', '062-991-1238', 'osca.field4@zamboanga.gov.ph', 'Branch Head 4', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(6, 'OSCA-F5', 'Field Office 5 - Central District', 'Guiwan, Zamboanga City', '062-991-1239', 'osca.field5@zamboanga.gov.ph', 'Branch Head 5', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(7, 'OSCA-F6', 'Field Office 6 - Coastal District', 'Recodo, Zamboanga City', '062-991-1240', 'osca.field6@zamboanga.gov.ph', 'Branch Head 6', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12'),
(8, 'OSCA-F7', 'Field Office 7 - Mountain District', 'Curuan, Zamboanga City', '062-991-1241', 'osca.field7@zamboanga.gov.ph', 'Branch Head 7', 1, '2025-12-15 01:32:12', '2025-12-15 01:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `branch_barangays`
--

CREATE TABLE `branch_barangays` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `assigned_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_barangays`
--

INSERT INTO `branch_barangays` (`id`, `branch_id`, `barangay_id`, `assigned_date`, `is_active`) VALUES
(1, 2, 1, '2025-01-01', 1),
(2, 2, 2, '2025-01-01', 1),
(3, 2, 3, '2025-01-01', 1),
(4, 2, 4, '2025-01-01', 1),
(5, 2, 5, '2025-01-01', 1),
(6, 2, 6, '2025-01-01', 1),
(7, 2, 7, '2025-01-01', 1),
(8, 2, 8, '2025-01-01', 1),
(9, 2, 9, '2025-01-01', 1),
(10, 2, 10, '2025-01-01', 1),
(11, 2, 11, '2025-01-01', 1),
(12, 2, 12, '2025-01-01', 1),
(13, 2, 13, '2025-01-01', 1),
(14, 2, 14, '2025-01-01', 1),
(16, 3, 15, '2025-01-01', 1),
(17, 3, 16, '2025-01-01', 1),
(18, 3, 17, '2025-01-01', 1),
(19, 3, 18, '2025-01-01', 1),
(20, 3, 19, '2025-01-01', 1),
(21, 3, 20, '2025-01-01', 1),
(22, 3, 21, '2025-01-01', 1),
(23, 3, 22, '2025-01-01', 1),
(24, 3, 23, '2025-01-01', 1),
(25, 3, 24, '2025-01-01', 1),
(26, 3, 25, '2025-01-01', 1),
(27, 3, 26, '2025-01-01', 1),
(28, 3, 27, '2025-01-01', 1),
(29, 3, 28, '2025-01-01', 1),
(31, 4, 29, '2025-01-01', 1),
(32, 4, 30, '2025-01-01', 1),
(33, 4, 31, '2025-01-01', 1),
(34, 4, 32, '2025-01-01', 1),
(35, 4, 33, '2025-01-01', 1),
(36, 4, 34, '2025-01-01', 1),
(37, 4, 35, '2025-01-01', 1),
(38, 4, 36, '2025-01-01', 1),
(39, 4, 37, '2025-01-01', 1),
(40, 4, 38, '2025-01-01', 1),
(41, 4, 39, '2025-01-01', 1),
(42, 4, 40, '2025-01-01', 1),
(43, 4, 41, '2025-01-01', 1),
(44, 4, 42, '2025-01-01', 1),
(46, 5, 43, '2025-01-01', 1),
(47, 5, 44, '2025-01-01', 1),
(48, 5, 45, '2025-01-01', 1),
(49, 5, 46, '2025-01-01', 1),
(50, 5, 47, '2025-01-01', 1),
(51, 5, 48, '2025-01-01', 1),
(52, 5, 49, '2025-01-01', 1),
(53, 5, 50, '2025-01-01', 1),
(54, 5, 51, '2025-01-01', 1),
(55, 5, 52, '2025-01-01', 1),
(56, 5, 53, '2025-01-01', 1),
(57, 5, 54, '2025-01-01', 1),
(58, 5, 55, '2025-01-01', 1),
(59, 5, 56, '2025-01-01', 1),
(61, 6, 57, '2025-01-01', 1),
(62, 6, 58, '2025-01-01', 1),
(63, 6, 59, '2025-01-01', 1),
(64, 6, 60, '2025-01-01', 1),
(65, 6, 61, '2025-01-01', 1),
(66, 6, 62, '2025-01-01', 1),
(67, 6, 63, '2025-01-01', 1),
(68, 6, 64, '2025-01-01', 1),
(69, 6, 65, '2025-01-01', 1),
(70, 6, 66, '2025-01-01', 1),
(71, 6, 67, '2025-01-01', 1),
(72, 6, 68, '2025-01-01', 1),
(73, 6, 69, '2025-01-01', 1),
(74, 6, 70, '2025-01-01', 1),
(76, 7, 71, '2025-01-01', 1),
(77, 7, 72, '2025-01-01', 1),
(78, 7, 73, '2025-01-01', 1),
(79, 7, 74, '2025-01-01', 1),
(80, 7, 75, '2025-01-01', 1),
(81, 7, 76, '2025-01-01', 1),
(82, 7, 77, '2025-01-01', 1),
(83, 7, 78, '2025-01-01', 1),
(84, 7, 79, '2025-01-01', 1),
(85, 7, 80, '2025-01-01', 1),
(86, 7, 81, '2025-01-01', 1),
(87, 7, 82, '2025-01-01', 1),
(88, 7, 83, '2025-01-01', 1),
(89, 7, 84, '2025-01-01', 1),
(91, 8, 85, '2025-01-01', 1),
(92, 8, 86, '2025-01-01', 1),
(93, 8, 87, '2025-01-01', 1),
(94, 8, 88, '2025-01-01', 1),
(95, 8, 89, '2025-01-01', 1),
(96, 8, 90, '2025-01-01', 1),
(97, 8, 91, '2025-01-01', 1),
(98, 8, 92, '2025-01-01', 1),
(99, 8, 93, '2025-01-01', 1),
(100, 8, 94, '2025-01-01', 1),
(101, 8, 95, '2025-01-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `complaint_number` varchar(50) NOT NULL,
  `complainant_id` int(11) NOT NULL,
  `violator_name` varchar(255) NOT NULL,
  `violator_contact` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `incident_date` date DEFAULT NULL,
  `incident_location` text DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `amount_billable` decimal(10,2) DEFAULT NULL,
  `filed_by` int(11) NOT NULL,
  `filed_date` date NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `resolved_date` date DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_categories`
--

CREATE TABLE `complaint_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_categories`
--

INSERT INTO `complaint_categories` (`id`, `name`, `description`) VALUES
(1, 'Discount Denial', 'Establishment refused senior discount'),
(2, 'Priority Lane Violation', 'Denied priority lane access'),
(3, 'Abuse/Neglect', 'Physical or emotional abuse'),
(4, 'Discrimination', 'Age-based discrimination'),
(5, 'Service Denial', 'Denied services for seniors'),
(6, 'Pension Issues', 'Problems with pension distribution'),
(7, 'Other', 'Other complaints');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_documents`
--

CREATE TABLE `complaint_documents` (
  `id` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_statuses`
--

CREATE TABLE `complaint_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_statuses`
--

INSERT INTO `complaint_statuses` (`id`, `name`, `description`, `color_code`) VALUES
(1, 'Submitted', 'Complaint filed', '#FFA500'),
(2, 'Under Investigation', 'Being investigated', '#2196F3'),
(3, 'In Progress', 'Action being taken', '#FF9800'),
(4, 'Resolved', 'Complaint resolved', '#4CAF50'),
(5, 'Closed', 'Case closed', '#9E9E9E'),
(6, 'Rejected', 'Complaint rejected', '#F44336');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `telephone_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `barangay_id` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT 'Zamboanga City',
  `postal_code` varchar(10) DEFAULT '7000',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `mobile_number`, `telephone_number`, `email`, `house_number`, `street`, `barangay_id`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
(1, '09171111111', '062-991-1111', 'senior1@email.com', '123', 'Rizal Street', 79, 'Zamboanga City', '7000', '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(2, '09172222222', '062-991-2222', 'senior2@email.com', '456', 'Bonifacio Avenue', 68, 'Zamboanga City', '7000', '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(3, '09173333333', NULL, NULL, '789', 'Luna Street', 26, 'Zamboanga City', '7000', '2025-12-15 01:34:59', '2025-12-15 01:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `name`, `description`, `is_required`) VALUES
(1, 'Birth Certificate', 'Proof of age and identity', 1),
(2, 'Barangay Certificate', 'Certificate of residency', 1),
(3, 'Senior Photo', 'Recent photo for ID', 1),
(4, 'Affidavit of Loss', 'For lost ID replacement', 0),
(5, 'Old/Damaged ID', 'For renewal or replacement', 0),
(6, 'COMELEC ID', 'Voter identification', 0),
(7, 'Medical Certificate', 'For PWD or special cases', 0);

-- --------------------------------------------------------

--
-- Table structure for table `educational_attainment`
--

CREATE TABLE `educational_attainment` (
  `id` int(11) NOT NULL,
  `level` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational_attainment`
--

INSERT INTO `educational_attainment` (`id`, `level`, `description`) VALUES
(1, 'No Formal Education', 'No formal schooling'),
(2, 'Elementary Level', 'Some elementary education'),
(3, 'Elementary Graduate', 'Completed elementary'),
(4, 'High School Level', 'Some high school education'),
(5, 'High School Graduate', 'Completed high school'),
(6, 'Vocational/Technical', 'Vocational or technical training'),
(7, 'College Level', 'Some college education'),
(8, 'College Graduate', 'Completed college degree'),
(9, 'Post Graduate', 'Masters or Doctorate degree');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `registered_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `attended` tinyint(1) DEFAULT 0,
  `attendance_date` timestamp NULL DEFAULT NULL,
  `claimed_benefit` tinyint(1) DEFAULT 0,
  `claim_date` timestamp NULL DEFAULT NULL,
  `claimed_by_admin` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_members`
--

CREATE TABLE `family_members` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `relationship` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`) VALUES
(2, 'Female'),
(1, 'Male'),
(3, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `id_statuses`
--

CREATE TABLE `id_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `id_statuses`
--

INSERT INTO `id_statuses` (`id`, `name`, `description`) VALUES
(1, 'Active', 'ID is active and valid'),
(2, 'Expired', 'ID has expired'),
(3, 'Lost', 'ID reported as lost'),
(4, 'Damaged', 'ID reported as damaged'),
(5, 'Suspended', 'ID temporarily suspended'),
(6, 'Replaced', 'ID has been replaced');

-- --------------------------------------------------------

--
-- Table structure for table `mobility_levels`
--

CREATE TABLE `mobility_levels` (
  `id` int(11) NOT NULL,
  `level` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobility_levels`
--

INSERT INTO `mobility_levels` (`id`, `level`, `description`) VALUES
(1, 'Independent', 'Able to move without assistance'),
(2, 'Assisted', 'Requires assistance for mobility'),
(3, 'Wheelchair Bound', 'Uses wheelchair'),
(4, 'Bedridden', 'Confined to bed');

-- --------------------------------------------------------

--
-- Table structure for table `registration_statuses`
--

CREATE TABLE `registration_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_statuses`
--

INSERT INTO `registration_statuses` (`id`, `name`, `description`, `color_code`) VALUES
(1, 'Pending', 'Application pending review', '#FFA500'),
(2, 'Approved', 'Application approved', '#4CAF50'),
(3, 'Rejected', 'Application rejected', '#F44336'),
(4, 'For Verification', 'Needs verification', '#2196F3'),
(5, 'For Printing', 'Ready for ID printing', '#9C27B0'),
(6, 'Completed', 'Process completed', '#4CAF50');

-- --------------------------------------------------------

--
-- Table structure for table `senior_citizens`
--

CREATE TABLE `senior_citizens` (
  `id` int(11) NOT NULL,
  `osca_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `gender_id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `educational_attainment_id` int(11) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT 0.00,
  `occupation` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `socioeconomic_status_id` int(11) DEFAULT NULL,
  `mobility_level_id` int(11) DEFAULT NULL,
  `barangay_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `registration_date` date NOT NULL,
  `registration_status_id` int(11) NOT NULL,
  `registered_by` int(11) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `thumbmark_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deceased` tinyint(1) DEFAULT 0,
  `deceased_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `senior_citizens`
--

INSERT INTO `senior_citizens` (`id`, `osca_id`, `first_name`, `middle_name`, `last_name`, `extension`, `birthdate`, `gender_id`, `contact_id`, `educational_attainment_id`, `monthly_salary`, `occupation`, `other_skills`, `socioeconomic_status_id`, `mobility_level_id`, `barangay_id`, `branch_id`, `registration_date`, `registration_status_id`, `registered_by`, `photo_path`, `thumbmark_verified`, `is_active`, `is_deceased`, `deceased_date`, `created_at`, `updated_at`) VALUES
(1, 'ZC-2025-001001', 'Juan', 'Santos', 'Dela Cruz', NULL, '1953-05-15', 1, 1, 6, 5000.00, 'Retired Teacher', NULL, 2, 1, 79, 2, '2025-01-15', 2, 1, NULL, 0, 1, 0, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(2, 'ZC-2025-001002', 'Maria', 'Lopez', 'Garcia', NULL, '1950-08-22', 2, 2, 8, 8000.00, 'Retired Nurse', NULL, 2, 1, 68, 2, '2025-01-16', 2, 1, NULL, 0, 1, 0, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59'),
(3, 'ZC-2025-001003', 'Pedro', 'Ramos', 'Mendoza', NULL, '1948-12-10', 1, 3, 4, 3000.00, 'Farmer', NULL, 1, 2, 26, 6, '2025-01-17', 2, 1, NULL, 0, 1, 0, NULL, '2025-12-15 01:34:59', '2025-12-15 01:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `senior_ids`
--

CREATE TABLE `senior_ids` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `id_number` varchar(50) NOT NULL,
  `qr_code` text DEFAULT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `status_id` int(11) NOT NULL,
  `printed_by` int(11) DEFAULT NULL,
  `print_date` timestamp NULL DEFAULT NULL,
  `released_by` int(11) DEFAULT NULL,
  `release_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `senior_target_sectors`
--

CREATE TABLE `senior_target_sectors` (
  `id` int(11) NOT NULL,
  `senior_id` int(11) NOT NULL,
  `sector_id` int(11) NOT NULL,
  `other_specification` varchar(255) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socioeconomic_statuses`
--

CREATE TABLE `socioeconomic_statuses` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `income_range` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `socioeconomic_statuses`
--

INSERT INTO `socioeconomic_statuses` (`id`, `category`, `description`, `income_range`) VALUES
(1, 'Low Income', 'Low income household', 'Below ?10,000/month'),
(2, 'Middle Income', 'Middle income household', '?10,000 - ?50,000/month'),
(3, 'High Income', 'High income household', 'Above ?50,000/month'),
(4, 'No Income', 'No regular income', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `target_sectors`
--

CREATE TABLE `target_sectors` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `benefits` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `target_sectors`
--

INSERT INTO `target_sectors` (`id`, `code`, `name`, `description`, `benefits`) VALUES
(1, 'PNGNA', 'PNGNA', 'Member of national senior citizens organization', NULL),
(2, 'WEPC', 'WEPC', 'Female senior citizens in empowerment programs', NULL),
(3, 'PWD', 'PWD', 'Senior with recognized disability', NULL),
(4, 'YNSP', 'YNSP', 'Special care program', NULL),
(5, 'PASP', 'PASP', 'Hope and support program members', NULL),
(6, 'KIA/WIA', 'KIA/WIA', 'Killed in Action/Wounded in Action', NULL),
(7, 'SOLO-PARENT', 'Solo Parents', 'Senior citizen raising children alone', NULL),
(8, 'IP', 'Indigenous Person', 'Member of indigenous community', NULL),
(9, 'RPUD', 'Recovering Person', 'Recovering from substance use', NULL),
(10, '4PS', '4P\'s DSWD Beneficiary', 'Pantawid Pamilyang Pilipino Program beneficiary', NULL),
(11, 'STREET', 'Street Dwellers', 'Homeless or street dwelling', NULL),
(12, 'PSYCHO', 'Psychosocial Disability', 'Mental or learning disability', NULL),
(13, 'STATELESS', 'Stateless Person', 'Stateless or asylum seeker', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `can_export` tinyint(1) DEFAULT 0,
  `can_print` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `role_id`, `module`, `can_view`, `can_create`, `can_edit`, `can_delete`, `can_export`, `can_print`) VALUES
(1, 1, 'dashboard', 1, 0, 0, 0, 1, 0),
(2, 1, 'registration', 1, 1, 1, 1, 1, 1),
(3, 1, 'senior_citizens', 1, 1, 1, 1, 1, 1),
(4, 1, 'applications', 1, 1, 1, 1, 1, 1),
(5, 1, 'complaints', 1, 1, 1, 1, 1, 1),
(6, 1, 'id_printing', 1, 1, 1, 1, 1, 1),
(7, 1, 'announcements', 1, 1, 1, 1, 1, 0),
(8, 1, 'accounts', 1, 1, 1, 1, 1, 0),
(9, 1, 'archives', 1, 0, 0, 1, 1, 0),
(10, 1, 'reports', 1, 1, 0, 0, 1, 1),
(11, 1, 'heatmap', 1, 0, 0, 0, 0, 0),
(12, 2, 'dashboard', 1, 0, 0, 0, 1, 0),
(13, 2, 'registration', 1, 1, 1, 0, 1, 1),
(14, 2, 'senior_citizens', 1, 1, 1, 0, 1, 1),
(15, 2, 'applications', 1, 1, 1, 0, 1, 1),
(16, 2, 'complaints', 1, 1, 1, 0, 1, 1),
(17, 2, 'id_printing', 1, 0, 0, 0, 0, 1),
(18, 2, 'announcements', 1, 1, 1, 0, 1, 0),
(19, 2, 'accounts', 1, 0, 0, 0, 0, 0),
(20, 2, 'archives', 1, 0, 0, 0, 1, 0),
(21, 2, 'reports', 1, 1, 0, 0, 1, 1),
(22, 2, 'heatmap', 1, 0, 0, 0, 0, 0),
(23, 3, 'dashboard', 1, 0, 0, 0, 1, 0),
(24, 3, 'registration', 1, 1, 1, 0, 1, 0),
(25, 3, 'senior_citizens', 1, 1, 1, 0, 1, 0),
(26, 3, 'applications', 1, 1, 0, 0, 1, 0),
(27, 3, 'complaints', 1, 1, 0, 0, 1, 0),
(28, 3, 'id_printing', 0, 0, 0, 0, 0, 0),
(29, 3, 'announcements', 1, 0, 0, 0, 0, 0),
(30, 3, 'accounts', 0, 0, 0, 0, 0, 0),
(31, 3, 'archives', 1, 0, 0, 0, 1, 0),
(32, 3, 'reports', 1, 0, 0, 0, 1, 0),
(33, 3, 'heatmap', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `level` int(11) NOT NULL COMMENT '1=Main Admin, 2=Branch Admin, 3=Barangay Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`, `description`, `level`) VALUES
(1, 'Main Admin', 'Full system access - can manage all branches and barangays', 1),
(2, 'Branch Admin', 'Access to assigned branch and its barangays', 2),
(3, 'Barangay Admin', 'Access to specific barangay only', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_timestamp` (`user_id`,`timestamp`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_module` (`user_id`,`module`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_published` (`is_published`),
  ADD KEY `idx_event_date_published` (`event_date`,`is_published`);

--
-- Indexes for table `announcement_media`
--
ALTER TABLE `announcement_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `announcement_types`
--
ALTER TABLE `announcement_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_number` (`application_number`),
  ADD KEY `application_type_id` (`application_type_id`),
  ADD KEY `submitted_by` (`submitted_by`),
  ADD KEY `verified_by` (`verified_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `printed_by` (`printed_by`),
  ADD KEY `claimed_by` (`claimed_by`),
  ADD KEY `idx_application_number` (`application_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_senior` (`senior_id`),
  ADD KEY `idx_application_senior_status` (`senior_id`,`status`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `document_type_id` (`document_type_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `application_types`
--
ALTER TABLE `application_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `branch_barangays`
--
ALTER TABLE `branch_barangays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_branch_barangay` (`branch_id`,`barangay_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `complaint_number` (`complaint_number`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `filed_by` (`filed_by`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_complaint_number` (`complaint_number`),
  ADD KEY `idx_complainant` (`complainant_id`),
  ADD KEY `idx_status` (`status_id`),
  ADD KEY `idx_complaint_status_date` (`status_id`,`filed_date`);

--
-- Indexes for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `complaint_documents`
--
ALTER TABLE `complaint_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `complaint_statuses`
--
ALTER TABLE `complaint_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `educational_attainment`
--
ALTER TABLE `educational_attainment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_announcement_senior` (`announcement_id`,`senior_id`),
  ADD KEY `senior_id` (`senior_id`),
  ADD KEY `claimed_by_admin` (`claimed_by_admin`);

--
-- Indexes for table `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senior_id` (`senior_id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `id_statuses`
--
ALTER TABLE `id_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `mobility_levels`
--
ALTER TABLE `mobility_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration_statuses`
--
ALTER TABLE `registration_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `senior_citizens`
--
ALTER TABLE `senior_citizens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `osca_id` (`osca_id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `educational_attainment_id` (`educational_attainment_id`),
  ADD KEY `socioeconomic_status_id` (`socioeconomic_status_id`),
  ADD KEY `mobility_level_id` (`mobility_level_id`),
  ADD KEY `registered_by` (`registered_by`),
  ADD KEY `idx_osca_id` (`osca_id`),
  ADD KEY `idx_barangay` (`barangay_id`),
  ADD KEY `idx_branch` (`branch_id`),
  ADD KEY `idx_status` (`registration_status_id`),
  ADD KEY `idx_name` (`last_name`,`first_name`),
  ADD KEY `idx_senior_barangay_status` (`barangay_id`,`registration_status_id`),
  ADD KEY `idx_senior_branch_status` (`branch_id`,`registration_status_id`);

--
-- Indexes for table `senior_ids`
--
ALTER TABLE `senior_ids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `printed_by` (`printed_by`),
  ADD KEY `released_by` (`released_by`),
  ADD KEY `idx_id_number` (`id_number`),
  ADD KEY `idx_senior` (`senior_id`);

--
-- Indexes for table `senior_target_sectors`
--
ALTER TABLE `senior_target_sectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_senior_sector` (`senior_id`,`sector_id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- Indexes for table `socioeconomic_statuses`
--
ALTER TABLE `socioeconomic_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_sectors`
--
ALTER TABLE `target_sectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_module` (`role_id`,`module`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcement_media`
--
ALTER TABLE `announcement_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement_types`
--
ALTER TABLE `announcement_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_types`
--
ALTER TABLE `application_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `branch_barangays`
--
ALTER TABLE `branch_barangays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `complaint_documents`
--
ALTER TABLE `complaint_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_statuses`
--
ALTER TABLE `complaint_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `educational_attainment`
--
ALTER TABLE `educational_attainment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_members`
--
ALTER TABLE `family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `id_statuses`
--
ALTER TABLE `id_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mobility_levels`
--
ALTER TABLE `mobility_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registration_statuses`
--
ALTER TABLE `registration_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `senior_citizens`
--
ALTER TABLE `senior_citizens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `senior_ids`
--
ALTER TABLE `senior_ids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `senior_target_sectors`
--
ALTER TABLE `senior_target_sectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socioeconomic_statuses`
--
ALTER TABLE `socioeconomic_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `target_sectors`
--
ALTER TABLE `target_sectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD CONSTRAINT `access_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD CONSTRAINT `admin_users_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`),
  ADD CONSTRAINT `admin_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`),
  ADD CONSTRAINT `admin_users_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `admin_users_ibfk_4` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`);

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `announcement_types` (`id`),
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `announcement_media`
--
ALTER TABLE `announcement_media`
  ADD CONSTRAINT `announcement_media_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`application_type_id`) REFERENCES `application_types` (`id`),
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`submitted_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `applications_ibfk_4` FOREIGN KEY (`verified_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `applications_ibfk_5` FOREIGN KEY (`approved_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `applications_ibfk_6` FOREIGN KEY (`printed_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `applications_ibfk_7` FOREIGN KEY (`claimed_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_documents_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`),
  ADD CONSTRAINT `application_documents_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `branch_barangays`
--
ALTER TABLE `branch_barangays`
  ADD CONSTRAINT `branch_barangays_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_barangays_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`complainant_id`) REFERENCES `senior_citizens` (`id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`),
  ADD CONSTRAINT `complaints_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `complaint_statuses` (`id`),
  ADD CONSTRAINT `complaints_ibfk_4` FOREIGN KEY (`filed_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `complaints_ibfk_5` FOREIGN KEY (`assigned_to`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `complaint_documents`
--
ALTER TABLE `complaint_documents`
  ADD CONSTRAINT `complaint_documents_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`);

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`),
  ADD CONSTRAINT `event_participants_ibfk_3` FOREIGN KEY (`claimed_by_admin`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);

--
-- Constraints for table `senior_citizens`
--
ALTER TABLE `senior_citizens`
  ADD CONSTRAINT `senior_citizens_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_3` FOREIGN KEY (`educational_attainment_id`) REFERENCES `educational_attainment` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_4` FOREIGN KEY (`socioeconomic_status_id`) REFERENCES `socioeconomic_statuses` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_5` FOREIGN KEY (`mobility_level_id`) REFERENCES `mobility_levels` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_6` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_7` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_8` FOREIGN KEY (`registration_status_id`) REFERENCES `registration_statuses` (`id`),
  ADD CONSTRAINT `senior_citizens_ibfk_9` FOREIGN KEY (`registered_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `senior_ids`
--
ALTER TABLE `senior_ids`
  ADD CONSTRAINT `senior_ids_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`),
  ADD CONSTRAINT `senior_ids_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`),
  ADD CONSTRAINT `senior_ids_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `id_statuses` (`id`),
  ADD CONSTRAINT `senior_ids_ibfk_4` FOREIGN KEY (`printed_by`) REFERENCES `admin_users` (`id`),
  ADD CONSTRAINT `senior_ids_ibfk_5` FOREIGN KEY (`released_by`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `senior_target_sectors`
--
ALTER TABLE `senior_target_sectors`
  ADD CONSTRAINT `senior_target_sectors_ibfk_1` FOREIGN KEY (`senior_id`) REFERENCES `senior_citizens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `senior_target_sectors_ibfk_2` FOREIGN KEY (`sector_id`) REFERENCES `target_sectors` (`id`);

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
