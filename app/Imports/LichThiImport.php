<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Services\Admin\Import_Excel\LichThiService;

class LichThiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private array $rowErrors = [];

    protected LichThiService $service;

    public function __construct(LichThiService $service)
    {
        $this->service = $service;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $data = $row->toArray();

            try {

                $this->service->handle($data);

            } catch (\Exception $e) {

                $this->rowErrors[] = [
                    'row_number' =>  $index + 2,
                    'errors' => [$e->getMessage()]
                ];
                
            }
        }
    }

    public function getRowErrors(): array
    {
        return $this->rowErrors;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}