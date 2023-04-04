<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ExportExhibition implements FromArray,WithHeadings
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    // public function getCsvSettings(): array
    // {
    //     return [
    //         'output_encoding' => 'Shift-JIS',
    //     ];
    // }

    public function headings():array{
        return[
            'SKU1_商品管理コード',
            'ASIN',
            '商品名',
            '商品説明文',
            '出品価格',
            'AMAZONカテゴリー',
            '送料',
            'その他費用',
            'AMAZONカテゴリー',
            'メルカリカテゴリー',
            'メルカリカテゴリーID'
        ];
    } 

    public function array(): array
    {
        return $this->invoices;
    }
}
