<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;  // Add this for converting an array into a Collection

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct($students)
    {
        // If students are passed as an array, convert them to a Collection
        $this->students = $students instanceof Collection ? $students : collect($students);
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'Student ID', 'Roll Number', 'Admission Number', 'Student Name', 'Date Form', 'Mother Tongue', 'State', 'Date of Birth', 'Gender', 'Blood Group',
            'Nationality', 'Religion', 'Denomination', 'Caste', 'Caste Classification', 'Aadhaar Card No.', 'Ration Card No.', 'EMIS No.', 'Food Preference',
            'Chronic Disease', 'Medicine Taken', 'Father Title', 'Father Name', 'Father Occupation', 'Mother Title', 'Mother Name', 'Mother Occupation', 'Guardian Title', 'Guardian Name', 'Guardian Occupation',
            'Mobile Number', 'Email ID', 'WhatsApp No.', 'Mother Email ID', 'Guardian Contact No.', 'Guardian Email ID', 'Monthly Income', 'Mother Income',
            'Guardian Income', 'Permanent House No.', 'Permanent Street Name', 'Permanent Village/Town Name', 'Permanent District', 'Permanent State', 'Permanent Pincode',
            'Communication House No.', 'Communication Street Name', 'Communication Village/Town Name', 'Communication District', 'Communication State', 'Communication Pincode',
            'Class Last Studied', 'School Name', 'Sought Standard', 'Section', 'Syllabus', 'Group 12', 'Group No.', 'Second Group No.', 'Language Part I', 'Payment Order ID', 'Siblings', 'Brother 1', 'Brother 2', 'Brother 3',
            'Gender 1', 'Gender 2', 'Gender 3', 'Class 1', 'Class 2', 'Class 3', 'Last School State', 'Second Language School', 'Second Language', 'Reference Name 1',
            'Reference Name 2', 'Reference Phone 1', 'Reference Phone 2', 'Organisation', 'Mother Organisation', 'Guardian Organisation', 'Grade Status', 'Academic Year','Status'
        ];
    }

    public function map($student): array
    {
        return [
            $student['id'],
            $student['roll_no'],
            $student['admission_no'],
            $student['STUDENT_NAME'],
            $student['date_form'],
            $student['MOTHERTONGUE'],
            $student['STATE'],
            $student['DOB_DD_MM_YYYY'],
            $student['SEX'],
            $student['BLOOD_GROUP'],
            $student['NATIONALITY'],
            $student['RELIGION'],
            $student['DENOMINATION'],
            $student['CASTE'],
            $student['CASTE_CLASSIFICATION'],
            $student['AADHAAR_CARD_NO'],
            $student['RATIONCARDNO'],
            $student['EMIS_NO'],
            $student['FOOD'],
            $student['chronic_des'],
            $student['medicine_taken'],
            $student['father_title'],
            $student['FATHER'],
            $student['OCCUPATION'],
           $student['mother_title'],
            $student['MOTHER'],
            $student['mother_occupation'],
             $student['guardian_title'],
            $student['GUARDIAN'],
            $student['guardian_occupation'],
            $student['MOBILE_NUMBER'],
            $student['EMAIL_ID'],
            $student['WHATS_APP_NO'],
            $student['mother_email_id'],
            $student['guardian_contact_no'],
            $student['guardian_email_id'],
            $student['MONTHLY_INCOME'],
            $student['mother_income'],
            $student['guardian_income'],
            $student['PERMANENT_HOUSENUMBER'],
            $student['P_STREETNAME'],
            $student['P_VILLAGE_TOWN_NAME'],
            $student['P_DISTRICT'],
            $student['P_STATE'],
            $student['P_PINCODE'],
            $student['COMMUNICATION_HOUSE_NO'],
            $student['C_STREET_NAME'],
            $student['C_VILLAGE_TOWN_NAME'],
            $student['C_DISTRICT'],
            $student['C_STATE'],
            $student['C_PINCODE'],
            $student['CLASS_LAST_STUDIED'],
            $student['NAME_OF_SCHOOL'],
            $student['SOUGHT_STD'],
            $student['sec'],
            $student['syllabus'],
            $student['GROUP_12'],
            $student['group_no'],
            $student['second_group_no'],
            $student['LANG_PART_I'],
            $student['payment_order_id'],
            $student['siblings'],
            $student['brother_1'],
            $student['brother_2'],
            $student['brother_3'],
            $student['gender_1'],
            $student['gender_2'],
            $student['gender_3'],
            $student['class_1'],
            $student['class_2'],
            $student['class_3'],
            $student['last_school_state'],
            $student['second_language_school'],
            $student['second_language'],
            $student['reference_name_1'],
            $student['reference_name_2'],
            $student['reference_phone_1'],
            $student['reference_phone_2'],
            $student['ORGANISATION'],
            $student['mother_organization'],
            $student['guardian_organization'],
            $student['grade_status'],
            $student['academic_year'],
            $student['status'],
        ];
    }
}
