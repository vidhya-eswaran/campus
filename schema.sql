-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 06:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `karthik`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT '',
  `date` varchar(30) DEFAULT NULL,
  `group_no` varchar(100) DEFAULT NULL,
  `second_language` varchar(100) DEFAULT NULL,
  `admission_for_class` varchar(40) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(200) DEFAULT NULL,
  `guardian_occupation` varchar(200) DEFAULT NULL,
  `annual_income` varchar(200) DEFAULT NULL,
  `father_contact_no` bigint(20) DEFAULT NULL,
  `mother_contact_no` bigint(20) DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pincode` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `admission_photo` varchar(255) DEFAULT NULL,
  `birth_certificate_photo` varchar(200) DEFAULT NULL,
  `aadhar_card_photo` varchar(200) DEFAULT NULL,
  `ration_card_photo` varchar(200) DEFAULT NULL,
  `community_certificate_photo` varchar(200) DEFAULT NULL,
  `slip_photo` varchar(200) DEFAULT NULL,
  `medical_certificate_photo` varchar(200) DEFAULT NULL,
  `reference_letter_photo` varchar(200) DEFAULT NULL,
  `church_certificate_photo` varchar(200) DEFAULT NULL,
  `transfer_certificate_photo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_live`
--

CREATE TABLE `admission_live` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_form` varchar(255) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `state_student` varchar(100) DEFAULT NULL,
  `date_of_birth` varchar(100) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `church_denomination` varchar(255) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `caste_type` varchar(255) DEFAULT NULL,
  `aadhar_card_no` varchar(255) DEFAULT NULL,
  `ration_card_no` varchar(255) DEFAULT NULL,
  `emis_no` varchar(255) DEFAULT NULL,
  `veg_or_non` varchar(255) DEFAULT NULL,
  `chronic_des` varchar(255) DEFAULT NULL,
  `medicine_taken` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_occupation` varchar(255) DEFAULT NULL,
  `father_contact_no` varchar(255) DEFAULT NULL,
  `father_email_id` varchar(255) DEFAULT NULL,
  `mother_contact_no` varchar(255) DEFAULT NULL,
  `mother_email_id` varchar(255) DEFAULT NULL,
  `guardian_contact_no` varchar(255) DEFAULT NULL,
  `guardian_email_id` varchar(255) DEFAULT NULL,
  `father_income` varchar(100) DEFAULT NULL,
  `mother_income` varchar(100) DEFAULT NULL,
  `guardian_income` varchar(100) DEFAULT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `house_no_1` varchar(255) DEFAULT NULL,
  `street_1` varchar(255) DEFAULT NULL,
  `city_1` varchar(255) DEFAULT NULL,
  `district_1` varchar(255) DEFAULT NULL,
  `state_1` varchar(100) DEFAULT NULL,
  `pincode_1` varchar(255) DEFAULT NULL,
  `last_class_std` varchar(255) DEFAULT NULL,
  `last_school` varchar(255) DEFAULT NULL,
  `admission_for_class` varchar(255) DEFAULT NULL,
  `syllabus` varchar(255) DEFAULT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `second_group_no` varchar(255) DEFAULT NULL,
  `second_language` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `birth_certificate_photo` varchar(200) DEFAULT NULL,
  `aadhar_card_photo` varchar(200) DEFAULT NULL,
  `ration_card_photo` varchar(200) DEFAULT NULL,
  `community_certificate` varchar(200) DEFAULT NULL,
  `slip_photo` varchar(200) DEFAULT NULL,
  `medical_certificate_photo` varchar(200) DEFAULT NULL,
  `reference_letter_photo` varchar(200) DEFAULT NULL,
  `church_certificate_photo` varchar(200) DEFAULT NULL,
  `transfer_certificate_photo` varchar(200) DEFAULT NULL,
  `admission_photo` varchar(255) DEFAULT NULL,
  `payment_order_id` varchar(200) DEFAULT NULL,
  `brother_1` varchar(200) DEFAULT NULL,
  `brother_2` varchar(200) DEFAULT NULL,
  `gender_1` varchar(200) DEFAULT NULL,
  `gender_2` varchar(200) DEFAULT NULL,
  `class_1` varchar(200) DEFAULT NULL,
  `class_2` varchar(200) DEFAULT NULL,
  `brother_3` varchar(100) DEFAULT NULL,
  `gender_3` varchar(100) DEFAULT NULL,
  `class_3` varchar(100) DEFAULT NULL,
  `last_school_state` varchar(100) DEFAULT NULL,
  `second_language_school` varchar(50) DEFAULT NULL,
  `reference_name_1` varchar(100) DEFAULT NULL,
  `reference_name_2` varchar(100) DEFAULT NULL,
  `reference_phone_1` varchar(100) DEFAULT NULL,
  `reference_phone_2` varchar(100) DEFAULT NULL,
  `father_organization` varchar(50) DEFAULT NULL,
  `mother_organization` varchar(50) DEFAULT NULL,
  `guardian_organization` varchar(50) DEFAULT NULL,
  `pin_no` varchar(20) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_live_demo`
--

