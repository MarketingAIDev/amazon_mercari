<?php

namespace App\Imports;

use App\Models\AmazonProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AmazomProductImport implements ToModel, WithBatchInserts, WithChunkReading, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
    
        return new AmazonProduct([
            'user_id' => Auth::user()->id,
            'image' => $row[0] ?? '',
            'ASIN' => $row[1],
            'prime' => $row[2] ?? '',
            'product' => json_encode($row[3]),
            'attribute' => isset($row[4]) ? json_encode($row[4]) : '',
            'feature_1' => isset($row[5]) ? json_encode($row[5]) : '',
            'feature_2' => isset($row[6]) ? json_encode($row[6]) : '',
            'feature_3' => isset($row[7]) ? json_encode($row[7]) : '',
            'feature_4' => isset($row[8]) ? json_encode($row[8]) : '',
            'feature_5' => isset($row[9]) ? json_encode($row[9]) : '',
            'feature' => isset($row[10]) ? json_encode($row[10]) : '',
            'price' => 0,
            'r_price' => 0,
            'rank' => $row[11] ?? '',
            'a_c_root' => $row[12] ?? '',
            'a_c_sub' => $row[13] ?? '',
            'a_c_tree' => $row[14] ?? '',
            'p_length' => $row[15] ?? '',
            'p_width' => $row[16] ?? '',
            'p_height' => $row[17] ?? '',
        ]);
    }
    public function batchSize(): int
    {
        return 500;
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
