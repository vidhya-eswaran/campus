<?php
// app/Helpers/ResponseHelper.php

namespace App\Helpers;

class ResponseHelper
{
    public static function formatCommonResponse($photo, $name, $mobileNo, $permanentAddress, $communicationAddress)
    {
        return [
            'photo' => $photo,
            'name' => $name,
            'mobileNo' => $mobileNo,
            'permanentAddress' => [
                'addressLine1' => $permanentAddress['PERMANENT_HOUSENUMBER'] ?? null,
                'addressLine2' => $permanentAddress['P_STREETNAME'] ?? null,
                'addressLine3' => $permanentAddress['P_VILLAGE_TOWN_NAME'] ?? null,
                'addressLine4' => $permanentAddress['P_DISTRICT'] ?? null,
                'addressLine5' => $permanentAddress['P_STATE'] ?? null,
                'addressLine6' => $permanentAddress['P_PINCODE'] ?? null,
             ],
            'communicationAddress' => [
                'addressLine1' => $communicationAddress['COMMUNICATION_HOUSE_NO'] ?? null,
                'addressLine2' => $communicationAddress['C_STREET_NAME'] ?? null,
                'addressLine3' => $communicationAddress['C_VILLAGE_TOWN_NAME'] ?? null,
                'addressLine4' => $communicationAddress['C_DISTRICT'] ?? null,
                'addressLine5' => $communicationAddress['C_STATE'] ?? null,
                'addressLine6' => $communicationAddress['C_PINCODE'] ?? null,
             ],
        ];
    }
}
