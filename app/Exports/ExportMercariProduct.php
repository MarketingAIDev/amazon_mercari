<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ExportMercariProduct implements FromArray,WithHeadings,WithCustomCsvSettings
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function getCsvSettings(): array
    {
        return [
            'output_encoding' => 'Shift-JIS',
        ];
    }

    public function headings():array{
        return[
            '商品画像名_1',
            '商品画像名_2',
            '商品画像名_3',
            '商品画像名_4',
            '商品画像名_5',
            '商品画像名_6',
            '商品画像名_7',
            '商品画像名_8',
            '商品画像名_9',
            '商品画像名_10',
            '商品名',
            '商品説明',
            'SKU1_種類',
            'SKU1_在庫数',
            'SKU1_商品管理コード',
            'SKU1_JANコード',
            'SKU2_種類',
            'SKU2_在庫数',
            'SKU2_商品管理コード',
            'SKU2_JANコード',
            'SKU3_種類',
            'SKU3_在庫数',
            'SKU3_商品管理コード',
            'SKU3_JANコード',
            'SKU4_種類',
            'SKU4_在庫数',
            'SKU4_商品管理コード',
            'SKU4_JANコード',
            'SKU5_種類',
            'SKU5_在庫数',
            'SKU5_商品管理コード',
            'SKU5_JANコード',
            'SKU6_種類',
            'SKU6_在庫数',
            'SKU6_商品管理コード',
            'SKU6_JANコード',
            'SKU7_種類',
            'SKU7_在庫数',
            'SKU7_商品管理コード',
            'SKU7_JANコード',
            'SKU8_種類',
            'SKU8_在庫数',
            'SKU8_商品管理コード',
            'SKU8_JANコード',
            'SKU9_種類',
            'SKU9_在庫数',
            'SKU9_商品管理コード',
            'SKU9_JANコード',
            'SKU10_種類',
            'SKU10_在庫数',
            'SKU10_商品管理コード',
            'SKU10_JANコード',
            'ブランドID',
            '販売価格',
            'カテゴリID',
            '商品の状態',
            '配送方法',
            '発送元の地域',
            '発送までの日数',
            '商品ステータス'
        ];
    } 

    public function array(): array
    {
        return $this->invoices;
    }
}
