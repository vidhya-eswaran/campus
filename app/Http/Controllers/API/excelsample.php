<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class excelsample extends Controller implements FromArray, WithHeadings
{
    public function downloadExcel(): BinaryFileResponse
    {
        return Excel::download($this, 'sample_data.xlsx');
    }

    public function array(): array
    {
        return [
            ['John Doe', 'john@example.com', 'Admin'],
            ['Jane Smith', 'jane@example.com', 'User'],
            ['David Johnson', 'david@example.com', 'Manager'],
        ];
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Role'];
    }
}

