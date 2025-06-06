-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: eucto_campus
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admission`
--

DROP TABLE IF EXISTS `admission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `transfer_certificate_photo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admission_live`
--

DROP TABLE IF EXISTS `admission_live`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admission_live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1086 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admission_live_demo`
--

DROP TABLE IF EXISTS `admission_live_demo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admission_live_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admission_process`
--

DROP TABLE IF EXISTS `admission_process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admission_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admission_process_live`
--

DROP TABLE IF EXISTS `admission_process_live`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admission_process_live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `payment_mode` text DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=758 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admitted_students`
--

DROP TABLE IF EXISTS `admitted_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admitted_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roll_no` varchar(220) DEFAULT NULL,
  `admission_no` varchar(220) DEFAULT NULL,
  `STUDENT_NAME` varchar(220) DEFAULT NULL,
  `date_form` varchar(220) DEFAULT NULL,
  `MOTHERTONGUE` varchar(220) DEFAULT NULL,
  `STATE` varchar(220) DEFAULT NULL,
  `DOB_DD_MM_YYYY` varchar(220) DEFAULT NULL,
  `SEX` varchar(220) DEFAULT NULL,
  `BLOOD_GROUP` varchar(220) DEFAULT NULL,
  `NATIONALITY` varchar(220) DEFAULT NULL,
  `RELIGION` varchar(220) DEFAULT NULL,
  `DENOMINATION` varchar(220) DEFAULT NULL,
  `CASTE` varchar(220) DEFAULT NULL,
  `CASTE_CLASSIFICATION` varchar(220) DEFAULT NULL,
  `AADHAAR_CARD_NO` varchar(220) DEFAULT NULL,
  `RATIONCARDNO` varchar(220) DEFAULT NULL,
  `EMIS_NO` varchar(220) DEFAULT NULL,
  `FOOD` varchar(220) DEFAULT NULL,
  `chronic_des` varchar(220) DEFAULT NULL,
  `medicine_taken` varchar(220) DEFAULT NULL,
  `FATHER` varchar(220) DEFAULT NULL,
  `OCCUPATION` varchar(220) DEFAULT NULL,
  `MOTHER` varchar(220) DEFAULT NULL,
  `mother_occupation` varchar(220) DEFAULT NULL,
  `GUARDIAN` varchar(220) DEFAULT NULL,
  `guardian_occupation` varchar(220) DEFAULT NULL,
  `MOBILE_NUMBER` varchar(220) DEFAULT NULL,
  `EMAIL_ID` varchar(220) DEFAULT NULL,
  `WHATS_APP_NO` varchar(220) DEFAULT NULL,
  `mother_email_id` varchar(220) DEFAULT NULL,
  `guardian_contact_no` varchar(220) DEFAULT NULL,
  `guardian_email_id` varchar(220) DEFAULT NULL,
  `MONTHLY_INCOME` varchar(90) DEFAULT NULL,
  `mother_income` varchar(220) DEFAULT NULL,
  `guardian_income` varchar(220) DEFAULT NULL,
  `PERMANENT_HOUSENUMBER` varchar(220) DEFAULT NULL,
  `P_STREETNAME` varchar(220) DEFAULT NULL,
  `P_VILLAGE_TOWN_NAME` varchar(220) DEFAULT NULL,
  `P_DISTRICT` varchar(220) DEFAULT NULL,
  `P_STATE` varchar(220) DEFAULT NULL,
  `P_PINCODE` varchar(220) DEFAULT NULL,
  `COMMUNICATION_HOUSE_NO` varchar(220) DEFAULT NULL,
  `C_STREET_NAME` varchar(220) DEFAULT NULL,
  `C_VILLAGE_TOWN_NAME` varchar(220) DEFAULT NULL,
  `C_DISTRICT` varchar(220) DEFAULT NULL,
  `C_STATE` varchar(220) DEFAULT NULL,
  `C_PINCODE` varchar(220) DEFAULT NULL,
  `CLASS_LAST_STUDIED` varchar(220) DEFAULT NULL,
  `NAME_OF_SCHOOL` varchar(220) DEFAULT NULL,
  `SOUGHT_STD` varchar(220) DEFAULT NULL,
  `sec` varchar(220) DEFAULT NULL,
  `syllabus` varchar(220) DEFAULT NULL,
  `GROUP_12` varchar(220) DEFAULT NULL,
  `second_group_no` varchar(220) DEFAULT NULL,
  `LANG_PART_I` varchar(220) DEFAULT NULL,
  `profile_photo` varchar(220) DEFAULT NULL,
  `birth_certificate_photo` varchar(90) DEFAULT NULL,
  `aadhar_card_photo` varchar(90) DEFAULT NULL,
  `ration_card_photo` varchar(220) DEFAULT NULL,
  `community_certificate` varchar(90) DEFAULT NULL,
  `slip_photo` varchar(220) DEFAULT NULL,
  `medical_certificate_photo` varchar(90) DEFAULT NULL,
  `reference_letter_photo` varchar(90) DEFAULT NULL,
  `church_certificate_photo` varchar(90) DEFAULT NULL,
  `transfer_certificate_photo` varchar(90) DEFAULT NULL,
  `admission_photo` varchar(90) DEFAULT NULL,
  `payment_order_id` varchar(90) DEFAULT NULL,
  `brother_1` varchar(220) DEFAULT NULL,
  `brother_2` varchar(220) DEFAULT NULL,
  `gender_1` varchar(90) DEFAULT NULL,
  `gender_2` varchar(90) DEFAULT NULL,
  `class_1` varchar(90) DEFAULT NULL,
  `class_2` varchar(90) DEFAULT NULL,
  `brother_3` varchar(90) DEFAULT NULL,
  `gender_3` varchar(90) DEFAULT NULL,
  `class_3` varchar(90) DEFAULT NULL,
  `last_school_state` varchar(90) DEFAULT NULL,
  `second_language_school` varchar(90) DEFAULT NULL,
  `reference_name_1` varchar(90) DEFAULT NULL,
  `reference_name_2` varchar(90) DEFAULT NULL,
  `reference_phone_1` varchar(90) DEFAULT NULL,
  `reference_phone_2` varchar(90) DEFAULT NULL,
  `ORGANISATION` varchar(220) DEFAULT NULL,
  `mother_organization` varchar(220) DEFAULT NULL,
  `guardian_organization` varchar(220) DEFAULT NULL,
  `pin_no` varchar(20) DEFAULT NULL,
  `created_at` varchar(90) DEFAULT NULL,
  `updated_at` varchar(90) DEFAULT NULL,
  `documents` varchar(220) DEFAULT NULL,
  `upload_created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `upload_updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=711 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admitted_students_history`
--

DROP TABLE IF EXISTS `admitted_students_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admitted_students_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_id` varchar(220) DEFAULT NULL,
  `roll_no` varchar(220) DEFAULT NULL,
  `admission_no` varchar(220) DEFAULT NULL,
  `STUDENT_NAME` varchar(220) DEFAULT NULL,
  `date_form` varchar(220) DEFAULT NULL,
  `MOTHERTONGUE` varchar(220) DEFAULT NULL,
  `STATE` varchar(220) DEFAULT NULL,
  `DOB_DD_MM_YYYY` varchar(220) DEFAULT NULL,
  `SEX` varchar(220) DEFAULT NULL,
  `BLOOD_GROUP` varchar(220) DEFAULT NULL,
  `NATIONALITY` varchar(220) DEFAULT NULL,
  `RELIGION` varchar(220) DEFAULT NULL,
  `DENOMINATION` varchar(220) DEFAULT NULL,
  `CASTE` varchar(220) DEFAULT NULL,
  `CASTE_CLASSIFICATION` varchar(220) DEFAULT NULL,
  `AADHAAR_CARD_NO` varchar(220) DEFAULT NULL,
  `RATIONCARDNO` varchar(220) DEFAULT NULL,
  `EMIS_NO` varchar(220) DEFAULT NULL,
  `FOOD` varchar(220) DEFAULT NULL,
  `chronic_des` varchar(220) DEFAULT NULL,
  `medicine_taken` varchar(220) DEFAULT NULL,
  `FATHER` varchar(220) DEFAULT NULL,
  `OCCUPATION` varchar(220) DEFAULT NULL,
  `MOTHER` varchar(220) DEFAULT NULL,
  `mother_occupation` varchar(220) DEFAULT NULL,
  `GUARDIAN` varchar(220) DEFAULT NULL,
  `guardian_occupation` varchar(220) DEFAULT NULL,
  `MOBILE_NUMBER` varchar(220) DEFAULT NULL,
  `EMAIL_ID` varchar(220) DEFAULT NULL,
  `WHATS_APP_NO` varchar(220) DEFAULT NULL,
  `mother_email_id` varchar(220) DEFAULT NULL,
  `guardian_contact_no` varchar(220) DEFAULT NULL,
  `guardian_email_id` varchar(220) DEFAULT NULL,
  `MONTHLY_INCOME` varchar(220) DEFAULT NULL,
  `mother_income` varchar(220) DEFAULT NULL,
  `guardian_income` varchar(220) DEFAULT NULL,
  `PERMANENT_HOUSENUMBER` varchar(220) DEFAULT NULL,
  `P_STREETNAME` varchar(220) DEFAULT NULL,
  `P_VILLAGE_TOWN_NAME` varchar(220) DEFAULT NULL,
  `P_DISTRICT` varchar(220) DEFAULT NULL,
  `P_STATE` varchar(220) DEFAULT NULL,
  `P_PINCODE` varchar(220) DEFAULT NULL,
  `COMMUNICATION_HOUSE_NO` varchar(220) DEFAULT NULL,
  `C_STREET_NAME` varchar(220) DEFAULT NULL,
  `C_VILLAGE_TOWN_NAME` varchar(90) DEFAULT NULL,
  `C_DISTRICT` varchar(90) DEFAULT NULL,
  `C_STATE` varchar(90) DEFAULT NULL,
  `C_PINCODE` varchar(90) DEFAULT NULL,
  `CLASS_LAST_STUDIED` varchar(220) DEFAULT NULL,
  `NAME_OF_SCHOOL` varchar(220) DEFAULT NULL,
  `SOUGHT_STD` varchar(220) DEFAULT NULL,
  `sec` varchar(220) DEFAULT NULL,
  `syllabus` varchar(220) DEFAULT NULL,
  `GROUP_12` varchar(90) DEFAULT NULL,
  `second_group_no` varchar(220) DEFAULT NULL,
  `LANG_PART_I` varchar(220) DEFAULT NULL,
  `profile_photo` varchar(220) DEFAULT NULL,
  `birth_certificate_photo` varchar(90) DEFAULT NULL,
  `aadhar_card_photo` varchar(90) DEFAULT NULL,
  `ration_card_photo` varchar(90) DEFAULT NULL,
  `community_certificate` varchar(90) DEFAULT NULL,
  `slip_photo` varchar(90) DEFAULT NULL,
  `medical_certificate_photo` varchar(90) DEFAULT NULL,
  `reference_letter_photo` varchar(90) DEFAULT NULL,
  `church_certificate_photo` varchar(90) DEFAULT NULL,
  `transfer_certificate_photo` varchar(90) DEFAULT NULL,
  `admission_photo` varchar(220) DEFAULT NULL,
  `payment_order_id` varchar(220) DEFAULT NULL,
  `brother_1` varchar(90) DEFAULT NULL,
  `brother_2` varchar(90) DEFAULT NULL,
  `gender_1` varchar(90) DEFAULT NULL,
  `gender_2` varchar(90) DEFAULT NULL,
  `class_1` varchar(90) DEFAULT NULL,
  `class_2` varchar(90) DEFAULT NULL,
  `brother_3` varchar(90) DEFAULT NULL,
  `gender_3` varchar(90) DEFAULT NULL,
  `class_3` varchar(90) DEFAULT NULL,
  `last_school_state` varchar(220) DEFAULT NULL,
  `second_language_school` varchar(220) DEFAULT NULL,
  `reference_name_1` varchar(220) DEFAULT NULL,
  `reference_name_2` varchar(220) DEFAULT NULL,
  `reference_phone_1` varchar(90) DEFAULT NULL,
  `reference_phone_2` varchar(90) DEFAULT NULL,
  `ORGANISATION` varchar(220) DEFAULT NULL,
  `mother_organization` varchar(220) DEFAULT NULL,
  `guardian_organization` varchar(220) DEFAULT NULL,
  `created_at` varchar(90) DEFAULT NULL,
  `updated_at` varchar(90) DEFAULT NULL,
  `documents` varchar(220) DEFAULT NULL,
  `upload_created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `upload_updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6291 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bulk_sponser_payments`
