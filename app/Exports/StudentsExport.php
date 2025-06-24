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
            $student['student_name'],
            $student['date_form'],
            $student['mother_tongue'],
            $student['state'],
            $student['dob'],
            $student['gender'],
            $student['blood_group'],
            $student['nationality'],
            $student['religion'],
            $student['denomination'],
            $student['caste'],
            $student['caste_classification'],
            $student['aadhar_card_no'],
            $student['ration_card_no'],
            $student['emis_no'],
            $student['food_choice'],
            $student['chronic_des'],
            $student['medicine_taken'],
            $student['father_title'],
            $student['father_name'],
            $student['father_occupation'],
           $student['mother_title'],
            $student['mother_name'],
            $student['mother_occupation'],
             $student['guardian_title'],
            $student['guardian_name'],
            $student['guardian_occupation'],
            $student['father_mobile_no'],
            $student['father_email_id'],
            $student['mother_mobile_no'],
            $student['mother_email_id'],
            $student['guardian_mobile_no'],
            $student['guardian_email_id'],
            $student['father_annual_income'],
            $student['mother_annual_income'],
            $student['guardian_annual_income'],
            $student['permanent_house_no'],
            $student['permanent_street_name'],
            $student['permanent_city_town_village'],
            $student['permanent_district'],
            $student['permanent_state'],
            $student['permanent_pincode'],
            $student['communication_house_no'],
            $student['communication_street_name'],
            $student['communication_city_town_village'],
            $student['communication_district'],
            $student['communication_state'],
            $student['communication_pincode'],
            $student['class_last_studied'],
            $student['last_school_name'],
            $student['std_sought'],
            $student['sec'],
            $student['syllabus'],
            $student['group_no'],
            $student['group_first_choice'],
            $student['group_second_choice'],
            $student['language'],
            $student['payment_order_id'],
            $student['siblings'],
            $student['sibling_1'],
            $student['sibling_2'],
            $student['sibling_3'],
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
            $student['father_organization'],
            $student['mother_organization'],
            $student['guardian_organization'],
            $student['grade_status'],
            $student['academic_year'],
            $student['status'],
        ];
    }
}
