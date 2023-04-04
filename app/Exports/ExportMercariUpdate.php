<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ExportMercariUpdate implements FromArray, WithHeadings, WithCustomCsvSettings
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

    public function headings(): array
    {
        return [
            '商品ID',
            'スナップショットID',
            '商品画像名_1',
            '商品画像更新フラグ_1',
            '商品画像登録有無_1',
            '商品画像名_2',
            '商品画像更新フラグ_2',
            '商品画像登録有無_2',
            '商品画像名_3',
            '商品画像更新フラグ_3',
            '商品画像登録有無_3',
            '商品画像名_4',
            '商品画像更新フラグ_4',
            '商品画像登録有無_4',
            '商品画像名_5',
            '商品画像更新フラグ_5',
            '商品画像登録有無_5',
            '商品画像名_6',
            '商品画像更新フラグ_6',
            '商品画像登録有無_6',
            '商品画像名_7',
            '商品画像更新フラグ_7',
            '商品画像登録有無_7',
            '商品画像名_8',
            '商品画像更新フラグ_8',
            '商品画像登録有無_8',
            '商品画像名_9',
            '商品画像更新フラグ_9',
            '商品画像登録有無_9',
            '商品画像名_10',
            '商品画像更新フラグ_10',
            '商品画像登録有無_10',
            '商品名',
            '商品説明',
            'SKU1_ID',
            'SKU1_スナップショットID',
            'SKU1_種類',
            'SKU1_現在の在庫数',
            'SKU1_増減フラグ',
            'SKU1_在庫増減数',
            'SKU1_商品管理コード',
            'SKU1_JANコード',
            'SKU2_ID',
            'SKU2_スナップショットID',
            'SKU2_種類',
            'SKU2_現在の在庫数',
            'SKU2_増減フラグ',
            'SKU2_在庫増減数',
            'SKU2_商品管理コード',
            'SKU2_JANコード',
            'SKU3_ID',
            'SKU3_スナップショットID',
            'SKU3_種類',
            'SKU3_現在の在庫数',
            'SKU3_増減フラグ',
            'SKU3_在庫増減数',
            'SKU3_商品管理コード',
            'SKU3_JANコード',
            'SKU4_ID',
            'SKU4_スナップショットID',
            'SKU4_種類',
            'SKU4_現在の在庫数',
            'SKU4_増減フラグ',
            'SKU4_在庫増減数',
            'SKU4_商品管理コード',
            'SKU4_JANコード',
            'SKU5_ID',
            'SKU5_スナップショットID',
            'SKU5_種類',
            'SKU5_現在の在庫数',
            'SKU5_増減フラグ',
            'SKU5_在庫増減数',
            'SKU5_商品管理コード',
            'SKU5_JANコード',
            'SKU6_ID',
            'SKU6_スナップショットID',
            'SKU6_種類',
            'SKU6_現在の在庫数',
            'SKU6_増減フラグ',
            'SKU6_在庫増減数',
            'SKU6_商品管理コード',
            'SKU6_JANコード',
            'SKU7_ID',
            'SKU7_スナップショットID',
            'SKU7_種類',
            'SKU7_現在の在庫数',
            'SKU7_増減フラグ',
            'SKU7_在庫増減数',
            'SKU7_商品管理コード',
            'SKU7_JANコード',
            'SKU8_ID',
            'SKU8_スナップショットID',
            'SKU8_種類',
            'SKU8_現在の在庫数',
            'SKU8_増減フラグ',
            'SKU8_在庫増減数',
            'SKU8_商品管理コード',
            'SKU8_JANコード',
            'SKU9_ID',
            'SKU9_スナップショットID',
            'SKU9_種類',
            'SKU9_現在の在庫数',
            'SKU9_増減フラグ',
            'SKU9_在庫増減数',
            'SKU9_商品管理コード',
            'SKU9_JANコード',
            'SKU10_ID',
            'SKU10_スナップショットID',
            'SKU10_種類',
            'SKU10_現在の在庫数',
            'SKU10_増減フラグ',
            'SKU10_在庫増減数',
            'SKU10_商品管理コード',
            'SKU10_JANコード',
            'ブランドID',
            '販売価格',
            'カテゴリID',
            '商品の状態',
            '配送方法',
            '発送元の地域',
            '発送までの日数',
            '商品ステータス',
            '商品登録日時',
            '最終更新日時',
            'Hash'
        ];
    }

    public function array(): array
    {
        return $this->invoices;
    }
}
