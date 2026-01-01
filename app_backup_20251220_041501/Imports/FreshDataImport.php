<?php

namespace App\Imports;

use App\Models\FreshData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class FreshDataImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $importedCount = 0;
    private $skippedCount = 0;
    private $errors = [];
    private $failures = [];
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function model(array $row)
    {
        try {
            // Check if mobile number already exists
            $exists = FreshData::where('mobile', $row['mobile'])->exists();
            
            if ($exists) {
                $this->skippedCount++;
                $this->errors[] = "Mobile {$row['mobile']} already exists - skipped";
                return null;
            }

            $this->importedCount++;

            return new FreshData([
                'mobile' => $row['mobile'],
                'name' => 'Imported Lead',
                'source' => $this->source,
                'assigned_to' => Auth::id(),
                'welcome_call' => false,
                'profile_created' => false,
                'photo_uploaded' => false,
            ]);
        } catch (\Exception $e) {
            $this->skippedCount++;
            $this->errors[] = "Error processing row: " . $e->getMessage();
            Log::error('Import row error', ['row' => $row, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'mobile' => 'required|digits:10',
        ];
    }

    public function onError(Throwable $e)
    {
        $this->skippedCount++;
        $this->errors[] = $e->getMessage();
        Log::error('Import error', ['error' => $e->getMessage()]);
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedCount++;
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFailures(): array
    {
        return $this->failures;
    }
}
