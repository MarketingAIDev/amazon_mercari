<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CropImageUploadController;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

// Homepage Route
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::post('/sendSectenceAction', [MypageController::class, 'sendSectenceAction'])->name('sendSectenceAction');

// Authentication Routes
Route::get('/signup/{role?}', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/loginview', [LoginController::class, 'loginview']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Plan Routes
Route::prefix('plan')->group(function () {
    Route::get('select', [PlanController::class, 'show'])->name('show_plan');
    Route::get('select/{id}', [PlanController::class, 'select'])->name('select_plan');
    Route::get('show', [PlanController::class, 'page'])->name('plan_page');
});


// Main Routes
Route::group(['middleware' => ['admin']], function () {
    Route::view('admin_page', 'mypage.admin_page')->name('admin_page');
});

Route::group(['middleware' => ['auth']], function () {
    //etc_function
    Route::get('etc_function', [ProductController::class, 'etc_function'])->name('etc_function');

    // admin
    Route::view('admin_page', 'mypage.admin_page')->name('admin_page');
    Route::get('delete_account', [MypageController::class, 'delete_account'])->name('delete_account');
    Route::get('permit_account', [MypageController::class, 'permit_account'])->name('permit_account');

    // base
    Route::view('index', 'components.base')->name('index');
    Route::get('base/edite/{id}', [DataController::class, 'amazon_edite'])->name('amazon_edite');
    Route::get('base_product_info/{id}', [DataController::class, 'product_info'])->name('product_info');
    Route::get('base_data', [DataController::class, 'base_data'])->name('base_data');
    Route::post('import_amazom_data', [DataController::class, 'import_amazom_data'])->name('import_amazom_data');
    Route::post('update_base_product', [DataController::class, 'update_base_product'])->name("update_base_product");
    Route::get('amazon/list', [DataController::class, 'amazon_list'])->name('amazon_list');
    Route::post('create_amazon_data', [MypageController::class, 'create_amazon_data'])->name("create_amazon_data");
    Route::post('remove_product', [ProductController::class, 'remove_product'])->name('remove_product');
    Route::post('amazon_remove_alldata', [MypageController::class, 'amazon_remove_alldata'])->name('amazon_remove_alldata');
    Route::post('select_remove_product', [MypageController::class, 'select_remove_product'])->name('select_remove_product');


    //entry
    Route::get('entry/list', [DataController::class, 'entry_list'])->name('entry_list');
    Route::get('entry/not/list', [DataController::class, 'not_entry_list'])->name('not_entry_list');
    Route::get('entry/data', [DataController::class, 'entry_data'])->name('entry_data');
    Route::get('delete_entry_data/{id}', [DataController::class, 'delete_entry_data'])->name('delete_entry_data');
    Route::get('entry/data_not', [DataController::class, 'entry_data_not'])->name('entry_data_not');
    Route::get('export/xlsx_entry', [DataController::class, 'export_xlsx_entry'])->name("export_xlsx_entry");
    Route::get('export/xlsx/not/entry', [DataController::class, 'export_xlsx_not_entry'])->name("export_xlsx_not_entry");
    Route::get('entry/condition', [DataController::class, 'entry_condition'])->name('entry_condition');
    Route::get('entry/setting_id', [MypageController::class, 'setting_category_id'])->name('setting_category_id');
    Route::post('create_exhibition_data', [DataController::class, 'create_exhibition_data'])->name("create_exhibition_data");

    // mercari 
    Route::get('export_mercari_csv/{from}/{to}/{start}/{end}', [DataController::class, 'export_mercari_csv'])->name("export_mercari_csv");
    Route::get('mercari_setting', [DataController::class, 'mercari_setting'])->name('mercari_setting');
    Route::get('mercari_list', [DataController::class, 'mercari_list'])->name("mercari_list");
    Route::get('mercari_list_products/{from}/{to}/{start}/{end}', [MypageController::class, 'mercari_list_products'])->name('mercari_list_products');
    Route::get('downloadIMG/{from}/{to}/{start}/{end}', [DataController::class, 'downloadIMG'])->name('downloadIMG');
    Route::post('mercari/exist/delete', [DataController::class, 'mercari_exist_delete'])->name("mercari_exist_delete");
    Route::post('export_mercari', [DataController::class, 'export_mercari'])->name("export_mercari");
    Route::post('mercari_product_setting_exist', [DataController::class, 'mercari_product_setting_exist'])->name("mercari_product_setting_exist");

    // mercari update
    Route::view('mercari_update/view/all', 'components.mercari_update_all')->name('mercari_update_all');
    Route::get('mercari_update', [DataController::class, 'mercari_update'])->name("mercari_update");
    Route::get('mercari_update_list/{from}/{to}/{start}/{end}', [MypageController::class, 'mercari_update_list'])->name('mercari_update_list');
    Route::get('mercari_update_allremove', [DataController::class, 'mercari_update_allremove'])->name('mercari_update_allremove');
    Route::get('/export/mercari/delete/csv/', [DataController::class, 'export_mercari_update_csv_delete'])->name("export_mercari_update_csv_delete");
    Route::get('export_mercari_update_csv/{from}/{to}/{start}/{end}', [DataController::class, 'export_mercari_update_csv'])->name("export_mercari_update_csv");
    Route::get('mercari_update_delete/{from}/{to}/{start}/{end}', [DataController::class, 'mercari_update_delete'])->name("mercari_update_delete");
    Route::get('mercari/update/list/datatable/{from}/{to}', [DataController::class, 'mercari_update_list_datatable'])->name("mercari_update_list_datatable");
    Route::get('mercari/update/datatable', [DataController::class, 'mercari_update_view'])->name("mercari_update_view");
    Route::post('update_mercari_import', [DataController::class, 'update_mercari_import'])->name("update_mercari_import");
    Route::post('mercari_update_setting', [DataController::class, 'mercari_update_setting'])->name("mercari_update_setting");
    Route::post('mercari_exist_setting', [DataController::class, 'mercari_exist_setting'])->name("mercari_exist_setting");
    Route::post('mercari_entry_update_setting', [DataController::class, 'mercari_entry_update_setting'])->name("mercari_entry_update_setting");
    Route::post('delete_mercari_update_data', [DataController::class, 'delete_mercari_update_data'])->name('delete_mercari_update_data');

    //setting category
    Route::get('entry/setting_category', [MypageController::class, 'setting_category'])->name('setting_category');
    Route::post('create_matching_categories', [MypageController::class, 'create_matching_categories'])->name('create_matching_categories');
    Route::post('create_category_id', [MypageController::class, 'create_category_id'])->name('create_category_id');
    Route::post('all_category_remove', [MypageController::class, 'all_category_remove'])->name('all_category_remove');
    Route::post('all_category_id_remove', [MypageController::class, 'all_category_id_remove'])->name('all_category_id_remove');
    Route::post('category_csv', [MypageController::class, 'category_csv'])->name("category_csv");
    Route::post('del_category', [MypageController::class, 'del_category'])->name('del_category');
    Route::post('del_category_id', [MypageController::class, 'del_category_id'])->name('del_category_id');
    Route::post('save_category', [MypageController::class, 'save_category'])->name("save_category");
    Route::post('save_postage', [MypageController::class, 'save_postage'])->name("save_postage");
    Route::post('save_category_id', [MypageController::class, 'save_category_id'])->name("save_category_id");
    Route::post('category_match', [MypageController::class, 'category_match'])->name("category_match");
    Route::post('update_category', [MypageController::class, 'update_category'])->name("update_category");
    Route::post('update_category_id', [MypageController::class, 'update_category_id'])->name("update_category_id");

    // setting postage
    Route::get('entry/setting_postage', [MypageController::class, 'setting_postage'])->name('setting_postage');
    Route::post('postage_remove', [MypageController::class, 'postage_remove'])->name("postage_remove");
    Route::get('entry/postage/del/{id}', [MypageController::class, 'delete_postage'])->name("delete_postage");
    Route::post('postage_xlsx', [MypageController::class, 'postage_xlsx'])->name("postage_xlsx");

    // setting price
    Route::get('entry/setting_price', [MypageController::class, 'setting_price'])->name("setting_price");
    Route::post('price_remove', [MypageController::class, 'price_remove'])->name("price_remove");
    Route::post('price_xlsx', [MypageController::class, 'price_xlsx'])->name("price_xlsx");

    // ng setting
    Route::get('entry/setting_ng', [DataController::class, 'entry_setting'])->name('entry_setting');
    Route::post('change_ng_categories', [MypageController::class, 'change_ng_categories'])->name("change_ng_categories");
    Route::post('change_ng_product', [MypageController::class, 'change_ng_product'])->name("change_ng_product");
    Route::post('change_ng_word', [MypageController::class, 'change_ng_word'])->name("change_ng_word");
    Route::post('change_setting', [MypageController::class, 'change_setting'])->name("change_setting");
    Route::post('change_setting_price', [MypageController::class, 'change_setting_price'])->name("change_setting_price");
    Route::post('change_price_reduction', [MypageController::class, 'change_price_reduction'])->name("change_price_reduction");
    Route::post('change_setting_price_date', [MypageController::class, 'change_setting_price_date'])->name("change_setting_price_date");
    Route::post('change_setting_sentence', [MypageController::class, 'change_setting_sentence'])->name("change_setting_sentence");

    // User Management Routes
    Route::post('change_pwd', [MypageController::class, 'change_pwd'])->name('change_pwd');
    Route::view('change_pwd', 'mypage.change_pwd')->name('change_pwd');
    Route::post('save_amazon_info', [MypageController::class, 'save_amazon_info'])->name('save_amazon_info');
    Route::get('users_profile', [MypageController::class, 'users_profile'])->name("users_profile");
    //user
    Route::post('check_pwd', [MypageController::class, 'check_pwd'])->name('check_pwd');
    Route::view('change_info', 'mypage.change_info')->name('change_info');
    Route::post('change_info', [MypageController::class, 'change_info'])->name('change_info');
    Route::post('change_line', [MypageController::class, 'change_line'])->name('change_line');
    Route::get('permit_account', [MypageController::class, 'permit_account'])->name('permit_account');
    Route::post('imgSave', [CropImageUploadController::class, 'store'])->name('imgSave');

    // mercari-shops delete product
    Route::view('delete/product', 'components.deleted_product')->name('deleted_product');
    Route::post('deleted_SKU1', [DataController::class, 'deleted_SKU1'])->name('deleted_SKU1');

    // Route::post('mercari_product_save', [DataController::class, 'mercari_product_save'])->name("mercari_product_save");
    // Route::get('register_product/{id}', [MypageController::class, 'register_product'])->name('register_product');
    // Route::get('register_product/{id}', [MypageController::class, 'register_product'])->name('register_product');
    // Route::get('list_product', [ProductController::class, 'list_product'])->name('list_product');
    // Route::post('delete_product', [ProductController::class, 'delete_product'])->name('delete_product');
    // Route::get('scan', [ProductController::class, 'scan'])->name('scan');
    // Route::get('csv_down', [ProductController::class, 'csv_down'])->name('csv_down');
    // Route::get('stop', [ProductController::class, 'stop'])->name('stop');
    // Route::get('restart', [ProductController::class, 'restart'])->name('restart');
    // Route::view('notify_page',  'mypage.notify_page')->name('notify_page');
    // Route::get('edit_track', [ProductController::class, 'edit_track'])->name('edit_track');
});

Route::middleware(['cors'])->group(function () {
    Route::get('http://localhost:32768/');
});
