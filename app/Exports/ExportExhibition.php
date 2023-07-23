<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ExportExhibition implements FromArray, WithHeadings
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

    public function headings(): array
    {
        return [
            '法人・施設名',
            '仕事内容',
            'サービス形態',
            '給与',
            '給与の備考',
            '待遇',
            '勤務時間',
            '休日',
            '長期休暇/特別休暇',
            'AMAZONカテゴリー',
            'アクセス',
        ];
    }

    public function array(): array
    {
        return $this->invoices;
    }
}