CREATE TABLE `admission_live_demo` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_form` varchar(255) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `state_student` varchar(100) DEFAULT NULL,
  `date_of_birth` varchar(100) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `church_denomination` varchar(255) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `caste_type` varchar(255) DEFAULT NULL,
  `aadhar_card_no` varchar(255) DEFAULT NULL,
  `ration_card_no` varchar(255) DEFAULT NULL,
  `emis_no` varchar(255) DEFAULT NULL,
  `veg_or_non` varchar(255) DEFAULT NULL,
  `chronic_des` varchar(255) DEFAULT NULL,
  `medicine_taken` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_occupation` varchar(255) DEFAULT NULL,
  `father_contact_no` varchar(255) DEFAULT NULL,
  `father_email_id` varchar(255) DEFAULT NULL,
  `mother_contact_no` varchar(255) DEFAULT NULL,
  `mother_email_id` varchar(255) DEFAULT NULL,
  `guardian_contact_no` varchar(255) DEFAULT NULL,
  `guardian_email_id` varchar(255) DEFAULT NULL,
  `father_income` varchar(100) DEFAULT NULL,
  `mother_income` varchar(100) DEFAULT NULL,
  `guardian_income` varchar(100) DEFAULT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `house_no_1` varchar(255) DEFAULT NULL,
  `street_1` varchar(255) DEFAULT NULL,
  `city_1` varchar(255) DEFAULT NULL,
  `district_1` varchar(255) DEFAULT NULL,
  `state_1` varchar(100) DEFAULT NULL,
  `pincode_1` varchar(255) DEFAULT NULL,
  `last_class_std` varchar(255) DEFAULT NULL,
  `last_school` varchar(255) DEFAULT NULL,
  `admission_for_class` varchar(255) DEFAULT NULL,
  `syllabus` varchar(255) DEFAULT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `second_group_no` varchar(255) DEFAULT NULL,
  `second_language` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `birth_certificate_photo` varchar(200) DEFAULT NULL,
  `aadhar_card_photo` varchar(200) DEFAULT NULL,
  `ration_card_photo` varchar(200) DEFAULT NULL,
  `community_certificate` varchar(200) DEFAULT NULL,
  `slip_photo` varchar(200) DEFAULT NULL,
  `medical_certificate_photo` varchar(200) DEFAULT NULL,
  `reference_letter_photo` varchar(200) DEFAULT NULL,
  `church_certificate_photo` varchar(200) DEFAULT NULL,
  `transfer_certificate_photo` varchar(200) DEFAULT NULL,
  `admission_photo` varchar(255) DEFAULT NULL,
  `payment_order_id` varchar(200) DEFAULT NULL,
  `brother_1` varchar(200) DEFAULT NULL,
  `brother_2` varchar(200) DEFAULT NULL,
  `gender_1` varchar(200) DEFAULT NULL,
  `gender_2` varchar(200) DEFAULT NULL,
  `class_1` varchar(200) DEFAULT NULL,
  `class_2` varchar(200) DEFAULT NULL,
  `brother_3` varchar(100) DEFAULT NULL,
  `gender_3` varchar(100) DEFAULT NULL,
  `class_3` varchar(100) DEFAULT NULL,
  `last_school_state` varchar(100) DEFAULT NULL,
  `second_language_school` varchar(50) DEFAULT NULL,
  `reference_name_1` varchar(100) DEFAULT NULL,
  `reference_name_2` varchar(100) DEFAULT NULL,
  `reference_phone_1` varchar(100) DEFAULT NULL,
  `reference_phone_2` varchar(100) DEFAULT NULL,
  `father_organization` varchar(50) DEFAULT NULL,
  `mother_organization` varchar(50) DEFAULT NULL,
  `guardian_organization` varchar(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_process`
--

CREATE TABLE `admission_process` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT '',
  `date` varchar(30) DEFAULT NULL,
  `group_no` varchar(100) DEFAULT NULL,
  `second_language` varchar(100) DEFAULT NULL,
  `admission_for_class` varchar(40) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `father_contact_no` bigint(20) DEFAULT NULL,
  `mother_contact_no` bigint(20) DEFAULT NULL,
  `guardian_name` varchar(200) DEFAULT NULL,
  `guardian_occupation` varchar(200) DEFAULT NULL,
  `annual_income` varchar(200) DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pincode` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `birth_certificate_photo` varchar(200) DEFAULT NULL,
  `aadhar_card_photo` varchar(200) DEFAULT NULL,
  `ration_card_photo` varchar(200) DEFAULT NULL,
  `community_certificate` varchar(200) DEFAULT NULL,
  `slip_photo` varchar(200) DEFAULT NULL,
  `medical_certificate_photo` varchar(200) DEFAULT NULL,
  `reference_letter_photo` varchar(200) DEFAULT NULL,
  `church_certificate_photo` varchar(200) DEFAULT NULL,
  `transfer_certificate_photo` varchar(200) DEFAULT NULL,
  `admission_photo` varchar(255) DEFAULT NULL,
  `payment_order_id` varchar(200) DEFAULT NULL,
  `brother_1` varchar(200) DEFAULT NULL,
  `gender_1` varchar(200) DEFAULT NULL,
  `class_1` varchar(200) DEFAULT NULL,
  `brother_2` varchar(200) DEFAULT NULL,
  `gender_2` varchar(200) DEFAULT NULL,
  `class_2` varchar(200) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_process_live`
--

CREATE TABLE `admission_process_live` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `application_date` varchar(255) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `state_student` varchar(100) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `denomination` varchar(255) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `caste_classification` varchar(255) DEFAULT NULL,
  `aadhar_card_no` varchar(255) DEFAULT NULL,
  `ration_card_no` varchar(255) DEFAULT NULL,
  `emis_no` varchar(255) DEFAULT NULL,
  `food_choice` varchar(255) DEFAULT NULL,
  `chronic_des` varchar(255) DEFAULT NULL,
  `medicine_taken` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_occupation` varchar(255) DEFAULT NULL,
  `father_mobile_no` varchar(255) DEFAULT NULL,
  `father_email_id` varchar(255) DEFAULT NULL,
  `mother_mobile_no` varchar(255) DEFAULT NULL,
  `mother_email_id` varchar(255) DEFAULT NULL,
  `guardian_mobile_no` varchar(255) DEFAULT NULL,
  `guardian_email_id` varchar(255) DEFAULT NULL,
  `father_annual_income` varchar(100) DEFAULT NULL,
  `mother_annual_income` varchar(100) DEFAULT NULL,
  `guardian_annual_income` varchar(100) DEFAULT NULL,
  `permanent_house_no` varchar(255) DEFAULT NULL,
  `permanent_street_name` varchar(255) DEFAULT NULL,
  `permanent_city_town_village` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `permanent_district` varchar(100) DEFAULT NULL,
  `permanent_pincode` varchar(255) DEFAULT NULL,
  `communication_house_no` varchar(255) DEFAULT NULL,
  `communication_street_name` varchar(255) DEFAULT NULL,
  `communication_city_town_village` varchar(255) DEFAULT NULL,
  `communication_district` varchar(255) DEFAULT NULL,
  `communication_state` varchar(100) DEFAULT NULL,
  `communication_pincode` varchar(255) DEFAULT NULL,
  `class_last_studied` varchar(255) DEFAULT NULL,
  `last_school_name` varchar(255) DEFAULT NULL,
  `admission_for_class` varchar(255) DEFAULT NULL,
  `syllabus` varchar(255) DEFAULT NULL,
  `group_first_choice` varchar(255) DEFAULT NULL,
  `group_second_choice` varchar(255) DEFAULT NULL,
  `second_language` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `birth_certificate_image` varchar(200) DEFAULT NULL,
  `aadhar_image` varchar(200) DEFAULT NULL,
  `ration_card_image` varchar(200) DEFAULT NULL,
  `community_image` varchar(200) DEFAULT NULL,
  `salary_image` varchar(200) DEFAULT NULL,
  `medical_certificate_image` varchar(200) DEFAULT NULL,
  `reference_letter_image` varchar(200) DEFAULT NULL,
  `church_certificate_photo` varchar(200) DEFAULT NULL,
  `transfer_certificate_image` varchar(200) DEFAULT NULL,
  `admission_photo` varchar(255) DEFAULT NULL,
  `payment_order_id` varchar(200) DEFAULT NULL,
  `sibling_1` varchar(200) DEFAULT NULL,
  `sibling_2` varchar(200) DEFAULT NULL,
  `gender_1` varchar(200) DEFAULT NULL,
  `gender_2` varchar(200) DEFAULT NULL,
  `class_1` varchar(200) DEFAULT NULL,
  `class_2` varchar(200) DEFAULT NULL,
  `sibling_3` varchar(100) DEFAULT NULL,
  `gender_3` varchar(100) DEFAULT NULL,
  `class_3` varchar(100) DEFAULT NULL,
  `last_school_state` varchar(100) DEFAULT NULL,
  `second_language_school` varchar(50) DEFAULT NULL,
  `reference_name_1` varchar(100) DEFAULT NULL,
  `reference_name_2` varchar(100) DEFAULT NULL,
  `reference_mobile_1` varchar(100) DEFAULT NULL,
  `reference_mobile_2` varchar(100) DEFAULT NULL,
  `father_organization` varchar(50) DEFAULT NULL,
  `mother_organization` varchar(50) DEFAULT NULL,
  `guardian_organization` varchar(50) DEFAULT NULL,
  `pin_no` varchar(20) DEFAULT NULL,
  `payment_mode` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `student_status` varchar(10) DEFAULT NULL,
  `father_title` varchar(10) DEFAULT NULL,
  `mother_title` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admitted_students`
--

CREATE TABLE `admitted_students` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(220) DEFAULT NULL,
  `admission_no` varchar(220) DEFAULT NULL,
  `student_name` varchar(220) DEFAULT NULL,
  `date_form` varchar(100) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `nationality` varchar(220) DEFAULT NULL,
  `religion` varchar(220) DEFAULT NULL,
  `denomination` varchar(220) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `caste_classification` varchar(220) DEFAULT NULL,
  `aadhar_card_no` varchar(220) DEFAULT NULL,
  `ration_card_no` varchar(220) DEFAULT NULL,
  `emis_no` varchar(220) DEFAULT NULL,
  `food_choice` varchar(220) DEFAULT NULL,
  `chronic_des` varchar(220) DEFAULT NULL,
  `medicine_taken` varchar(220) DEFAULT NULL,
  `father_name` varchar(220) DEFAULT NULL,
  `father_occupation` varchar(220) DEFAULT NULL,
  `mother_name` varchar(220) DEFAULT NULL,
  `mother_occupation` varchar(220) DEFAULT NULL,
  `guardian_name` varchar(220) DEFAULT NULL,
  `guardian_occupation` varchar(220) DEFAULT NULL,
  `father_mobile_no` varchar(220) DEFAULT NULL,
  `father_email_id` varchar(220) DEFAULT NULL,
  `mother_mobile_no` varchar(220) DEFAULT NULL,
  `mother_email_id` varchar(220) DEFAULT NULL,
  `guardian_mobile_no` varchar(220) DEFAULT NULL,
  `guardian_email_id` varchar(220) DEFAULT NULL,
  `father_annual_income` varchar(90) DEFAULT NULL,
  `mother_annual_income` varchar(220) DEFAULT NULL,
  `guardian_annual_income` varchar(220) DEFAULT NULL,
  `permanent_house_no` varchar(220) DEFAULT NULL,
  `permanent_street_name` varchar(220) DEFAULT NULL,
  `permanent_city_town_village` varchar(220) DEFAULT NULL,
  `permanent_district` varchar(220) DEFAULT NULL,
  `permanent_state` varchar(220) DEFAULT NULL,
  `permanent_pincode` varchar(220) DEFAULT NULL,
  `communication_house_no` varchar(100) DEFAULT NULL,
  `communication_street_name` varchar(220) DEFAULT NULL,
  `communication_city_town_village` varchar(220) DEFAULT NULL,
  `communication_district` varchar(220) DEFAULT NULL,
  `communication_state` varchar(220) DEFAULT NULL,
  `communication_pincode` varchar(220) DEFAULT NULL,
  `class_last_studied` varchar(220) DEFAULT NULL,
  `last_school_name` varchar(220) DEFAULT NULL,
  `std_sought` varchar(220) DEFAULT NULL,
  `sec` varchar(220) DEFAULT NULL,
  `syllabus` varchar(220) DEFAULT NULL,
  `group_first_choice` varchar(220) DEFAULT NULL,
  `group_second_choice` varchar(100) DEFAULT NULL,
  `language` varchar(220) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT NULL,
  `birth_certificate_image` varchar(90) DEFAULT NULL,
  `aadhar_image` varchar(90) DEFAULT NULL,
  `ration_card_image` varchar(220) DEFAULT NULL,
  `community_image` varchar(90) DEFAULT NULL,
  `salary_image` varchar(220) DEFAULT NULL,
  `medical_certificate_image` varchar(90) DEFAULT NULL,
  `reference_letter_image` varchar(90) DEFAULT NULL,
  `church_certificate_photo` varchar(90) DEFAULT NULL,
  `transfer_certificate_image` varchar(90) DEFAULT NULL,
  `migration_image` varchar(255) DEFAULT NULL,
  `church_endorsement_image` varchar(255) DEFAULT NULL,
  `admission_photo` varchar(90) DEFAULT NULL,
  `payment_order_id` varchar(90) DEFAULT NULL,
  `sibling_1` varchar(100) DEFAULT NULL,
  `sibling_2` varchar(100) DEFAULT NULL,
  `gender_1` varchar(90) DEFAULT NULL,
  `gender_2` varchar(90) DEFAULT NULL,
  `class_1` varchar(90) DEFAULT NULL,
  `class_2` varchar(90) DEFAULT NULL,
  `sibling_3` varchar(90) DEFAULT NULL,
  `gender_3` varchar(90) DEFAULT NULL,
  `class_3` varchar(90) DEFAULT NULL,
  `last_school_state` varchar(90) DEFAULT NULL,
  `second_language_school` varchar(90) DEFAULT NULL,
  `reference_name_1` varchar(90) DEFAULT NULL,
  `reference_name_2` varchar(90) DEFAULT NULL,
  `reference_phone_1` varchar(90) DEFAULT NULL,
  `reference_phone_2` varchar(90) DEFAULT NULL,
  `father_organization` varchar(220) DEFAULT NULL,
  `mother_organization` varchar(220) DEFAULT NULL,
  `guardian_organization` varchar(220) DEFAULT NULL,
  `pin_no` varchar(20) DEFAULT NULL,
  `created_at` varchar(90) DEFAULT NULL,
  `updated_at` varchar(90) DEFAULT NULL,
  `documents` varchar(220) DEFAULT NULL,
  `upload_created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `upload_updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `academic_year` varchar(100) DEFAULT NULL,
  `grade_status` varchar(100) DEFAULT NULL,
  `group_no` int(11) DEFAULT NULL,
  `siblings` varchar(10) DEFAULT NULL,
  `second_language` varchar(10) DEFAULT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `father_title` varchar(10) DEFAULT NULL,
  `mother_title` varchar(10) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `guardian_title` varchar(20) DEFAULT NULL,
  `pen_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admitted_students_history`
--

CREATE TABLE `admitted_students_history` (
  `id` int(11) NOT NULL,
  `original_id` varchar(220) DEFAULT NULL,
  `roll_no` varchar(220) DEFAULT NULL,
  `admission_no` varchar(220) DEFAULT NULL,
  `student_name` varchar(220) DEFAULT NULL,
  `date_form` varchar(220) DEFAULT NULL,
  `mother_tongue` varchar(220) DEFAULT NULL,
  `state` varchar(220) DEFAULT NULL,
  `dob` varchar(220) DEFAULT NULL,
  `gender` varchar(220) DEFAULT NULL,
  `blood_group` varchar(220) DEFAULT NULL,
  `nationality` varchar(220) DEFAULT NULL,
  `religion` varchar(220) DEFAULT NULL,
  `denomination` varchar(220) DEFAULT NULL,
  `caste` varchar(220) DEFAULT NULL,
  `caste_classification` varchar(220) DEFAULT NULL,
  `aadhar_card_no` varchar(220) DEFAULT NULL,
  `ration_card_no` varchar(220) DEFAULT NULL,
  `emis_no` varchar(220) DEFAULT NULL,
  `food_choice` varchar(220) DEFAULT NULL,
  `chronic_des` varchar(220) DEFAULT NULL,
  `medicine_taken` varchar(220) DEFAULT NULL,
  `father_name` varchar(220) DEFAULT NULL,
  `father_occupation` varchar(220) DEFAULT NULL,
  `mother_name` varchar(220) DEFAULT NULL,
  `mother_occupation` varchar(220) DEFAULT NULL,
  `guardian_name` varchar(220) DEFAULT NULL,
  `guardian_occupation` varchar(220) DEFAULT NULL,
  `father_mobile_no` varchar(220) DEFAULT NULL,
  `father_email_id` varchar(220) DEFAULT NULL,
  `mother_mobile_no` varchar(220) DEFAULT NULL,
  `mother_email_id` varchar(220) DEFAULT NULL,
  `guardian_mobile_no` varchar(220) DEFAULT NULL,
  `guardian_email_id` varchar(220) DEFAULT NULL,
  `father_annual_income` varchar(220) DEFAULT NULL,
  `mother_annual_income` varchar(220) DEFAULT NULL,
  `guardian_annual_income` varchar(220) DEFAULT NULL,
  `permanent_house_no` varchar(220) DEFAULT NULL,
  `permanent_street_name` varchar(220) DEFAULT NULL,
  `permanent_city_town_village` varchar(220) DEFAULT NULL,
  `permanent_district` varchar(220) DEFAULT NULL,
  `permanent_state` varchar(220) DEFAULT NULL,
  `permanent_pincode` varchar(220) DEFAULT NULL,
  `communication_house_no` varchar(220) DEFAULT NULL,
  `communication_street_name` varchar(220) DEFAULT NULL,
  `communication_city_town_village` varchar(90) DEFAULT NULL,
  `communication_district` varchar(90) DEFAULT NULL,
  `communication_state` varchar(90) DEFAULT NULL,
  `communication_pincode` varchar(90) DEFAULT NULL,
  `class_last_studied` varchar(220) DEFAULT NULL,
  `last_school_name` varchar(220) DEFAULT NULL,
  `std_sought` varchar(220) DEFAULT NULL,
  `sec` varchar(220) DEFAULT NULL,
  `syllabus` varchar(220) DEFAULT NULL,
  `group_first_choice` varchar(90) DEFAULT NULL,
  `group_second_choice` varchar(220) DEFAULT NULL,
  `language` varchar(220) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT NULL,
  `birth_certificate_image` varchar(90) DEFAULT NULL,
  `aadhar_image` varchar(90) DEFAULT NULL,
  `ration_card_image` varchar(90) DEFAULT NULL,
  `community_image` varchar(90) DEFAULT NULL,
  `salary_image` varchar(90) DEFAULT NULL,
  `medical_certificate_image` varchar(90) DEFAULT NULL,
  `reference_letter_image` varchar(90) DEFAULT NULL,
  `church_certificate_photo` varchar(90) DEFAULT NULL,
  `transfer_certificate_image` varchar(90) DEFAULT NULL,
  `admission_photo` varchar(220) DEFAULT NULL,
  `payment_order_id` varchar(220) DEFAULT NULL,
  `sibling_1` varchar(90) DEFAULT NULL,
  `sibling_2` varchar(90) DEFAULT NULL,
  `gender_1` varchar(90) DEFAULT NULL,
  `gender_2` varchar(90) DEFAULT NULL,
  `class_1` varchar(90) DEFAULT NULL,
  `class_2` varchar(90) DEFAULT NULL,
  `sibling_3` varchar(90) DEFAULT NULL,
  `gender_3` varchar(90) DEFAULT NULL,
  `class_3` varchar(90) DEFAULT NULL,
  `last_school_state` varchar(220) DEFAULT NULL,
  `second_language` varchar(220) DEFAULT NULL,
  `reference_name_1` varchar(220) DEFAULT NULL,
  `reference_name_2` varchar(220) DEFAULT NULL,
  `reference_phone_1` varchar(90) DEFAULT NULL,
  `reference_phone_2` varchar(90) DEFAULT NULL,
  `father_organization` varchar(220) DEFAULT NULL,
  `mother_organization` varchar(220) DEFAULT NULL,
  `guardian_organization` varchar(220) DEFAULT NULL,
  `created_at` varchar(90) DEFAULT NULL,
  `updated_at` varchar(90) DEFAULT NULL,
  `documents` varchar(220) DEFAULT NULL,
  `upload_created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `upload_updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `target_type` enum('target','role','class') NOT NULL,
  `target` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `announcementDescription` text NOT NULL,
  `announcementType` tinyint(1) NOT NULL DEFAULT 0,
  `announcementDate` date NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_sponser_payments`
--

CREATE TABLE `bulk_sponser_payments` (
  `id` int(11) NOT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`request_data`)),
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `by_pay_informations`
--

