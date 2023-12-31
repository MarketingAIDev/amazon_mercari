<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercariUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'snapshot_id',
        'image_n_1',
        'image_n_2',
        'image_n_3',
        'image_n_4',
        'image_n_5',
        'image_n_6',
        'image_n_7',
        'image_n_8',
        'image_n_9',
        'image_n_10',
        'image_u_1',
        'image_u_2',
        'image_u_3',
        'image_u_4',
        'image_u_5',
        'image_u_6',
        'image_u_7',
        'image_u_8',
        'image_u_9',
        'image_u_10',
        'image_r_1',
        'image_r_2',
        'image_r_3',
        'image_r_4',
        'image_r_5',
        'image_r_6',
        'image_r_7',
        'image_r_8',
        'image_r_9',
        'image_r_10',
        'product_name',
        'feature',
        'SKU1_id',
        'SKU2_id',
        'SKU3_id',
        'SKU4_id',
        'SKU5_id',
        'SKU6_id',
        'SKU7_id',
        'SKU8_id',
        'SKU9_id',
        'SKU10_id',
        'SKU1_Snapshot_id',
        'SKU2_Snapshot_id',
        'SKU3_Snapshot_id',
        'SKU4_Snapshot_id',
        'SKU5_Snapshot_id',
        'SKU6_Snapshot_id',
        'SKU7_Snapshot_id',
        'SKU8_Snapshot_id',
        'SKU9_Snapshot_id',
        'SKU10_Snapshot_id',
        'SKU1_Type',
        'SKU2_Type',
        'SKU3_Type',
        'SKU4_Type',
        'SKU5_Type',
        'SKU6_Type',
        'SKU7_Type',
        'SKU8_Type',
        'SKU9_Type',
        'SKU10_Type',
        'SKU1_current_inventory',
        'SKU2_current_inventory',
        'SKU3_current_inventory',
        'SKU4_current_inventory',
        'SKU5_current_inventory',
        'SKU6_current_inventory',
        'SKU7_current_inventory',
        'SKU8_current_inventory',
        'SKU9_current_inventory',
        'SKU10_current_inventory',
        'SKU1_increase',
        'SKU2_increase',
        'SKU3_increase',
        'SKU4_increase',
        'SKU5_increase',
        'SKU6_increase',
        'SKU7_increase',
        'SKU8_increase',
        'SKU9_increase',
        'SKU10_increase',
        'SKU1_stock_increase',
        'SKU2_stock_increase',
        'SKU3_stock_increase',
        'SKU4_stock_increase',
        'SKU5_stock_increase',
        'SKU6_stock_increase',
        'SKU7_stock_increase',
        'SKU8_stock_increase',
        'SKU9_stock_increase',
        'SKU10_stock_increase',
        'SKU1_product_management_code',
        'SKU2_product_management_code',
        'SKU3_product_management_code',
        'SKU4_product_management_code',
        'SKU5_product_management_code',
        'SKU6_product_management_code',
        'SKU7_product_management_code',
        'SKU8_product_management_code',
        'SKU9_product_management_code',
        'SKU10_product_management_code',
        'SKU1_JAN_code',
        'SKU2_JAN_code',
        'SKU3_JAN_code',
        'SKU4_JAN_code',
        'SKU5_JAN_code',
        'SKU6_JAN_code',
        'SKU7_JAN_code',
        'SKU8_JAN_code',
        'SKU9_JAN_code',
        'SKU10_JAN_code',
        'brand_id',
        'Selling_price',
        'category_id',
        'commodity',
        'Shipping_method',
        'region_origin',
        'days_ship',
        'product_status',
        'product_registration_time',
        'last_modified',
        'hash',
        're_entry'
    ];
}
