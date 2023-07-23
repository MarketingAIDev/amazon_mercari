<?php

namespace App\Imports;

use App\Models\MercariUpdate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class UpdateMercari implements ToModel, WithStartRow, WithCustomCsvSettings

{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $exist = MercariUpdate::where('SKU1_product_management_code', $row[40])->where('user_id', Auth::id())->first();
        if ($exist == null) {
            return new MercariUpdate([
                'user_id' => Auth::id(),
                'product_id' => $row[0],
                'snapshot_id' => $row[1],
                'image_n_1' => $row[2],
                'image_u_1' => $row[3],
                'image_r_1' => $row[4],
                'image_n_2' => $row[5],
                'image_u_2' => $row[6],
                'image_r_2' => $row[7],
                'image_n_3' => $row[8],
                'image_u_3' => $row[9],
                'image_r_3' => $row[10],
                'image_n_4' => $row[11],
                'image_u_4' => $row[12],
                'image_r_4' => $row[13],
                'image_n_5' => $row[14],
                'image_u_5' => $row[15],
                'image_r_5' => $row[16],
                'image_n_6' => $row[17],
                'image_u_6' => $row[18],
                'image_r_6' => $row[19],
                'image_n_7' => $row[20],
                'image_u_7' => $row[21],
                'image_r_7' => $row[22],
                'image_n_8' => $row[23],
                'image_u_8' => $row[24],
                'image_r_8' => $row[25],
                'image_n_9' => $row[26],
                'image_u_9' => $row[27],
                'image_r_9' => $row[28],
                'image_n_10' => $row[29],
                'image_u_10' => $row[30],
                'image_r_10' => $row[31],
                'product_name' => json_encode($row[32]),
                'feature' => json_encode($row[33]),
                'SKU1_id' => $row[34],
                'SKU1_Snapshot_id' => $row[35],
                'SKU1_Type' => $row[36],
                'SKU1_current_inventory' => $row[37],
                'SKU1_increase' => $row[38],
                'SKU1_stock_increase' => $row[39],
                'SKU1_product_management_code' => $row[40],
                'SKU1_JAN_code' => $row[41],
                'SKU2_id' => $row[42],
                'SKU2_Snapshot_id' => $row[43],
                'SKU2_Type' => $row[44],
                'SKU2_current_inventory' => $row[45],
                'SKU2_increase' => $row[46],
                'SKU2_stock_increase' => $row[47],
                'SKU2_product_management_code' => $row[48],
                'SKU2_JAN_code' => $row[49],
                'SKU3_id' => $row[50],
                'SKU3_Snapshot_id' => $row[51],
                'SKU3_Type' => $row[52],
                'SKU3_current_inventory' => $row[53],
                'SKU3_increase' => $row[54],
                'SKU3_stock_increase' => $row[55],
                'SKU3_product_management_code' => $row[56],
                'SKU3_JAN_code' => $row[57],
                'SKU4_id' => $row[58],
                'SKU4_Snapshot_id' => $row[59],
                'SKU4_Type' => $row[60],
                'SKU4_current_inventory' => $row[61],
                'SKU4_increase' => $row[62],
                'SKU4_stock_increase' => $row[63],
                'SKU4_product_management_code' => $row[64],
                'SKU4_JAN_code' => $row[65],
                'SKU5_id' => $row[66],
                'SKU5_Snapshot_id' => $row[67],
                'SKU5_Type' => $row[68],
                'SKU5_current_inventory' => $row[69],
                'SKU5_increase' => $row[70],
                'SKU5_stock_increase' => $row[71],
                'SKU5_product_management_code' => $row[72],
                'SKU5_JAN_code' => $row[73],
                'SKU6_id' => $row[74],
                'SKU6_Snapshot_id' => $row[75],
                'SKU6_Type' => $row[76],
                'SKU6_current_inventory' => $row[77],
                'SKU6_increase' => $row[78],
                'SKU6_stock_increase' => $row[79],
                'SKU6_product_management_code' => $row[80],
                'SKU6_JAN_code' => $row[81],
                'SKU7_id' => $row[82],
                'SKU7_Snapshot_id' => $row[83],
                'SKU7_Type' => $row[84],
                'SKU7_current_inventory' => $row[85],
                'SKU7_increase' => $row[86],
                'SKU7_stock_increase' => $row[87],
                'SKU7_product_management_code' => $row[88],
                'SKU7_JAN_code' => $row[89],
                'SKU8_id' => $row[90],
                'SKU8_Snapshot_id' => $row[91],
                'SKU8_Type' => $row[92],
                'SKU8_current_inventory' => $row[93],
                'SKU8_increase' => $row[94],
                'SKU8_stock_increase' => $row[95],
                'SKU8_product_management_code' => $row[96],
                'SKU8_JAN_code' => $row[97],
                'SKU9_id' => $row[98],
                'SKU9_Snapshot_id' => $row[99],
                'SKU9_Type' => $row[100],
                'SKU9_current_inventory' => $row[101],
                'SKU9_increase' => $row[102],
                'SKU9_stock_increase' => $row[103],
                'SKU9_product_management_code' => $row[104],
                'SKU9_JAN_code' => $row[105],
                'SKU10_id' => $row[106],
                'SKU10_Snapshot_id' => $row[107],
                'SKU10_Type' => $row[108],
                'SKU10_current_inventory' => $row[109],
                'SKU10_increase' => $row[110],
                'SKU10_stock_increase' => $row[111],
                'SKU10_product_management_code' => $row[112],
                'SKU10_JAN_code' => $row[113],
                'brand_id' => $row[114],
                'Selling_price' => $row[115],
                'category_id' => $row[116],
                'commodity' => $row[117],
                'Shipping_method' => $row[118],
                'region_origin' => $row[119],
                'days_ship' => $row[120],
                'product_status' => $row[121],
                'product_registration_time' => $row[122],
                'last_modified' => $row[123],
                'hash' => $row[124],
                're_entry' => 14,
            ]);
        }
    }
    public function getCsvSettings(): array
    {
        # Define your custom import settings for only this class
        return [
            'input_encoding' => 'SHIFT-JIS',
            'delimiter' => null
        ];
    }
}
