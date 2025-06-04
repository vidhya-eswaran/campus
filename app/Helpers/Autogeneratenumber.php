<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Contact;

class Autogeneratenumber
{
    /**
     * Generate a Staff ID.
     *
     * Format: (last two digits of year) + (2-digit month) + (3-digit serial that resets every year)
     * Example: 2402001 for a joining date in February 2024 and it being the first record of the year.
     *
     * @param string $joiningDate A valid date string (e.g., "2024-02-15").
     * @return string The generated Staff ID.
     */
    public static function generateStaffId($joiningDate)
    {
        // Parse the joining date using Carbon.
        $date = Carbon::parse($joiningDate);
        $yearTwoDigit = $date->format('y'); // e.g., "24" for 2024
        $month = $date->format('m'); // e.g., "02" for February

        // Retrieve the last staff record for the given joining year.
        $lastStaff = Staff::whereYear('date_of_joining', $date->year)
                          ->orderBy('id', 'desc')
                          ->first();

        $serial = 1;
        if ($lastStaff) {
            // Extract the last 3 digits from the last staff ID.
            $lastSerial = (int) substr($lastStaff->staff_id, -3);
            $serial = $lastSerial + 1;
        }

        // Format the serial as a 3-digit number.
        $serialFormatted = str_pad($serial, 3, '0', STR_PAD_LEFT);

        // Concatenate the parts to form the complete Staff ID.
        return $yearTwoDigit . $month . $serialFormatted;
    }

    /**
     * Generate a Contact ID.
     *
     * Format: (full year) + (2-digit month) + (4-digit serial that resets every year)
     * Example: 2024020001 for a contact date in February 2024 and it being the first record of the year.
     *
     * @param string $contactDate A valid date string (e.g., "2024-02-15").
     * @return string The generated Contact ID.
     */
    public static function generateContactId($contactDate)
    {
 
    // Parse the contact date using Carbon.
    $date = Carbon::parse($contactDate);
    $yearFull = $date->format('Y'); // Full year, e.g., "2024"
    $month = $date->format('m'); // Month, e.g., "02"

    // Retrieve the last contact record for the given year and month using LIKE on 'id'.
    $lastContact = Contact::where('id', 'like', $yearFull . $month . '%') // Match 'id' starting with YYYYMM
                          ->orderBy('id', 'desc')  // Order by 'id' in descending order to get the latest one
                          ->first();

    // Start serial at 1 if no records exist.
    $serial = 1;
    if ($lastContact) {
        // Extract the last 4 digits from the last 'id' and increment the serial number.
        $lastSerial = (int) substr($lastContact->id, -4); // Get the last 4 digits from the 'id'
        $serial = $lastSerial + 1; // Increment the serial number
    }

    // Format the serial as a 4-digit number.
    $serialFormatted = str_pad($serial, 4, '0', STR_PAD_LEFT); // Ensure 4 digits

    // Concatenate the parts to form the complete ID.
    $contactId = $yearFull . $month . $serialFormatted; // Final ID format: YYYYMMxxxx

    // Now you can insert this contact ID into your database, or return it to use later
    return $contactId; 
}

}