CREATE TABLE `by_pay_informations` (
  `id` int(11) NOT NULL,
  `student_id` varchar(121) NOT NULL,
  `invoice_id` varchar(121) NOT NULL,
  `transactionId` varchar(121) NOT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `sponsor` varchar(121) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `inv_amt` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `due_amount` varchar(100) DEFAULT NULL,
  `payment_status` varchar(100) NOT NULL,
  `additional_details` varchar(100) DEFAULT NULL,
  `mode` varchar(100) DEFAULT NULL,
  `s_excess_amount` varchar(100) NOT NULL,
  `h_excess_amount` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_masters`
--

CREATE TABLE `class_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `group_no` varchar(50) DEFAULT NULL,
  `term` varchar(50) DEFAULT NULL,
  `acad_year` year(4) DEFAULT NULL,
  `mark` int(11) DEFAULT NULL,
  `sec` varchar(10) DEFAULT NULL,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_mappings`
--

CREATE TABLE `class_subject_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class` varchar(255) NOT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `subjects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`subjects`)),
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_teachers`
--

CREATE TABLE `class_teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_teacher` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`class_teacher`)),
  `std_and_sub_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`std_and_sub_details`)),
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `contact_type` varchar(50) NOT NULL,
  `delete_status` int(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deletedreceipts`
--

CREATE TABLE `deletedreceipts` (
  `id` int(11) NOT NULL,
  `user_uuid` varchar(255) DEFAULT NULL,
  `invoice_id` varchar(255) DEFAULT NULL,
  `payment_transaction_id` varchar(255) DEFAULT NULL,
  `transaction_amount` varchar(255) DEFAULT NULL,
  `balance_amount` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `transaction_completed_status` varchar(255) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_category_masters`
--

CREATE TABLE `discount_category_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_name` varchar(255) NOT NULL,
  `feestype` varchar(200) DEFAULT NULL,
  `amount` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_lists`
--

CREATE TABLE `discount_lists` (
  `id` int(11) NOT NULL,
  `discount_heading` varchar(150) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `student` varchar(255) NOT NULL,
  `fees_cat` varchar(255) NOT NULL,
  `end_date` varchar(100) NOT NULL,
  `status` int(15) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donar_list`
--

CREATE TABLE `donar_list` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `address_line_1` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `address_line_2` text DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `donor_id` varchar(255) DEFAULT NULL,
  `city_2` varchar(100) DEFAULT NULL,
  `state_2` varchar(100) DEFAULT NULL,
  `country_2` varchar(100) DEFAULT NULL,
  `pincode_2` varchar(20) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `typeOfDonation` varchar(100) DEFAULT NULL,
  `check_dd_trans_id` varchar(255) DEFAULT NULL,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `mode_of_payment` varchar(50) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_donar_list`
--

CREATE TABLE `donation_donar_list` (
  `id` int(100) NOT NULL,
  `donor_name` varchar(50) DEFAULT NULL,
  `donor_id` varchar(20) DEFAULT NULL,
  `typeOfDonation` varchar(200) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `amount` varchar(60) DEFAULT NULL,
  `mode_of_payment` varchar(60) DEFAULT NULL,
  `payment_type` varchar(60) DEFAULT NULL,
  `check_dd_trans_id` varchar(60) DEFAULT NULL,
  `address_line_1` varchar(100) DEFAULT NULL,
  `address_line_2` varchar(100) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `pincode` varchar(30) DEFAULT NULL,
  `pan_no` varchar(30) DEFAULT NULL,
  `invoice_pdf` varchar(30) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_list`
--

CREATE TABLE `donation_list` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `main_description` text NOT NULL,
  `short_description` text NOT NULL,
  `range_slide` varchar(200) DEFAULT NULL,
  `btn_amt_1` decimal(10,2) NOT NULL,
  `btn_amt_2` decimal(10,2) NOT NULL,
  `btn_amt_3` decimal(10,2) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `delete_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_statement`
--

CREATE TABLE `donation_statement` (
  `id` int(11) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `donar_id` int(11) NOT NULL,
  `donar_name` varchar(255) NOT NULL,
  `donation_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transection_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_statement_trans`
--

CREATE TABLE `donation_statement_trans` (
  `id` int(11) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `donar_id` int(11) NOT NULL,
  `donar_name` varchar(255) NOT NULL,
  `donation_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `transection_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dropdown_types`
--

CREATE TABLE `dropdown_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_calendars`
--

CREATE TABLE `event_calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` bigint(20) UNSIGNED NOT NULL,
  `isStart` varchar(255) NOT NULL,
  `isEnd` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_category_masters`
--

CREATE TABLE `event_category_masters` (
  `id` int(10) UNSIGNED NOT NULL,
  `eventCategory` varchar(255) NOT NULL,
  `eventColor` varchar(7) NOT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_masters`
--

CREATE TABLE `exam_masters` (
  `id` int(10) UNSIGNED NOT NULL,
   `term` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `mark` varchar(255) DEFAULT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `acad_year` varchar(255) DEFAULT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_maps`
--

CREATE TABLE `fees_maps` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `standard` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `amount` varchar(255) NOT NULL,
  `fees_heading` varchar(255) NOT NULL,
  `fees_sub_heading` varchar(255) NOT NULL,
  `due_date` varchar(255) NOT NULL,
  `acad_year` varchar(30) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1',
  `Priority` varchar(111) DEFAULT NULL,
  `invoice_generated` int(11) DEFAULT 0,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Fee_Category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_map_arrays`
--

CREATE TABLE `fee_map_arrays` (
  `id` int(11) NOT NULL,
  `std` varchar(111) DEFAULT NULL,
  `Checked` varchar(100) DEFAULT NULL,
  `Fee_Category` varchar(255) DEFAULT NULL,
  `Sub_Fees` longtext DEFAULT NULL,
  `Amount` varchar(255) DEFAULT NULL,
  `Acad_Year` varchar(255) DEFAULT NULL,
  `Priority` varchar(100) DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generate_invoice_views`
--

CREATE TABLE `generate_invoice_views` (
  `slno` int(255) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `student_id` int(255) NOT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `sec` varchar(50) DEFAULT NULL,
  `hostelOrDay` varchar(50) DEFAULT NULL,
  `sponser_id` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `standard` varchar(55) DEFAULT NULL,
  `twe_group` varchar(100) DEFAULT NULL,
  `actual_amount` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `previous_pending_amount` decimal(12,2) DEFAULT 0.00,
  `total_invoice_amount` decimal(12,2) DEFAULT NULL,
  `discount_percent` int(70) DEFAULT NULL,
  `fees_glance` longtext DEFAULT NULL,
  `fees_cat` varchar(255) DEFAULT NULL,
  `fees_items_details` longtext DEFAULT NULL,
  `discount_items_details` longtext DEFAULT NULL,
  `date` varchar(66) DEFAULT NULL,
  `acad_year` varchar(66) DEFAULT NULL,
  `due_date` varchar(66) DEFAULT NULL,
  `cash_amount` varchar(255) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `invoice_pending_amount` decimal(12,2) DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT '0',
  `invoice_status` varchar(70) DEFAULT '1',
  `additionalDetails` varchar(255) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `created_by` varchar(33) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  `disable` int(11) DEFAULT NULL,
  `due_amount` int(11) DEFAULT NULL,
  `s_excess_amount` int(11) DEFAULT NULL,
  `h_excess_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_masters`
--

CREATE TABLE `group_masters` (
  `id` int(10) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `healthcare_records`
--

CREATE TABLE `healthcare_records` (
  `id` int(11) NOT NULL,
 `student_id` int(255) NOT NULL,
  `admission_no` varchar(100) NOT NULL,
  `hostel_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) NOT NULL,
  `father_number` bigint(11) DEFAULT NULL,
  `mother_name` varchar(100) NOT NULL,
  `mother_number` bigint(11) DEFAULT NULL,
  `nature_of_sickness` varchar(100) DEFAULT NULL,
  `treatment_type` varchar(100) NOT NULL,
  `from_date` varchar(100) NOT NULL,
  `to_date` varchar(100) NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `delete_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_student_infos`
--

CREATE TABLE `history_student_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admission_no` varchar(255) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `sex` varchar(50) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `emis_no` varchar(255) DEFAULT NULL,
  `Nationality` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `Religion` varchar(255) DEFAULT NULL,
  `Denomination` varchar(255) DEFAULT NULL,
  `Caste` varchar(50) DEFAULT NULL,
  `CasteClassification` varchar(255) DEFAULT NULL,
  `AadhaarCardNo` varchar(50) DEFAULT NULL,
  `RationCard` varchar(255) DEFAULT NULL,
  `Mothertongue` varchar(255) DEFAULT NULL,
  `Father` varchar(255) DEFAULT NULL,
  `Mother` varchar(255) DEFAULT NULL,
  `Guardian` varchar(255) DEFAULT NULL,
  `Occupation` varchar(255) DEFAULT NULL,
  `Organisation` varchar(255) DEFAULT NULL,
  `Monthlyincome` varchar(255) DEFAULT NULL,
  `p_housenumber` varchar(50) DEFAULT NULL,
  `p_Streetname` varchar(255) DEFAULT NULL,
  `p_VillagetownName` varchar(255) DEFAULT NULL,
  `p_Postoffice` varchar(255) DEFAULT NULL,
  `p_Taluk` varchar(255) DEFAULT NULL,
  `p_District` varchar(255) DEFAULT NULL,
  `p_State` varchar(255) DEFAULT NULL,
  `p_Pincode` varchar(50) DEFAULT NULL,
  `c_HouseNumber` varchar(50) DEFAULT NULL,
  `c_StreetName` varchar(255) DEFAULT NULL,
  `c_VillageTownName` varchar(255) DEFAULT NULL,
  `c_Postoffice` varchar(255) DEFAULT NULL,
  `c_Taluk` varchar(70) DEFAULT NULL,
  `c_District` varchar(255) DEFAULT NULL,
  `c_State` varchar(255) DEFAULT NULL,
  `c_Pincode` varchar(50) DEFAULT NULL,
  `Mobilenumber` varchar(255) DEFAULT NULL,
  `WhatsAppNo` varchar(255) DEFAULT NULL,
  `ClasslastStudied` varchar(255) DEFAULT NULL,
  `EmailID` varchar(255) DEFAULT NULL,
  `Nameofschool` varchar(255) DEFAULT NULL,
  `File` varchar(255) DEFAULT NULL,
  `sought_Std` text DEFAULT NULL,
  `sec` varchar(50) DEFAULT NULL,
  `Part_I` text DEFAULT NULL,
  `Group` text DEFAULT NULL,
  `FOOD` text DEFAULT NULL,
  `hostelOrDay` varchar(100) DEFAULT NULL,
  `special_information` text DEFAULT NULL,
  `Declare_not_attended` text DEFAULT NULL,
  `Declare_dues` varchar(90) DEFAULT NULL,
  `Declare_dob` text DEFAULT NULL,
  `Declare_Date` text DEFAULT NULL,
  `Declare_Place` text DEFAULT NULL,
  `Measles` text DEFAULT NULL,
  `Chickenpox` text DEFAULT NULL,
  `Fits` text DEFAULT NULL,
  `Rheumaticfever` text DEFAULT NULL,
  `Mumps` text DEFAULT NULL,
  `Jaundice` text DEFAULT NULL,
  `Asthma` text DEFAULT NULL,
  `Nephritis` text DEFAULT NULL,
  `Whoopingcough` text DEFAULT NULL,
  `Tuberculosis` text DEFAULT NULL,
  `Hayfever` text DEFAULT NULL,
  `CongenitalHeartDisease` text DEFAULT NULL,
  `P_Bronchial` text DEFAULT NULL,
  `P_Tuberculosis` text DEFAULT NULL,
  `BCG` text DEFAULT NULL,
  `Triple_Vaccine` text DEFAULT NULL,
  `Polio_Drops` text DEFAULT NULL,
  `Measles_given` text DEFAULT NULL,
  `MMR` text DEFAULT NULL,
  `Dual_Vaccine` text DEFAULT NULL,
  `Typhoid` text DEFAULT NULL,
  `Cholera` text DEFAULT NULL,
  `permission_to_principal` text DEFAULT NULL,
  `administration_of_anaesthetic` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_admissions`
--

CREATE TABLE `hostel_admissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `student_class` varchar(100) DEFAULT NULL,
  `student_section` varchar(100) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `father_mobileNo` varchar(20) DEFAULT NULL,
  `mother_mobileNo` varchar(20) DEFAULT NULL,
  `pa_address_line1` varchar(255) DEFAULT NULL,
  `pa_address_line2` varchar(255) DEFAULT NULL,
  `pa_city` varchar(100) DEFAULT NULL,
  `pa_state` varchar(100) DEFAULT NULL,
  `pa_country` varchar(100) DEFAULT NULL,
  `pa_pincode` varchar(20) DEFAULT NULL,
  `co_address_line1` varchar(255) DEFAULT NULL,
  `co_address_line2` varchar(255) DEFAULT NULL,
  `co_city` varchar(100) DEFAULT NULL,
  `co_state` varchar(100) DEFAULT NULL,
  `co_country` varchar(100) DEFAULT NULL,
  `co_pincode` varchar(20) DEFAULT NULL,
  `gaurdian_name` varchar(255) DEFAULT NULL,
  `gaurdian_email_id` varchar(255) DEFAULT NULL,
  `father_email_id` varchar(255) DEFAULT NULL,
  `mother_email_id` varchar(255) DEFAULT NULL,
  `declaration` tinyint(1) DEFAULT NULL,
  `terms_condition` tinyint(1) DEFAULT NULL,
  `acad_year` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected','Approved','Rejected','Pending') DEFAULT 'pending',
  `arr_dep_status` tinyint(1) DEFAULT NULL COMMENT '1=Arrival, 2=Departure',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_fee_masters`
--

CREATE TABLE `hostel_fee_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_histories`
--

CREATE TABLE `invoice_histories` (
  `table_id` int(255) NOT NULL,
  `slno` int(255) NOT NULL,
  `student_id` int(255) NOT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `sec` varchar(50) DEFAULT NULL,
  `hostelOrDay` varchar(50) DEFAULT NULL,
  `sponser_id` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `standard` varchar(55) DEFAULT NULL,
  `twe_group` varchar(100) DEFAULT NULL,
  `amount` varchar(160) DEFAULT NULL,
  `fees_glance` longtext DEFAULT NULL,
  `date` varchar(66) DEFAULT NULL,
  `acad_year` varchar(66) DEFAULT NULL,
  `due_date` varchar(66) DEFAULT NULL,
  `cash_amount` varchar(255) DEFAULT NULL,
  `payment_status` varchar(30) DEFAULT NULL,
  `invoice_status` varchar(70) DEFAULT NULL,
  `created_by` varchar(33) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_lists`
--

CREATE TABLE `invoice_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_uuid` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `payment_transaction_id` varchar(255) NOT NULL,
  `transaction_amount` decimal(12,2) DEFAULT 0.00,
  `balance_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) DEFAULT NULL,
  `transaction_completed_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unique_payment_transaction_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_pendings`
--

CREATE TABLE `invoice_pendings` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `fees_cat` varchar(255) NOT NULL,
  `pending_amount` decimal(12,2) NOT NULL,
  `closed_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `studentName` varchar(255) NOT NULL,
  `studentId` int(11) NOT NULL,
  `rollNo` varchar(50) DEFAULT NULL,
  `class` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `fatherName` varchar(255) DEFAULT NULL,
  `motherName` varchar(255) DEFAULT NULL,
  `fromDate` date NOT NULL,
  `toDate` date DEFAULT NULL,
  `leaveDays` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lifecycle_logs`
--

CREATE TABLE `lifecycle_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logged_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `about` int(11) DEFAULT NULL,
  `replies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`replies`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(1024) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_category_master`
--

CREATE TABLE `message_category_master` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `messageCategory` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module_permissions`
--

CREATE TABLE `module_permissions` (
  `id` int(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `parent_id` int(255) DEFAULT NULL,
  `selected` varchar(255) DEFAULT NULL,
  `select_all` varchar(255) DEFAULT NULL,
  `view_permission` varchar(255) DEFAULT NULL,
  `create_permission` varchar(255) DEFAULT NULL,
  `edit_permission` varchar(255) DEFAULT NULL,
  `delete_permission` varchar(255) DEFAULT NULL,
  `menu_key` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice_boards`
--

CREATE TABLE `notice_boards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` int(11) NOT NULL,
  `notice_message` text NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_categories`
--

CREATE TABLE `notification_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `notification_category` varchar(255) NOT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_expenditure_masters`
--

CREATE TABLE `other_expenditure_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_admins`
--

CREATE TABLE `payment_gateway_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `merchantSchemeCode` varchar(255) NOT NULL,
  `typeOfPayment` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `primaryColor` varchar(255) NOT NULL,
  `secondaryColor` varchar(255) NOT NULL,
  `buttonColor1` varchar(255) NOT NULL,
  `buttonColor2` varchar(255) NOT NULL,
  `logoURL` varchar(255) NOT NULL,
  `enableExpressPay` tinyint(1) NOT NULL,
  `separateCardMode` tinyint(1) NOT NULL,
  `enableNewWindowFlow` tinyint(1) NOT NULL,
  `merchantMessage` varchar(255) NOT NULL,
  `disclaimerMessage` varchar(255) NOT NULL,
  `paymentMode` varchar(255) NOT NULL,
  `paymentModeOrder` varchar(255) NOT NULL,
  `enableInstrumentDeRegistration` tinyint(1) NOT NULL,
  `transactionType` varchar(255) NOT NULL,
  `hideSavedInstruments` tinyint(1) NOT NULL,
  `saveInstrument` tinyint(1) NOT NULL,
  `displayTransactionMessageOnPopup` tinyint(1) NOT NULL,
  `embedPaymentGatewayOnPage` tinyint(1) NOT NULL,
  `enableEmandate` tinyint(1) NOT NULL,
  `hideSIConfirmation` tinyint(1) NOT NULL,
  `expandSIDetails` tinyint(1) NOT NULL,
  `enableDebitDay` tinyint(1) NOT NULL,
  `showSIResponseMsg` tinyint(1) NOT NULL,
  `showSIConfirmation` tinyint(1) NOT NULL,
  `enableTxnForNonSICards` tinyint(1) NOT NULL,
  `showAllModesWithSI` tinyint(1) NOT NULL,
  `enableSIDetailsAtMerchantEnd` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_info`
--

CREATE TABLE `payment_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_mode_masters`
--

CREATE TABLE `payment_mode_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `paymenttype` varchar(255) NOT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_notification_datas`
--

CREATE TABLE `payment_notification_datas` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `txnId` varchar(255) NOT NULL,
  `paidAmount` varchar(255) NOT NULL,
  `invoice_nos` varchar(255) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `show_hide` int(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_orders_details`
--

CREATE TABLE `payment_orders_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `internal_txn_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'user id / Sponser id',
  `amount` decimal(12,2) NOT NULL COMMENT 'invoice total amount',
  `paymentMode` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'user name/ Card holder Name',
  `accNo` varchar(255) DEFAULT NULL COMMENT 'user account no',
  `custID` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'user id / sponser id /card holder id',
  `mobNo` varchar(255) DEFAULT NULL COMMENT 'user Mobile id',
  `email` varchar(255) DEFAULT NULL COMMENT 'user email id',
  `debitStartDate` date DEFAULT NULL COMMENT 'debit Start Date',
  `debitEndDate` date DEFAULT NULL COMMENT 'debit End Date',
  `maxAmount` decimal(8,2) DEFAULT NULL COMMENT 'This is max amout for tnx',
  `amountType` varchar(255) DEFAULT NULL COMMENT 'user ammount type',
  `currency` varchar(255) DEFAULT NULL COMMENT 'currency type from admin',
  `frequency` varchar(255) DEFAULT NULL COMMENT 'payment frequency',
  `cardNumber` varchar(255) DEFAULT NULL COMMENT 'user card number',
  `expMonth` varchar(255) DEFAULT NULL COMMENT 'user card exp month',
  `expYear` varchar(255) DEFAULT NULL COMMENT 'user card exp year',
  `cvvCode` varchar(255) DEFAULT NULL COMMENT 'user card cvv',
  `scheme` varchar(255) DEFAULT NULL COMMENT 'schem code from admin',
  `accountName` varchar(255) DEFAULT NULL COMMENT 'user account Name',
  `ifscCode` varchar(255) DEFAULT NULL COMMENT 'user ifscCode',
  `accountType` varchar(255) DEFAULT NULL COMMENT 'user account type',
  `payment_status` varchar(1024) DEFAULT NULL COMMENT 'user account type',
  `payment_code` varchar(1024) DEFAULT NULL COMMENT 'user account type',
  `order_hash_value` text DEFAULT NULL COMMENT 'order hashed value payment order',
  `user_return_Url` text DEFAULT NULL COMMENT 'url for user view return',
  `user_retrun_req_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'user data req for user return url' CHECK (json_valid(`user_retrun_req_data`)),
  `user_access_key` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'user access token can save here' CHECK (json_valid(`user_access_key`)),
  `updatedat` timestamp NULL DEFAULT NULL COMMENT 'update time stamp',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_orders_statuses`
--

CREATE TABLE `payment_orders_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `txn_status` varchar(255) DEFAULT NULL,
  `txn_msg` varchar(255) DEFAULT NULL,
  `txn_err_msg` varchar(255) DEFAULT NULL,
  `clnt_txn_ref` varchar(255) DEFAULT NULL,
  `tpsl_bank_cd` varchar(255) DEFAULT NULL,
  `tpsl_txn_id` varchar(255) DEFAULT NULL,
  `txn_amt` varchar(255) DEFAULT NULL,
  `clnt_rqst_meta` varchar(255) DEFAULT NULL,
  `tpsl_txn_time` varchar(255) DEFAULT NULL,
  `bal_amt` varchar(255) DEFAULT NULL,
  `card_id` varchar(255) DEFAULT NULL,
  `alias_name` varchar(255) DEFAULT NULL,
  `BankTransactionID` varchar(255) DEFAULT NULL,
  `mandate_reg_no` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `payment_gatway_response` text DEFAULT NULL,
  `dual_veri_merchantCode` varchar(255) DEFAULT NULL,
  `merchantTransactionIdentifier` varchar(255) DEFAULT NULL,
  `paymentModeBy` varchar(255) DEFAULT NULL,
  `dual_veri_statusCode` varchar(255) DEFAULT NULL,
  `dual_veri_statusMessage` varchar(255) DEFAULT NULL,
  `dual_veri_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dual_veri_response`)),
  `pay_res_updatedAt` timestamp NULL DEFAULT NULL,
  `dual_veri_updatedAt` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_req_data`
--

CREATE TABLE `payment_req_data` (
  `payment_req_id` int(11) NOT NULL,
  `payment_req_customerName` varchar(255) DEFAULT NULL,
  `payment_req_merchantCode` varchar(255) DEFAULT NULL,
  `payment_req_ITC` varchar(255) DEFAULT NULL,
  `payment_req_requestType` varchar(255) DEFAULT NULL,
  `payment_req_merchantTxnRefNumber` varchar(400) DEFAULT NULL,
  `payment_req_amount` decimal(12,2) DEFAULT NULL,
  `payment_req_currencyCode` varchar(55) DEFAULT NULL,
  `payment_req_returnURL` text DEFAULT NULL,
  `payment_req_shoppingCartDetails` varchar(255) DEFAULT NULL,
  `payment_req_TPSLTxnID` varchar(255) DEFAULT NULL,
  `payment_req_mobileNumber` varchar(255) DEFAULT NULL,
  `payment_req_txnDate` varchar(55) DEFAULT NULL,
  `payment_req_bankCode` varchar(55) DEFAULT NULL,
  `payment_req_custId` varchar(255) DEFAULT NULL,
  `payment_req_key` varchar(255) DEFAULT NULL,
  `payment_req_iv` varchar(255) DEFAULT NULL,
  `payment_req_accountNo` varchar(255) DEFAULT NULL,
  `payment_req_webServiceLocator_PHP_EOL` text DEFAULT NULL,
  `payment_req_date_time` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `receipt_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `send_to` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_fee_discounts`
--

CREATE TABLE `school_fee_discounts` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) DEFAULT NULL,
  `discount_cat` longtext DEFAULT NULL,
  `dis_amount` varchar(255) DEFAULT NULL,
  `invoicefeescat` varchar(255) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_fee_masters`
--

CREATE TABLE `school_fee_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_miscellaneous_bill_masters`
--

CREATE TABLE `school_miscellaneous_bill_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_masters`
--

CREATE TABLE `section_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seperate_fees_maps`
--

CREATE TABLE `seperate_fees_maps` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `standard` varchar(100) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `fees_heading` varchar(255) NOT NULL,
  `fees_sub_heading` varchar(255) DEFAULT NULL,
  `due_date` varchar(100) DEFAULT NULL,
  `acad_year` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT '1',
  `invoice_generated` int(50) NOT NULL DEFAULT 0,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settlements`
--

CREATE TABLE `settlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sponser_maps`
--

CREATE TABLE `sponser_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_ids` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sponser_masters`
--

CREATE TABLE `sponser_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(150) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `gst` varchar(100) DEFAULT NULL,
  `pan` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sponser_schoolfees`
--

CREATE TABLE `sponser_schoolfees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `sponser_id` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `fee_heading` varchar(255) NOT NULL,
  `fee_sub_heading` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `pay_type_id` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(10) UNSIGNED NOT NULL,
  `staff_id` varchar(50) DEFAULT NULL,
  `staffName` varchar(155) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `permanentAddress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permanentAddress`)),
  `communicationAddress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`communicationAddress`)),
  `staff_photo` varchar(255) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT 0,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `teacher_type` varchar(255) DEFAULT NULL,
  `previous_experience` text DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `no_of_children` int(11) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `emergency_contact_no` varchar(20) DEFAULT NULL,
  `epf_no` varchar(100) DEFAULT NULL,
  `aadhaar_no` varchar(20) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `staff_status` varchar(50) DEFAULT NULL,
  `date_of_resignation` date DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- --------------------------------------------------------

--
-- Table structure for table `staff_fees_mapping`
--

CREATE TABLE `staff_fees_mapping` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `fees_type` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','generated','invoice_generated','paid') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_fee_masters`
--

CREATE TABLE `staff_fee_masters` (
  `id` int(11) NOT NULL,
  `feesType` varchar(155) NOT NULL,
  `created_by` varchar(111) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_invoices`
--

CREATE TABLE `staff_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','partial','disabled') DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `due_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_invoice_details`
--

CREATE TABLE `staff_invoice_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `fees_type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payments`
--

CREATE TABLE `staff_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_no` varchar(100) NOT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paid_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_transactions`
--

CREATE TABLE `staff_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `due_amount` decimal(10,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_date` datetime DEFAULT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `invoice_amount` decimal(10,2) DEFAULT NULL,
  `receipt_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `standards`
--

CREATE TABLE `standards` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `delete_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `standard_section_mappings`
--

CREATE TABLE `standard_section_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `standard` int(11) NOT NULL,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sections`)),
  `group` varchar(255) DEFAULT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendances`
--

CREATE TABLE `student_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `roll_no` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `standard` varchar(50) DEFAULT NULL,
  `sec` varchar(10) DEFAULT NULL,
  `twe_group` varchar(100) DEFAULT NULL,
  `attendance` varchar(10) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `count` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_maps`
--

CREATE TABLE `student_fees_maps` (
  `slno` int(11) NOT NULL,
  `student_id` varchar(60) NOT NULL,
  `roll_no` varchar(60) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `standard` varchar(60) DEFAULT NULL,
  `twe_group` varchar(49) DEFAULT NULL,
  `sec` varchar(40) DEFAULT NULL,
  `hostelOrDay` varchar(80) DEFAULT NULL,
  `fee_by` varchar(80) DEFAULT NULL,
  `sponser_id` varchar(80) DEFAULT NULL,
  `email` varchar(90) DEFAULT NULL,
  `fee_id` varchar(55) DEFAULT NULL,
  `amount` varchar(69) DEFAULT NULL,
  `fee_heading` varchar(50) DEFAULT NULL,
  `fee_sub_heading` varchar(89) DEFAULT NULL,
  `Priority` varchar(255) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `acad_year` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `invoice_generated` int(11) DEFAULT 0,
  `created_by` varchar(111) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fee_map_arrays`
--

CREATE TABLE `student_fee_map_arrays` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `std` varchar(111) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `fees_heading` varchar(255) DEFAULT NULL,
  `fees_sub_heading` longtext DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `acad_year` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_infos`
--

CREATE TABLE `student_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admission_no` varchar(255) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) NOT NULL,
  `sex` varchar(50) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `emis_no` varchar(255) DEFAULT NULL,
  `Nationality` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `Religion` varchar(255) DEFAULT NULL,
  `Denomination` varchar(255) DEFAULT NULL,
  `Caste` varchar(50) DEFAULT NULL,
  `CasteClassification` varchar(255) DEFAULT NULL,
  `AadhaarCardNo` varchar(50) DEFAULT NULL,
  `RationCard` varchar(255) DEFAULT NULL,
  `Mothertongue` varchar(255) DEFAULT NULL,
  `Father` varchar(255) DEFAULT NULL,
  `Mother` varchar(255) DEFAULT NULL,
  `Guardian` varchar(255) DEFAULT NULL,
  `Occupation` varchar(255) DEFAULT NULL,
  `Organisation` varchar(255) DEFAULT NULL,
  `Monthlyincome` varchar(255) DEFAULT NULL,
  `p_housenumber` varchar(50) DEFAULT NULL,
  `p_Streetname` varchar(255) DEFAULT NULL,
  `p_VillagetownName` varchar(255) DEFAULT NULL,
  `p_Postoffice` varchar(255) DEFAULT NULL,
  `p_Taluk` varchar(255) DEFAULT NULL,
  `p_District` varchar(255) DEFAULT NULL,
  `p_State` varchar(255) DEFAULT NULL,
  `p_Pincode` varchar(50) DEFAULT NULL,
  `c_HouseNumber` varchar(50) DEFAULT NULL,
  `c_StreetName` varchar(255) DEFAULT NULL,
  `c_VillageTownName` varchar(255) DEFAULT NULL,
  `c_Postoffice` varchar(255) DEFAULT NULL,
  `c_Taluk` varchar(70) DEFAULT NULL,
  `c_District` varchar(255) DEFAULT NULL,
  `c_State` varchar(255) DEFAULT NULL,
  `c_Pincode` varchar(50) DEFAULT NULL,
  `Mobilenumber` varchar(255) DEFAULT NULL,
  `WhatsAppNo` varchar(255) DEFAULT NULL,
  `ClasslastStudied` varchar(255) DEFAULT NULL,
  `EmailID` varchar(255) DEFAULT NULL,
  `Nameofschool` varchar(255) DEFAULT NULL,
  `File` varchar(255) DEFAULT NULL,
  `sought_Std` varchar(255) DEFAULT NULL,
  `sec` varchar(50) DEFAULT NULL,
  `Part_I` varchar(255) DEFAULT NULL,
  `Group` varchar(255) DEFAULT NULL,
  `FOOD` varchar(255) DEFAULT NULL,
  `hostelOrDay` varchar(50) DEFAULT NULL,
  `special_information` varchar(255) DEFAULT NULL,
  `Declare_not_attended` text DEFAULT NULL,
  `Declare_dues` varchar(90) DEFAULT NULL,
  `Declare_dob` varchar(255) DEFAULT NULL,
  `Declare_Date` varchar(255) DEFAULT NULL,
  `Declare_Place` varchar(255) DEFAULT NULL,
  `Measles` varchar(255) DEFAULT NULL,
  `Chickenpox` varchar(255) DEFAULT NULL,
  `Fits` varchar(255) DEFAULT NULL,
  `Rheumaticfever` varchar(255) DEFAULT NULL,
  `Mumps` varchar(255) DEFAULT NULL,
  `Jaundice` varchar(255) DEFAULT NULL,
  `Asthma` varchar(255) DEFAULT NULL,
  `Nephritis` varchar(255) DEFAULT NULL,
  `Whoopingcough` varchar(255) DEFAULT NULL,
  `Tuberculosis` varchar(255) DEFAULT NULL,
  `Hayfever` varchar(111) DEFAULT NULL,
  `CongenitalHeartDisease` varchar(111) DEFAULT NULL,
  `P_Bronchial` varchar(111) DEFAULT NULL,
  `P_Tuberculosis` varchar(111) DEFAULT NULL,
  `BCG` varchar(111) DEFAULT NULL,
  `Triple_Vaccine` varchar(111) DEFAULT NULL,
  `Polio_Drops` varchar(111) DEFAULT NULL,
  `Measles_given` varchar(111) DEFAULT NULL,
  `MMR` varchar(111) DEFAULT NULL,
  `Dual_Vaccine` varchar(111) DEFAULT NULL,
  `Typhoid` varchar(111) DEFAULT NULL,
  `Cholera` varchar(111) DEFAULT NULL,
  `permission_to_principal` varchar(111) DEFAULT NULL,
  `administration_of_anaesthetic` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_infos_bk`
--

CREATE TABLE `student_infos_bk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `blood_group` varchar(50) NOT NULL,
  `emis_no` varchar(255) NOT NULL,
  `Nationality` varchar(255) NOT NULL,
  `State` varchar(255) NOT NULL,
  `Religion` varchar(255) NOT NULL,
  `Denomination` varchar(255) NOT NULL,
  `Caste` varchar(50) NOT NULL,
  `CasteClassification` varchar(255) NOT NULL,
  `AadhaarCardNo` varchar(50) NOT NULL,
  `RationCard` varchar(255) NOT NULL,
  `Mothertongue` varchar(255) NOT NULL,
  `Father` varchar(255) NOT NULL,
  `Mother` varchar(255) NOT NULL,
  `Guardian` varchar(255) NOT NULL,
  `Occupation` varchar(255) NOT NULL,
  `Organisation` varchar(255) NOT NULL,
  `Monthlyincome` varchar(255) NOT NULL,
  `p_housenumber` varchar(50) NOT NULL,
  `p_Streetname` varchar(255) NOT NULL,
  `p_VillagetownName` varchar(255) NOT NULL,
  `p_Postoffice` varchar(255) NOT NULL,
  `p_Taluk` varchar(255) NOT NULL,
  `p_District` varchar(255) NOT NULL,
  `p_State` varchar(255) NOT NULL,
  `p_Pincode` varchar(50) NOT NULL,
  `c_HouseNumber` varchar(50) NOT NULL,
  `c_StreetName` varchar(255) NOT NULL,
  `c_VillageTownName` varchar(255) NOT NULL,
  `c_Postoffice` varchar(255) NOT NULL,
  `c_Taluk` varchar(70) DEFAULT NULL,
  `c_District` varchar(255) NOT NULL,
  `c_State` varchar(255) NOT NULL,
  `c_Pincode` varchar(50) NOT NULL,
  `Mobilenumber` varchar(255) NOT NULL,
  `WhatsAppNo` varchar(255) NOT NULL,
  `ClasslastStudied` varchar(255) NOT NULL,
  `EmailID` varchar(255) NOT NULL,
  `Nameofschool` varchar(255) NOT NULL,
  `File` varchar(255) NOT NULL,
  `sought_Std` text NOT NULL,
  `Part_I` text NOT NULL,
  `Group` text NOT NULL,
  `FOOD` text NOT NULL,
  `special_information` text NOT NULL,
  `Declare_not_attended` text NOT NULL,
  `Declare_dues` varchar(90) DEFAULT NULL,
  `Declare_dob` text NOT NULL,
  `Declare_Date` text NOT NULL,
  `Declare_Place` text NOT NULL,
  `Measles` text NOT NULL,
  `Chickenpox` text NOT NULL,
  `Fits` text NOT NULL,
  `Rheumaticfever` text NOT NULL,
  `Mumps` text NOT NULL,
  `Jaundice` text NOT NULL,
  `Asthma` text NOT NULL,
  `Nephritis` text NOT NULL,
  `Whoopingcough` text NOT NULL,
  `Tuberculosis` text NOT NULL,
  `Hayfever` text NOT NULL,
  `CongenitalHeartDisease` text NOT NULL,
  `P_Bronchial` text NOT NULL,
  `P_Tuberculosis` text NOT NULL,
  `BCG` text NOT NULL,
  `Triple_Vaccine` text NOT NULL,
  `Polio_Drops` text NOT NULL,
  `Measles_given` text NOT NULL,
  `MMR` text NOT NULL,
  `Dual_Vaccine` text NOT NULL,
  `Typhoid` text NOT NULL,
  `Cholera` text NOT NULL,
  `permission_to_principal` text NOT NULL,
  `administration_of_anaesthetic` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_mark_records`
--

CREATE TABLE `student_mark_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `roll_no` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `standard` varchar(255) NOT NULL,
  `section` varchar(255) DEFAULT NULL,
  `term` varchar(255) NOT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `subjects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`subjects`)),
  `total` int(11) DEFAULT NULL,
  `percentage` float(5,2) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_schoolfees`
--

CREATE TABLE `student_schoolfees` (
  `slno` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `fee_heading` varchar(255) NOT NULL,
  `fee_sub_heading` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `pay_type_id` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_announcements`
--

CREATE TABLE `target_announcements` (
  `id` int(10) UNSIGNED NOT NULL,
  `target_audience` varchar(255) NOT NULL,
  `target_group` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`target_group`)),
  `user_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`user_details`)),
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_types`
--

CREATE TABLE `teacher_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `teacher_type` varchar(255) NOT NULL,
  `delete_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_editors`
--

CREATE TABLE `template_editors` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `template` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_masters`
--

CREATE TABLE `template_masters` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temporary_student_marks`
--

CREATE TABLE `temporary_student_marks` (
  `id` int(10) UNSIGNED NOT NULL,
  `academic_year` varchar(255) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `term` varchar(255) DEFAULT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `subjects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`subjects`)),
  `total` int(11) DEFAULT NULL,
  `percentage` float(5,2) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `delete_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_donation_worldline`