--

DROP TABLE IF EXISTS `bulk_sponser_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulk_sponser_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`request_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `by_pay_informations`
--

DROP TABLE IF EXISTS `by_pay_informations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `by_pay_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(121) NOT NULL,
  `invoice_id` varchar(121) NOT NULL,
  `transactionId` varchar(121) NOT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `sponsor` varchar(121) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1554 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_masters`
--

DROP TABLE IF EXISTS `class_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deletedreceipts`
--

DROP TABLE IF EXISTS `deletedreceipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deletedreceipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_uuid` varchar(255) DEFAULT NULL,
  `invoice_id` varchar(255) DEFAULT NULL,
  `payment_transaction_id` varchar(255) DEFAULT NULL,
  `transaction_amount` varchar(255) DEFAULT NULL,
  `balance_amount` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `transaction_completed_status` varchar(255) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `discount_category_masters`
--

DROP TABLE IF EXISTS `discount_category_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount_category_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discount_name` varchar(255) NOT NULL,
  `feestype` varchar(200) DEFAULT NULL,
  `amount` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `discount_lists`
--

DROP TABLE IF EXISTS `discount_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discount_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_heading` varchar(150) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `student` varchar(255) NOT NULL,
  `fees_cat` varchar(255) NOT NULL,
  `end_date` varchar(100) NOT NULL,
  `status` int(15) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donar_list`
