<?php

namespace App\Imports;

use App\Models\Postcode;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PostcodesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithUpserts, WithUpsertColumns
{
    use importable;

    /**
     * @param array $row
     * @return Postcode
     */
    public function model(array $row): Postcode
    {
        return new Postcode([
            'out_code' => trim(strtok($row['pcd2'], ' ')),
            'in_code' => substr($row['pcd2'], -3),
            'latitude' => $row['lat'],
            'longitude' => $row['long']
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function upsertColumns(): array
    {
        return ['latitude', 'longitude'];
    }

    public function uniqueBy(): array
    {
        return ['out_code', 'in_code'];
    }
}