--

CREATE TABLE `transactions_donation_worldline` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `twelveth_groups`
--

CREATE TABLE `twelveth_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(50) NOT NULL,
  `group_des` varchar(150) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `slno` bigint(20) UNSIGNED NOT NULL,
  `id` int(255) NOT NULL,
  `admission_no` varchar(200) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `twe_group` varchar(255) DEFAULT NULL,
  `sec` varchar(30) DEFAULT NULL,
  `hostelOrDay` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL DEFAULT 'student',
  `fee_by` varchar(100) DEFAULT 'parent',
  `sponser_id` int(80) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `excess_amount` varchar(255) DEFAULT NULL,
  `h_excess_amount` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `academic_year` varchar(100) DEFAULT NULL,
  `grade_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_excess_histories`
--

CREATE TABLE `user_excess_histories` (
  `id` int(11) NOT NULL,
  `sponser_id` int(11) NOT NULL,
  `excess_amount` decimal(10,2) DEFAULT 0.00,
  `h_excess_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_grade_histories`
--

CREATE TABLE `user_grade_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `admission_no` varchar(50) NOT NULL,
  `previous_standard` varchar(20) DEFAULT NULL,
  `previous_sec` varchar(10) DEFAULT NULL,
  `previous_grade_status` varchar(50) DEFAULT NULL,
  `new_standard` varchar(20) DEFAULT NULL,
  `new_sec` varchar(10) DEFAULT NULL,
  `new_grade_status` varchar(50) DEFAULT NULL,
  `previous_academic_year` varchar(20) DEFAULT NULL,
  `current_academic_year` varchar(20) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notification_type` varchar(255) NOT NULL,
  `notification_category` int(11) NOT NULL,
  `notification_text` text NOT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `send_status` enum('sent','pending','failed') DEFAULT 'pending',
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webinars`
--

