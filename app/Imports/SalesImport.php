<?php

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Get current user
        $currentUser = Auth::user();

        // Parse the date - handle different formats
        $date = $this->parseDate($row['date'] ?? null);

        return new Sale([
            'date' => $date,
            'profile_id' => $row['id'] ?? null,
            'name' => $row['customer_name'] ?? null,
            'phone' => $row['phone'] ?? null,
            'plan' => $row['plan'] ?? null,
            'amount' => $row['paid_amount'] ?? 0, // Using paid_amount as the main amount
            'paid_amount' => $row['paid_amount'] ?? 0,
            'discount' => 0,
            'success_fee' => 0,
            'executive' => $row['service_executive'] ?? null,
            'office' => $row['office'] ?? null,
            'sale_status' => $row['sale_status'] ?? 'PENDING',
            'cash_type' => $row['cash_type'] ?? null,
            'notes' => null,
            'staff_id' => $currentUser->id,
            'created_by' => $currentUser->id,
            'status' => 'new',
        ]);
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return Carbon::today();
        }

        // If it's a numeric Excel date (serial number)
        if (is_numeric($date)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
        }

        // Try to parse as string (handles "1-Dec-2025" format)
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return Carbon::today();
        }
    }
}