--

DROP TABLE IF EXISTS `donar_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donar_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address_1` text DEFAULT NULL,
  `city_1` varchar(100) DEFAULT NULL,
  `state_1` varchar(100) DEFAULT NULL,
  `country_1` varchar(100) DEFAULT NULL,
  `pincode_1` varchar(20) DEFAULT NULL,
  `address_2` text DEFAULT NULL,
  `city_2` varchar(100) DEFAULT NULL,
  `state_2` varchar(100) DEFAULT NULL,
  `country_2` varchar(100) DEFAULT NULL,
  `pincode_2` varchar(20) DEFAULT NULL,
  `pan_aadhar` varchar(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donation_donar_list`
--

DROP TABLE IF EXISTS `donation_donar_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation_donar_list` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donation_list`
--

DROP TABLE IF EXISTS `donation_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donation_statement`
--

DROP TABLE IF EXISTS `donation_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation_statement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_id` int(11) NOT NULL,
  `donar_id` int(11) NOT NULL,
  `donar_name` varchar(255) NOT NULL,
  `donation_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transection_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donation_statement_trans`
--

DROP TABLE IF EXISTS `donation_statement_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation_statement_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_id` int(11) NOT NULL,
  `donar_id` int(11) NOT NULL,
  `donar_name` varchar(255) NOT NULL,
  `donation_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `transection_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_map_arrays`
--

DROP TABLE IF EXISTS `fee_map_arrays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_map_arrays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `std` varchar(111) DEFAULT NULL,
  `Checked` varchar(100) DEFAULT NULL,
  `Fee_Category` varchar(255) DEFAULT NULL,
  `Sub_Fees` longtext DEFAULT NULL,
  `Amount` varchar(255) DEFAULT NULL,
  `Acad_Year` varchar(255) DEFAULT NULL,
  `Priority` varchar(100) DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fees_maps`
--

DROP TABLE IF EXISTS `fees_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_maps` (
  `id` bigint(255) unsigned NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `generate_invoice_views`
--

DROP TABLE IF EXISTS `generate_invoice_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generate_invoice_views` (
  `slno` int(255) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`slno`)
) ENGINE=InnoDB AUTO_INCREMENT=3243 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history_student_infos`
--

DROP TABLE IF EXISTS `history_student_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history_student_infos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hostel_fee_masters`
--

DROP TABLE IF EXISTS `hostel_fee_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hostel_fee_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_histories`
--

DROP TABLE IF EXISTS `invoice_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_histories` (
  `table_id` int(255) NOT NULL,
  `slno` int(255) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`slno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_lists`
--

DROP TABLE IF EXISTS `invoice_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uuid` bigint(20) unsigned NOT NULL,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `payment_transaction_id` bigint(20) unsigned NOT NULL,
  `transaction_amount` decimal(12,2) DEFAULT 0.00,
  `balance_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) DEFAULT NULL,
  `transaction_completed_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1562 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_pendings`
--

DROP TABLE IF EXISTS `invoice_pendings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_pendings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(255) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `fees_cat` varchar(255) NOT NULL,
  `pending_amount` decimal(12,2) NOT NULL,
  `closed_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=536 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `other_expenditure_masters`
--

DROP TABLE IF EXISTS `other_expenditure_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `other_expenditure_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_gateway_admins`
--

DROP TABLE IF EXISTS `payment_gateway_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_gateway_admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_info`
--

DROP TABLE IF EXISTS `payment_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_mode_masters`
--

DROP TABLE IF EXISTS `payment_mode_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_mode_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paymenttype` varchar(255) NOT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_notification_datas`
--

DROP TABLE IF EXISTS `payment_notification_datas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_notification_datas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `txnId` varchar(255) NOT NULL,
  `paidAmount` varchar(255) NOT NULL,
  `invoice_nos` varchar(255) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `show_hide` int(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_orders_details`
--

DROP TABLE IF EXISTS `payment_orders_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_orders_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `internal_txn_id` bigint(20) unsigned NOT NULL COMMENT ' internal random id',
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'user id / Sponser id',
  `amount` decimal(12,2) NOT NULL COMMENT 'invoice total amount',
  `paymentMode` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'user name/ Card holder Name',
  `accNo` varchar(255) DEFAULT NULL COMMENT 'user account no',
  `custID` bigint(20) unsigned DEFAULT NULL COMMENT 'user id / sponser id /card holder id',
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2488 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_orders_statuses`
--

DROP TABLE IF EXISTS `payment_orders_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_orders_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2678 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_req_data`
--

DROP TABLE IF EXISTS `payment_req_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_req_data` (
  `payment_req_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `payment_req_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`payment_req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=869 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt_no` (`receipt_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `send_to` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_fee_discounts`
--

DROP TABLE IF EXISTS `school_fee_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_fee_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(100) DEFAULT NULL,
  `discount_cat` longtext DEFAULT NULL,
  `dis_amount` varchar(255) DEFAULT NULL,
  `invoicefeescat` varchar(255) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_fee_masters`
--

DROP TABLE IF EXISTS `school_fee_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_fee_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_miscellaneous_bill_masters`
--

DROP TABLE IF EXISTS `school_miscellaneous_bill_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_miscellaneous_bill_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sub_heading` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `section_masters`
--

DROP TABLE IF EXISTS `section_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `seperate_fees_maps`
--

DROP TABLE IF EXISTS `seperate_fees_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seperate_fees_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settlements`
--

DROP TABLE IF EXISTS `settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sponser_maps`
--

DROP TABLE IF EXISTS `sponser_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sponser_maps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_ids` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sponser_masters`
--

DROP TABLE IF EXISTS `sponser_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sponser_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sponser_schoolfees`
--

DROP TABLE IF EXISTS `sponser_schoolfees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sponser_schoolfees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_fee_masters`
--

DROP TABLE IF EXISTS `staff_fee_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_fee_masters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `created_by` varchar(111) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_fee_map_arrays`
--

DROP TABLE IF EXISTS `student_fee_map_arrays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_fee_map_arrays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7025 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_fees_maps`
--

DROP TABLE IF EXISTS `student_fees_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_fees_maps` (
  `slno` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`slno`)
) ENGINE=InnoDB AUTO_INCREMENT=22281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_infos`
--

DROP TABLE IF EXISTS `student_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_infos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_infos_bk`
--

DROP TABLE IF EXISTS `student_infos_bk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_infos_bk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `administration_of_anaesthetic` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_schoolfees`
--

DROP TABLE IF EXISTS `student_schoolfees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_schoolfees` (
  `slno` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`slno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions_donation_worldline`
--

DROP TABLE IF EXISTS `transactions_donation_worldline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions_donation_worldline` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `created_at` DATETIME NULL DEFAULT current_timestamp(),
  `updated_at` DATETIME NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `twelveth_groups`
--

DROP TABLE IF EXISTS `twelveth_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `twelveth_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(50) NOT NULL,
  `group_des` varchar(150) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `slno` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(255) NOT NULL,
  `admission_no` varchar(200) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `standard` varchar(50) DEFAULT NULL,
  `twe_group` varchar(50) DEFAULT NULL,
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
  PRIMARY KEY (`slno`,`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `slno` (`slno`)
) ENGINE=InnoDB AUTO_INCREMENT=4659 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-21 13:05:04