CREATE TABLE `webinars` (
  `id` int(10) UNSIGNED NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `class` varchar(10) NOT NULL,
  `section` varchar(5) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `host_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_live`
--
ALTER TABLE `admission_live`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_live_demo`
--
ALTER TABLE `admission_live_demo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_process`
--
ALTER TABLE `admission_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admission_process_live`
--
ALTER TABLE `admission_process_live`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admitted_students`
--
ALTER TABLE `admitted_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admitted_students_history`
--
ALTER TABLE `admitted_students_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_sponser_payments`
--
ALTER TABLE `bulk_sponser_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `by_pay_informations`
--
ALTER TABLE `by_pay_informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_masters`
--
ALTER TABLE `class_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subject_mappings`
--
ALTER TABLE `class_subject_mappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_teachers`
--
ALTER TABLE `class_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deletedreceipts`
--
ALTER TABLE `deletedreceipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_category_masters`
--
ALTER TABLE `discount_category_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_lists`
--
ALTER TABLE `discount_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donar_list`
--
ALTER TABLE `donar_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `donation_donar_list`
--
ALTER TABLE `donation_donar_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation_list`
--
ALTER TABLE `donation_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation_statement`
--
ALTER TABLE `donation_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation_statement_trans`
--
ALTER TABLE `donation_statement_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dropdown_types`
--
ALTER TABLE `dropdown_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_calendars`
--
ALTER TABLE `event_calendars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_category_masters`
--
ALTER TABLE `event_category_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_masters`
--
ALTER TABLE `exam_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fees_maps`
--
ALTER TABLE `fees_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_map_arrays`
--
ALTER TABLE `fee_map_arrays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `generate_invoice_views`
--
ALTER TABLE `generate_invoice_views`
  ADD PRIMARY KEY (`slno`);

--
-- Indexes for table `group_masters`
--
ALTER TABLE `group_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `healthcare_records`
--
ALTER TABLE `healthcare_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_student_infos`
--
ALTER TABLE `history_student_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel_admissions`
--
ALTER TABLE `hostel_admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_acad_year` (`acad_year`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `hostel_fee_masters`
--
ALTER TABLE `hostel_fee_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_histories`
--
ALTER TABLE `invoice_histories`
  ADD PRIMARY KEY (`slno`);

--
-- Indexes for table `invoice_lists`
--
ALTER TABLE `invoice_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_pendings`
--
ALTER TABLE `invoice_pendings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lifecycle_logs`
--
ALTER TABLE `lifecycle_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_category_master`
--
ALTER TABLE `message_category_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_boards`
--
ALTER TABLE `notice_boards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_categories`
--
ALTER TABLE `notification_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `other_expenditure_masters`
--
ALTER TABLE `other_expenditure_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_gateway_admins`
--
ALTER TABLE `payment_gateway_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_mode_masters`
--
ALTER TABLE `payment_mode_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_notification_datas`
--
ALTER TABLE `payment_notification_datas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_orders_details`
--
ALTER TABLE `payment_orders_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_orders_statuses`
--
ALTER TABLE `payment_orders_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_req_data`
--
ALTER TABLE `payment_req_data`
  ADD PRIMARY KEY (`payment_req_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_fee_discounts`
--
ALTER TABLE `school_fee_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_fee_masters`
--
ALTER TABLE `school_fee_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_miscellaneous_bill_masters`
--
ALTER TABLE `school_miscellaneous_bill_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_masters`
--
ALTER TABLE `section_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seperate_fees_maps`
--
ALTER TABLE `seperate_fees_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settlements`
--
ALTER TABLE `settlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `sponser_maps`
--
ALTER TABLE `sponser_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponser_masters`
--
ALTER TABLE `sponser_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponser_schoolfees`
--
ALTER TABLE `sponser_schoolfees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_fees_mapping`
--
ALTER TABLE `staff_fees_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_fee_masters`
--
ALTER TABLE `staff_fee_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_invoices`
--
ALTER TABLE `staff_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`);

--
-- Indexes for table `staff_invoice_details`
--
ALTER TABLE `staff_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_payments`
--
ALTER TABLE `staff_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_transactions`
--
ALTER TABLE `staff_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `standards`
--
ALTER TABLE `standards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `standard_section_mappings`
--
ALTER TABLE `standard_section_mappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fees_maps`
--
ALTER TABLE `student_fees_maps`
  ADD PRIMARY KEY (`slno`);

--
-- Indexes for table `student_fee_map_arrays`
--
ALTER TABLE `student_fee_map_arrays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_infos`
--
ALTER TABLE `student_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_infos_bk`
--
ALTER TABLE `student_infos_bk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_mark_records`
--
ALTER TABLE `student_mark_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_schoolfees`
--
ALTER TABLE `student_schoolfees`
  ADD PRIMARY KEY (`slno`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_announcements`
--
ALTER TABLE `target_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_types`
--
ALTER TABLE `teacher_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template_editors`
--
ALTER TABLE `template_editors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_name` (`template_name`);

--
-- Indexes for table `template_masters`
--
ALTER TABLE `template_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temporary_student_marks`
--
ALTER TABLE `temporary_student_marks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions_donation_worldline`
--
ALTER TABLE `transactions_donation_worldline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `twelveth_groups`
--
ALTER TABLE `twelveth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`slno`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `slno` (`slno`);

--
-- Indexes for table `user_excess_histories`
--
ALTER TABLE `user_excess_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_grade_histories`
--
ALTER TABLE `user_grade_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webinars`
--
ALTER TABLE `webinars`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission`
--
ALTER TABLE `admission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_live`
--
ALTER TABLE `admission_live`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_live_demo`
--
ALTER TABLE `admission_live_demo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_process`
--
ALTER TABLE `admission_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_process_live`
--
ALTER TABLE `admission_process_live`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admitted_students`
--
ALTER TABLE `admitted_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admitted_students_history`
--
ALTER TABLE `admitted_students_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_sponser_payments`
--
ALTER TABLE `bulk_sponser_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `by_pay_informations`
--
ALTER TABLE `by_pay_informations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_masters`
--
ALTER TABLE `class_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_subject_mappings`
--
ALTER TABLE `class_subject_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_teachers`
--
ALTER TABLE `class_teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deletedreceipts`
--
ALTER TABLE `deletedreceipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_category_masters`
--
ALTER TABLE `discount_category_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_lists`
--
ALTER TABLE `discount_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donar_list`
--
ALTER TABLE `donar_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_donar_list`
--
ALTER TABLE `donation_donar_list`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_list`
--
ALTER TABLE `donation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_statement`
--
ALTER TABLE `donation_statement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donation_statement_trans`
--
ALTER TABLE `donation_statement_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dropdown_types`
--
ALTER TABLE `dropdown_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_calendars`
--
ALTER TABLE `event_calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_category_masters`
--
ALTER TABLE `event_category_masters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_masters`
--
ALTER TABLE `exam_masters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees_maps`
--
ALTER TABLE `fees_maps`
  MODIFY `id` bigint(255) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_map_arrays`
--
ALTER TABLE `fee_map_arrays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `generate_invoice_views`
--
ALTER TABLE `generate_invoice_views`
  MODIFY `slno` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_masters`
--
ALTER TABLE `group_masters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `healthcare_records`
--
ALTER TABLE `healthcare_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_student_infos`
--
ALTER TABLE `history_student_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hostel_admissions`
--
ALTER TABLE `hostel_admissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hostel_fee_masters`
--
ALTER TABLE `hostel_fee_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_histories`
--
ALTER TABLE `invoice_histories`
  MODIFY `slno` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_lists`
--
ALTER TABLE `invoice_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_pendings`
--
ALTER TABLE `invoice_pendings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lifecycle_logs`
--
ALTER TABLE `lifecycle_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_category_master`
--
ALTER TABLE `message_category_master`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice_boards`
--
ALTER TABLE `notice_boards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_categories`
--
ALTER TABLE `notification_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_expenditure_masters`
--
ALTER TABLE `other_expenditure_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateway_admins`
--
ALTER TABLE `payment_gateway_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_info`
--
ALTER TABLE `payment_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_mode_masters`
--
ALTER TABLE `payment_mode_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_notification_datas`
--
ALTER TABLE `payment_notification_datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_orders_details`
--
ALTER TABLE `payment_orders_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_orders_statuses`
--
ALTER TABLE `payment_orders_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_req_data`
--
ALTER TABLE `payment_req_data`
  MODIFY `payment_req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_fee_discounts`
--
ALTER TABLE `school_fee_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_fee_masters`
--
ALTER TABLE `school_fee_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_miscellaneous_bill_masters`
--
ALTER TABLE `school_miscellaneous_bill_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_masters`
--
ALTER TABLE `section_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seperate_fees_maps`
--
ALTER TABLE `seperate_fees_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settlements`
--
ALTER TABLE `settlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponser_maps`
--
ALTER TABLE `sponser_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponser_masters`
--
ALTER TABLE `sponser_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponser_schoolfees`
--
ALTER TABLE `sponser_schoolfees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_fees_mapping`
--
ALTER TABLE `staff_fees_mapping`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_fee_masters`
--
ALTER TABLE `staff_fee_masters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_invoices`
--
ALTER TABLE `staff_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_invoice_details`
--
ALTER TABLE `staff_invoice_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_payments`
--
ALTER TABLE `staff_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_transactions`
--
ALTER TABLE `staff_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `standards`
--
ALTER TABLE `standards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `standard_section_mappings`
--
ALTER TABLE `standard_section_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_attendances`
--
ALTER TABLE `student_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees_maps`
--
ALTER TABLE `student_fees_maps`
  MODIFY `slno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fee_map_arrays`
--
ALTER TABLE `student_fee_map_arrays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_infos`
--
ALTER TABLE `student_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_infos_bk`
--
ALTER TABLE `student_infos_bk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_mark_records`
--
ALTER TABLE `student_mark_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_schoolfees`
--
ALTER TABLE `student_schoolfees`
  MODIFY `slno` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_announcements`
--
ALTER TABLE `target_announcements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_types`
--
ALTER TABLE `teacher_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_editors`
--
ALTER TABLE `template_editors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_masters`
--
ALTER TABLE `template_masters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temporary_student_marks`
--
ALTER TABLE `temporary_student_marks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_donation_worldline`
--
ALTER TABLE `transactions_donation_worldline`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `twelveth_groups`
--
ALTER TABLE `twelveth_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `slno` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_excess_histories`
--
ALTER TABLE `user_excess_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_grade_histories`
--
ALTER TABLE `user_grade_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webinars`
--
ALTER TABLE `webinars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
