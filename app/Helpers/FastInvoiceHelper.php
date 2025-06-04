<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FastInvoiceHelper
{
    protected static function logDebug($label, $data)
    {
        Log::channel('daily')->info("[FastInvoiceHelper][$label]", is_array($data) ? $data : ['data' => $data]);
    }

    protected static function logError(Throwable $e, $context = [])
    {
        Log::channel('daily')->error("[FastInvoiceHelper][ERROR] " . $e->getMessage(), array_merge([
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], $context));
    }

    public static function getFeesCategory($identifier): string
    {
        try {
            $id = strtolower((string) $identifier);

            if ($id === 'school') return 'S';
            if ($id === 'other') return 'H';

            $feesCat = DB::table('generate_invoice_views')
                ->where(is_numeric($identifier) ? 'slno' : 'invoice_no', $identifier)
                ->value('fees_cat');

            return strtolower($feesCat) === 'school' ? 'S' : 'H';
        } catch (Throwable $e) {
            self::logError($e, ['identifier' => $identifier]);
            return 'H'; // fallback category
        }
    }

    public static function generateInvoiceWithPrefix($identifier): string
    {
        $monthYear = date('ym'); // yymm format
        $number = 0;

        try {
            $type = self::getFeesCategory($identifier);
            $title = 'invoice_' . $type;

            self::logDebug('generateInvoiceWithPrefix.input', [
                'identifier' => $identifier,
                'monthYear' => $monthYear,
                'type' => $type,
                'title' => $title
            ]);

            DB::transaction(function () use (&$number, $monthYear, $title) {
                $row = DB::table('invoice_sequences')
                    ->where('month_year', $monthYear)
                    ->where('title', $title)
                    ->lockForUpdate()
                    ->first();

                if ($row) {
                    $number = $row->latest_number + 1;
                    DB::table('invoice_sequences')
                        ->where('id', $row->id)
                        ->update(['latest_number' => $number]);
                } else {
                    $number = 1;
                    DB::table('invoice_sequences')->insert([
                        'month_year' => $monthYear,
                        'latest_number' => $number,
                        'title' => $title,
                    ]);
                }
            }, 1);
        } catch (Throwable $e) {
            self::logError($e, ['identifier' => $identifier]);
            return 'INV' . $monthYear . '-' . $type . 'ERR';
        }

        return 'INV' . $monthYear . '-' . $type . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public static function generateReceiptWithPrefix($identifier): string
    {
        $monthYear = date('ym'); // yymm format
        $number = 0;

        try {
            $type = self::getFeesCategory($identifier);
            $title = 'receipt_' . $type;

            self::logDebug('generateReceiptWithPrefix.input', [
                'identifier' => $identifier,
                'monthYear' => $monthYear,
                'type' => $type,
                'title' => $title
            ]);

            DB::transaction(function () use (&$number, $monthYear, $title) {
                $row = DB::table('invoice_sequences')
                    ->where('month_year', $monthYear)
                    ->where('title', $title)
                    ->lockForUpdate()
                    ->first();

                if ($row) {
                    $number = $row->latest_number + 1;
                    DB::table('invoice_sequences')
                        ->where('id', $row->id)
                        ->update(['latest_number' => $number]);
                } else {
                    $number = 1;
                    DB::table('invoice_sequences')->insert([
                        'month_year' => $monthYear,
                        'latest_number' => $number,
                        'title' => $title,
                    ]);
                }
            }, 1);
        } catch (Throwable $e) {
            self::logError($e, ['identifier' => $identifier]);
            return 'RT' . $monthYear . $type . '-ERR';
        }

        return 'RT' . $monthYear . $type . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
