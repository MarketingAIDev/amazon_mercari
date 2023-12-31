<?php

namespace App\Http\Controllers;

use App\Models\AmazonProduct;
use App\Models\NgCategory;
use App\Models\NgProduct;
use App\Models\NgWord;
use App\Models\Category;
use App\Models\CategoryId;
use App\Models\Postage;
use App\Models\MercariProduct;
use App\Models\Price;
use App\Models\Exhibition;
use App\Models\Setting;
use App\Models\MercariUpdate;
use App\Models\Job;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportExhibition;
use App\Exports\ExportMercariProduct;
use App\Exports\ExportMercariUpdate;
use App\Exports\NotExhibition;
use App\Imports\AmazomProductImport;
use App\Imports\UpdateMercari;
use DataTables;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use File;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;
use ZipArchive;


class DataController extends Controller
{
	public function downloadIMG(Request $request, $from, $to, $start, $end)
	{
		$zip = new ZipArchive;
		$directory = public_path() . "/" . Auth::id() . '/' . ceil($start / 1000) . "/";
		// $fileName = Auth::user()->family_name . '.zip';
		$downloadImgName = 'MERCARI_UPLOAD' . $start . '_' . $end . '.zip';
		if (!File::exists($directory)) {
			return redirect()->route('mercari_list');
			// return view('components.mercari_register', ['mercari_products' => $mercari_products, 'error' => 'no']);
		}
		if ($zip->open(public_path(Auth::id() . '/' . $downloadImgName), ZipArchive::CREATE) === true) {
			$files = File::files(public_path(Auth::id() . '/' . ceil($start / 1000) . "/"));
			foreach ($files as $key => $value) {
				$relativeNameInZipFile = basename($value);
				$zip->addFile($value, $relativeNameInZipFile);
			}

			$zip->close();
		}
		return response()->download(public_path(Auth::id() . '/' . $downloadImgName));
	}

	public function mercari_list()
	{
		$exhibition_data = MercariProduct::select('id')->where('user_id', '=', Auth::id())->orderBy('id', 'asc')->get();
		if (count($exhibition_data) != 0) {
			return view('components.mercari_list', ['exhibition_data' => $exhibition_data]);
		} else {
			// return back()->withErrors(['upload' => '未出品商品リストに商品がありません。出品データの管理から出品商品登録ボタンをクリックしてください。']);
			return redirect('entry/data')->withErrors(['upload' => '未出品商品リストに商品がありません。出品データの管理から出品商品登録ボタンをクリックしてください。']);
		}
	}

	public function mercari_update()
	{
		$mercari_updates = MercariUpdate::select('id')->where('user_id', '=', Auth::id())->orderBy('id', 'asc')->get();
		return view('components.mercari_update', ['mercari_updates' => $mercari_updates]);
	}

	public function mercari_update_allremove()
	{
		$mercari_all_remove = MercariUpdate::where('user_id', Auth::id())->get();
		foreach ($mercari_all_remove as $all) {
			$all->product_status = 3;
			$all->save();
		}
		return redirect()->route('mercari_update');
	}

	public function update_mercari_import(Request $request)
	{
		$files = $request['file'];
		if ($files == null) {
			return back()->withErrors(['error' => 'ファイルが選択されていません。']);
		} else {
			foreach ($files as $file) {
				Excel::import(new UpdateMercari, $file->store('files'));
			}
			return back()->withErrors(['info' => '商品保管が成功しました。']);
		}
		// Excel::import(new UpdateMercari, $request->file('file')->store('files'));
		// return redirect()->back();
	}

	public function base_data(Request $request)
	{
		$products = AmazonProduct::where('user_id', Auth::user()->id)->where('flag', 1)->orderBy('id', 'desc')->paginate(10);
		return view('components.base');
		// return view('components.base', ['products' => $products]);
	}

	public function product_info(Request $request, $id)
	{
		$product = AmazonProduct::find($id);
		return view('components.product_info', ['product' => $product]);
	}
	public function amazon_edite(Request $request, $id)
	{
		$product = AmazonProduct::find($id);
		return view('components.amazon_edite', ['product' => $product]);
	}

	public function update_base_product(Request $request)
	{
		$req = $request['change'];
		$product = AmazonProduct::find($req['p_id']);
		$product->product = json_encode($req['product']);
		$product->attribute = $req['attribute'] == "" ? "" : json_encode($req['attribute']);
		$product->feature = json_encode($req['feature']) ?? '';
		$product->feature_1 = json_encode($req['feature_1']);
		$product->feature_2 = json_encode($req['feature_2']);
		$product->feature_3 = json_encode($req['feature_3']);
		$product->feature_4 = json_encode($req['feature_4']);
		$product->feature_5 = json_encode($req['feature_5']);
		$product->rank = $req['rank'];
		$product->p_length = $req['p_length'];
		$product->p_width = $req['p_width'];
		$product->price = $req['price'];
		$product->r_price = $req['price'];
		$product->p_height = $req['p_height'];
		$product->image = $req['image'];
		// $product->a_c_root = $req['a_c_root'];
		// $product->a_c_sub = $req['a_c_sub'];
		// $product->a_c_root = $req['a_c_root'];
		$product->save();
		return redirect()->route('base_data');
	}

	public function entry_data(Request $request)
	{
		// $exhibitions = Exhibition::where('user_id', Auth::user()->id)->where('exclusion', '')->paginate(10);
		return view('components.entry');
	}

	public function entry_data_not(Request $request)
	{
		// $exhibitions = Exhibition::where('user_id', Auth::user()->id)->where('exclusion', '!=', '')->paginate(10);
		return view('components.not_entry');
	}

	public function entry_setting(Request $request)
	{
		$exhibition = Exhibition::where('user_id', Auth::user()->id)->get();
		// if ( isset($exhibition) ) {
		// 	return redirect()->route("entry_data");
		// }
		$Ngwords = NgWord::where('user_id', Auth::user()->id)->get();
		$Ngcategories = NgCategory::where('user_id', Auth::user()->id)->get();
		$Ngproducts = NgProduct::where('user_id', Auth::user()->id)->get();
		$setting = Setting::where('user_id', Auth::user()->id)->get();
		return view('components.entry_setting', [
			'Ngproducts' => $Ngproducts,
			'Ngcategories' => $Ngcategories,
			'Ngwords' => $Ngwords,
			'setting' => $setting
		]);
	}

	public function deleted_SKU1(Request $request)
	{
		$reqData = $request->all();
		$SKU1_code = $reqData['deleted_product'];
		foreach ($SKU1_code as $code) {
			$mercari_update = MercariUpdate::where('SKU1_product_management_code', $code)->first();
			if ($mercari_update) {
				MercariUpdate::where('SKU1_product_management_code', $code)->delete();
			}
			$mercari_product = MercariProduct::where('SKU1_management', $code)->first();
			if ($mercari_product) {
				MercariProduct::where('SKU1_management', $code)->delete();
			}
		}
		return $SKU1_code;
	}

	public function mercari_product_setting_exist(Request $request)
	{
		$mercari_product_exist_setting = MercariProduct::where('user_id', Auth::id())->first();
		return $mercari_product_exist_setting;
	}

	public function entry_condition(Request $request)
	{
		$exhibition = Exhibition::where('user_id', Auth::user()->id)->get();
		if (count($exhibition) > 0) {
			return redirect()->route("entry_data");
		}
		$Ngwords = NgWord::where('user_id', Auth::user()->id)->get();
		$Ngcategories = NgCategory::where('user_id', Auth::user()->id)->get();
		$Ngproducts = NgProduct::where('user_id', Auth::user()->id)->get();
		$setting = Setting::where('user_id', Auth::user()->id)->get();
		return view('components.entry_setting', [
			'Ngproducts' => $Ngproducts,
			'Ngcategories' => $Ngcategories,
			'Ngwords' => $Ngwords,
			'setting' => $setting
		]);
	}

	public function create_exhibition_data($amazon_data)
	{
		Exhibition::where('user_id', Auth::user()->id)->delete();

		$patt = [
			"）",
			")",
			"｝",
			"}",
			"］",
			"】",
			"〕",
			"〙",
			"〛",
			"」",
			"』",
			"]",
			"〉",
			"›",
			"》",
			"、",
			"。",
			"»",
			" ",
			"”",
			"～",
		];
		$condition = Setting::where('user_id', Auth::id())->get();
		$Ngcategories = NgCategory::where('user_id', Auth::user()->id)->get();
		$Ngproducts = NgProduct::where('user_id', Auth::user()->id)->get();
		$Ngwords = NgWord::where('user_id', Auth::user()->id)->get();
		$setting = Setting::where('user_id', Auth::id())->first();
		$criteria = Postage::where('user_id', Auth::id())->get();
		$len = count($criteria);
		$number = 1;
		DB::beginTransaction();
		try {
			foreach ($amazon_data as $ap) {
				$ngCategoriesSearchArray = [];
				$ngProductSearchString = json_decode($ap->product) . json_decode($ap->feature) . json_decode($ap->feature_1) . json_decode($ap->feature_2) . json_decode($ap->feature_3) . json_decode($ap->feature_4) . json_decode($ap->feature_5);
				$exclusion = '';
				$m_category = '';
				$m_category_id = '';
				$exclusion = '';
				$product = json_decode($ap->product);
				$feature = json_decode($ap->feature);
				$feature_1 = json_decode($ap->feature_1);
				$feature_2 = json_decode($ap->feature_2);
				$feature_3 = json_decode($ap->feature_3);
				$feature_4 = json_decode($ap->feature_4);
				$feature_5 = json_decode($ap->feature_5);
				$profit = Price::where('down', '<=', $ap->price)->where('up', '>=', $ap->price)->get();
				$m_category_match = Category::where('a_c_root', $ap->a_c_root)->where('a_c_sub', $ap->a_c_sub)->first();
				//ng category setting
				array_push($ngCategoriesSearchArray, $ap->a_c_root, $ap->a_c_sub, $ap->a_c_tree);
				foreach ($Ngcategories as $ngC) {
					if (in_array($ngC->category, $ngCategoriesSearchArray)) {
						$exclusion .= '<span class="badge bg-light-warning">NGカテゴリ</span><br />';
					}
				}
				//ng word setting
				foreach ($Ngproducts as $ngP) {
					$pattern = '/' . $ngP->product . '/i';
					if (preg_match($pattern, $ngProductSearchString)) {
						$exclusion .= '<span class="badge bg-light-warning">NGワード</span><br />';
					}
				}
				//delete word setting in product and feature ...
				foreach ($Ngwords as $ngw) {
					$pattern = "/" . $ngw->word . "/i";
					$product = preg_replace($pattern, '', $product);
					$feature = preg_replace($pattern, '', $feature);
					$feature_1 = preg_replace($pattern, '', $feature_1);
					$feature_2 = preg_replace($pattern, '', $feature_2);
					$feature_3 = preg_replace($pattern, '', $feature_3);
					$feature_4 = preg_replace($pattern, '', $feature_4);
					$feature_5 = preg_replace($pattern, '', $feature_5);
				}
				if (!isset($m_category_match)) {
					$exclusion .= '<span class="badge bg-light-warning">対象カテゴリーなし</span><br />';
				} else {
					$m_category =  $m_category_match->m_category;
					if ($m_category == '削除') {
						$exclusion .= '<span class="badge bg-light-warning">削除</span><br />';
					}
					$match_m_category_id = CategoryId::where('all_category', $m_category)->where('user_id', $ap->user_id)->first();
					if (isset($match_m_category_id)) {
						$m_category_id = $match_m_category_id->category_id;
					}
				}
				// product_name of option
				$options = json_decode($ap->attribute);
				if ($options) {
					$a = explode(';', $options);
					$r = '';
					for ($i = 0; $i < count($a); $i++) {
						$b = explode(":", $a[$i]);
						$r .= $b[1] ?? "";
					}
					if (strlen($r) < 40) {
						if ($condition[0]['mark']) {
							$product = '★' . $r . '★' . $product;
						} else {
							$product = $r . $product;
						}
					}
				}
				if (strlen($product) > 40) {
					$temp = mb_substr($product, 0, 40);
					$product_split = 0;
					for ($i = 0; $i < count($patt); $i++) {
						$temp_1 = mb_strripos($temp, $patt[$i], 0);
						if ($temp_1 != false && $temp_1 > $product_split) {
							$product_split = $temp_1;
						}
					}
					$product = mb_substr($product, 0, $product_split);
				}
				//setting feature
				$feature = $setting->sentence . $product . $feature .  $feature_1 . $feature_2 .  $feature_3 .  $feature_4 .  $feature_5 .  $options;
				if (strlen($feature) > 1000) {
					$temp = mb_substr($feature, 0, 1000);
					$feature_split = 0;
					for ($i = 0; $i < count($patt); $i++) {
						$temp_1 = mb_strripos($temp, $patt[$i], 0);
						if ($temp_1 != false && $temp_1 > $feature_split) {
							$feature_split = $temp_1;
						}
					}
					$feature = mb_substr($temp, 0, $feature_split);
				}
				//setting prime
				if ($condition[0]['prime']) {
					if ($ap->prime == 'no') {
						$exclusion .= '<span class="badge bg-light-warning">非prime</span><br />';
					}
				}
				//setting postage
				$postage = 448;
				if ($ap->p_width && $ap->p_length && $ap->p_height) {
					for ($i = 0; $i < $len; $i++) {
						if ($criteria[$i]['width'] > ($ap->p_width * 10) && $criteria[$i]['height'] > ($ap->p_height * 10) && $criteria[$i]['length'] > ($ap->p_length * 10)) {
							$postage = $criteria[$i]['final'];
							break;
						}
					}
				}
				//setting SKU1_code
				$m_code = null;
				if ($exclusion == '') {
					$m_code = 'MC' . substr('0000000', (floor(log($number, 10)) + 1)) . $number;
					$number++;
				}
				if (!isset($profit[0]->profit)) {
					$r_profit = 0;
				} else {

					$r_profit = $profit[0]->profit;
				}
				//start register
				$exhibition = new Exhibition;
				$exhibition->amazon_id = $ap->id;
				$exhibition->ASIN = $ap->ASIN;
				$exhibition->image = $ap->image;
				$exhibition->m_code = $m_code;
				$exhibition->product = json_encode($product);
				$exhibition->prime = $ap->prime;
				$exhibition->feature = json_encode($feature);
				$exhibition->a_category = $ap->a_c_tree;
				$exhibition->m_category = $m_category;
				$exhibition->m_category_id = $m_category_id;
				$exhibition->price = $ap->r_price;
				$exhibition->e_price = ((int)$ap->price + $r_profit + $postage + 100) * 1.1;
				$exhibition->postage = $postage;
				$exhibition->etc = 100;
				$exhibition->exclusion = $exclusion;
				$exhibition->user_id = Auth::user()->id;
				$exhibition->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
	}

	public function export_mercari_csv(Request $request, $from, $to, $start, $end)
	{
		$mercari_data = MercariProduct::where('user_id', Auth::id())->whereBetween('id', [$from, $to])->get();
		$mercari_product = [];
		$filename = 'MERCARI_UPLOAD' . $start . '_' . $end . '.csv';
		foreach ($mercari_data as $t) {
			array_push($mercari_product, [
				$t->image_1,
				$t->image_2,
				$t->image_3,
				$t->image_4,
				$t->image_5,
				$t->image_6,
				$t->image_7,
				$t->image_8,
				$t->image_9,
				$t->image_10,
				json_decode($t->product),
				json_decode($t->feature),
				$t->SKU1_type,
				($t->SKU1_inventory == 0) ? '0' : $t->SKU1_inventory,
				$t->SKU1_management,
				$t->SKU1_jan_code,
				$t->SKU2_type,
				$t->SKU2_inventory,
				$t->SKU2_management,
				$t->SKU2_jan_code,
				$t->SKU3_type,
				$t->SKU3_inventory,
				$t->SKU3_management,
				$t->SKU3_jan_code,
				$t->SKU4_type,
				$t->SKU4_inventory,
				$t->SKU4_management,
				$t->SKU4_jan_code,
				$t->SKU5_type,
				$t->SKU5_inventory,
				$t->SKU5_management,
				$t->SKU5_jan_code,
				$t->SKU6_type,
				$t->SKU6_inventory,
				$t->SKU6_management,
				$t->SKU6_jan_code,
				$t->SKU7_type,
				$t->SKU7_inventory,
				$t->SKU7_management,
				$t->SKU7_jan_code,
				$t->SKU8_type,
				$t->SKU8_inventory,
				$t->SKU8_management,
				$t->SKU8_jan_code,
				$t->SKU9_type,
				$t->SKU9_inventory,
				$t->SKU9_management,
				$t->SKU9_jan_code,
				$t->SKU10_type,
				$t->SKU10_inventory,
				$t->SKU10_management,
				$t->SKU10_jan_code,
				$t->brand_id,
				($t->selling_price != 0) ? $t->selling_price : '999999',
				$t->category_id,
				$t->commodity,
				$t->shipping_method,
				$t->region_origin,
				$t->day_ship,
				$t->product_status,
			]);
		}
		$export_csv = new ExportMercariProduct($mercari_product);
		return Excel::download($export_csv, $filename, \Maatwebsite\Excel\Excel::CSV);
	}

	public function export_mercari_update_csv(Request $request, $from, $to, $start, $end)
	{
		$mercari_update_data = MercariUpdate::where('user_id', Auth::id())->whereBetween('id', [$from, $to])->get();
		$mercari_update_product = [];
		$filename = 'MERCARI_UPDATE' . $start . '_' . $end . '.csv';
		foreach ($mercari_update_data as $t) {
			array_push($mercari_update_product, [
				$t->product_id,
				$t->snapshot_id,
				$t->image_n_1,
				$t->image_u_1,
				$t->image_r_1,
				$t->image_n_2,
				$t->image_u_2,
				$t->image_r_2,
				$t->image_n_3,
				$t->image_u_3,
				$t->image_r_3,
				$t->image_n_4,
				$t->image_u_4,
				$t->image_r_4,
				$t->image_n_5,
				$t->image_u_5,
				$t->image_r_5,
				$t->image_n_6,
				$t->image_u_6,
				$t->image_r_6,
				$t->image_n_7,
				$t->image_u_7,
				$t->image_r_7,
				$t->image_n_8,
				$t->image_u_8,
				$t->image_r_8,
				$t->image_n_9,
				$t->image_u_9,
				$t->image_r_9,
				$t->image_n_10,
				$t->image_u_10,
				$t->image_r_10,
				json_decode($t->product_name),
				json_decode($t->feature),
				$t->SKU1_id,
				$t->SKU1_Snapshot_id,
				$t->SKU1_Type,
				$t->SKU1_current_inventory,
				$t->SKU1_increase,
				($t->SKU1_stock_increase == 404) ? '0' : $t->SKU1_stock_increase,
				$t->SKU1_product_management_code,
				$t->SKU1_JAN_code,
				$t->SKU2_id,
				$t->SKU2_Snapshot_id,
				$t->SKU2_Type,
				$t->SKU2_current_inventory,
				$t->SKU2_increase,
				$t->SKU2_stock_increase,
				$t->SKU2_product_management_code,
				$t->SKU2_JAN_code,
				$t->SKU3_id,
				$t->SKU3_Snapshot_id,
				$t->SKU3_Type,
				$t->SKU3_current_inventory,
				$t->SKU3_increase,
				$t->SKU3_stock_increase,
				$t->SKU3_product_management_code,
				$t->SKU3_JAN_code,
				$t->SKU4_id,
				$t->SKU4_Snapshot_id,
				$t->SKU4_Type,
				$t->SKU4_current_inventory,
				$t->SKU4_increase,
				$t->SKU4_stock_increase,
				$t->SKU4_product_management_code,
				$t->SKU4_JAN_code,
				$t->SKU5_id,
				$t->SKU5_Snapshot_id,
				$t->SKU5_Type,
				$t->SKU5_current_inventory,
				$t->SKU5_increase,
				$t->SKU5_stock_increase,
				$t->SKU5_product_management_code,
				$t->SKU5_JAN_code,
				$t->SKU6_id,
				$t->SKU6_Snapshot_id,
				$t->SKU6_Type,
				$t->SKU6_current_inventory,
				$t->SKU6_increase,
				$t->SKU6_stock_increase,
				$t->SKU6_product_management_code,
				$t->SKU6_JAN_code,
				$t->SKU7_id,
				$t->SKU7_Snapshot_id,
				$t->SKU7_Type,
				$t->SKU7_current_inventory,
				$t->SKU7_increase,
				$t->SKU7_stock_increase,
				$t->SKU7_product_management_code,
				$t->SKU7_JAN_code,
				$t->SKU8_id,
				$t->SKU8_Snapshot_id,
				$t->SKU8_Type,
				$t->SKU8_current_inventory,
				$t->SKU8_increase,
				$t->SKU8_stock_increase,
				$t->SKU8_product_management_code,
				$t->SKU8_JAN_code,
				$t->SKU9_id,
				$t->SKU9_Snapshot_id,
				$t->SKU9_Type,
				$t->SKU9_current_inventory,
				$t->SKU9_increase,
				$t->SKU9_stock_increase,
				$t->SKU9_product_management_code,
				$t->SKU9_JAN_code,
				$t->SKU10_id,
				$t->SKU10_Snapshot_id,
				$t->SKU10_Type,
				$t->SKU10_current_inventory,
				$t->SKU10_increase,
				$t->SKU10_stock_increase,
				$t->SKU10_product_management_code,
				$t->SKU10_JAN_code,
				$t->brand_id,
				$t->Selling_price,
				$t->category_id,
				$t->commodity,
				$t->Shipping_method,
				$t->region_origin,
				$t->days_ship,
				$t->product_status,
				$t->product_registration_time,
				$t->last_modified,
				$t->hash,
			]);
		}
		$export_csv = new ExportMercariUpdate($mercari_update_product);
		return Excel::download($export_csv, $filename, \Maatwebsite\Excel\Excel::CSV, [
			'Content-Type' => 'text/csv',
		]);
	}
	public function export_mercari_update_csv_delete(Request $request)
	{
		$mercari_update_csv_delete = MercariUpdate::where('user_id', Auth::id())->where('product_status', '3')->get();
		$mercari_update_product = [];
		$filename = 'MERCARI_DELETE_PRODUCT' . '.csv';
		foreach ($mercari_update_csv_delete as $t) {
			array_push($mercari_update_product, [
				$t->product_id,
				$t->snapshot_id,
				$t->image_n_1,
				$t->image_u_1,
				$t->image_r_1,
				$t->image_n_2,
				$t->image_u_2,
				$t->image_r_2,
				$t->image_n_3,
				$t->image_u_3,
				$t->image_r_3,
				$t->image_n_4,
				$t->image_u_4,
				$t->image_r_4,
				$t->image_n_5,
				$t->image_u_5,
				$t->image_r_5,
				$t->image_n_6,
				$t->image_u_6,
				$t->image_r_6,
				$t->image_n_7,
				$t->image_u_7,
				$t->image_r_7,
				$t->image_n_8,
				$t->image_u_8,
				$t->image_r_8,
				$t->image_n_9,
				$t->image_u_9,
				$t->image_r_9,
				$t->image_n_10,
				$t->image_u_10,
				$t->image_r_10,
				json_decode($t->product_name),
				json_decode($t->feature),
				$t->SKU1_id,
				$t->SKU1_Snapshot_id,
				$t->SKU1_Type,
				$t->SKU1_current_inventory,
				$t->SKU1_increase,
				($t->SKU1_stock_increase == 404) ? '0' : $t->SKU1_stock_increase,
				$t->SKU1_product_management_code,
				$t->SKU1_JAN_code,
				$t->SKU2_id,
				$t->SKU2_Snapshot_id,
				$t->SKU2_Type,
				$t->SKU2_current_inventory,
				$t->SKU2_increase,
				$t->SKU2_stock_increase,
				$t->SKU2_product_management_code,
				$t->SKU2_JAN_code,
				$t->SKU3_id,
				$t->SKU3_Snapshot_id,
				$t->SKU3_Type,
				$t->SKU3_current_inventory,
				$t->SKU3_increase,
				$t->SKU3_stock_increase,
				$t->SKU3_product_management_code,
				$t->SKU3_JAN_code,
				$t->SKU4_id,
				$t->SKU4_Snapshot_id,
				$t->SKU4_Type,
				$t->SKU4_current_inventory,
				$t->SKU4_increase,
				$t->SKU4_stock_increase,
				$t->SKU4_product_management_code,
				$t->SKU4_JAN_code,
				$t->SKU5_id,
				$t->SKU5_Snapshot_id,
				$t->SKU5_Type,
				$t->SKU5_current_inventory,
				$t->SKU5_increase,
				$t->SKU5_stock_increase,
				$t->SKU5_product_management_code,
				$t->SKU5_JAN_code,
				$t->SKU6_id,
				$t->SKU6_Snapshot_id,
				$t->SKU6_Type,
				$t->SKU6_current_inventory,
				$t->SKU6_increase,
				$t->SKU6_stock_increase,
				$t->SKU6_product_management_code,
				$t->SKU6_JAN_code,
				$t->SKU7_id,
				$t->SKU7_Snapshot_id,
				$t->SKU7_Type,
				$t->SKU7_current_inventory,
				$t->SKU7_increase,
				$t->SKU7_stock_increase,
				$t->SKU7_product_management_code,
				$t->SKU7_JAN_code,
				$t->SKU8_id,
				$t->SKU8_Snapshot_id,
				$t->SKU8_Type,
				$t->SKU8_current_inventory,
				$t->SKU8_increase,
				$t->SKU8_stock_increase,
				$t->SKU8_product_management_code,
				$t->SKU8_JAN_code,
				$t->SKU9_id,
				$t->SKU9_Snapshot_id,
				$t->SKU9_Type,
				$t->SKU9_current_inventory,
				$t->SKU9_increase,
				$t->SKU9_stock_increase,
				$t->SKU9_product_management_code,
				$t->SKU9_JAN_code,
				$t->SKU10_id,
				$t->SKU10_Snapshot_id,
				$t->SKU10_Type,
				$t->SKU10_current_inventory,
				$t->SKU10_increase,
				$t->SKU10_stock_increase,
				$t->SKU10_product_management_code,
				$t->SKU10_JAN_code,
				$t->brand_id,
				$t->Selling_price,
				$t->category_id,
				$t->commodity,
				$t->Shipping_method,
				$t->region_origin,
				$t->days_ship,
				$t->product_status,
				$t->product_registration_time,
				$t->last_modified,
				$t->hash,
			]);
		}
		$export_csv = new ExportMercariUpdate($mercari_update_product);
		return Excel::download($export_csv, $filename, \Maatwebsite\Excel\Excel::CSV, [
			'Content-Type' => 'text/csv',
		]);
	}

	public function mercari_update_delete(Request $request, $from, $to, $start, $end)
	{
		$mercari_delete = MercariUpdate::whereBetween('id', [$from, $to])->get();
		foreach ($mercari_delete as $md) {
			$md->product_status = 3;
			$md->save();
		}
		return redirect()->route('mercari_update');
	}

	public function mercari_update_setting(Request $request)
	{
		$req = $request->all();
		$update_region = MercariProduct::where('user_id', $req['user_id'])->get();
		foreach ($update_region as $re) {
			$re->region_origin = $req['region_origin'];
			$re->day_ship = $req['day_ship'];
			$re->product_status = $req['product_status'];
			$re->save();
		}
		return 'success';
	}
	public function mercari_exist_setting(Request $request)
	{
		$setting_value = MercariUpdate::where('user_id', Auth::id())->first();
		return $setting_value;
	}
	public function mercari_entry_update_setting(Request $request)
	{
		// dd($request->all());
		$req = $request->all();
		$update_region = MercariUpdate::where('user_id', $req['user_id'])->get();
		DB::beginTransaction();
		try {
			foreach ($update_region as $re) {
				$re->region_origin = $req['region_origin'];
				$re->days_ship = $req['day_ship'];
				$re->product_status = $req['product_status'];
				$re->re_entry = $req['re_entry'];
				$re->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
		return 'success';
	}

	public function export_xlsx_entry(Request $request)
	{
		$entry_data = Job::get();
		$exhibitionData = [];
		foreach ($entry_data as $t) {
			array_push($exhibitionData, [
				$t->corporation,
				$t->job_content,
				$t->service_type,
				$t->salary,
				$t->salary_remarks,
				$t->treatment,
				$t->hours,
				$t->vacation,
				$t->long_spec,
				$t->access,
				$t->url
			]);
		};
		$export = new ExportExhibition($exhibitionData);
		return Excel::download($export, 'nursery.xlsx');
		// $entry_data = Exhibition::get();
		// $exhibitionData = [];
		// foreach ($entry_data as $t) {
		// 	array_push($exhibitionData, [
		// 		$t->m_code,
		// 		$t->ASIN,
		// 		json_decode($t->product),
		// 		json_decode($t->feature),
		// 		$t->e_price,
		// 		$t->price,
		// 		$t->profit,
		// 		$t->postage,
		// 		$t->etc,
		// 		$t->a_category,
		// 		$t->m_category,
		// 		$t->m_category_id
		// 	]);
		// };
		// $export = new ExportExhibition($exhibitionData);
		// return Excel::download($export, '出品対象商品.xlsx');
	}
	public function export_xlsx_not_entry(Request $request)
	{
		$entry_data = Exhibition::where('user_id', Auth::id())->where('exclusion', '!=', '')->get();
		$unexhibitionData = [];
		foreach ($entry_data as $t) {
			array_push($unexhibitionData, [
				$t->ASIN,
				json_decode($t->product),
				json_decode($t->feature),
				$t->e_price,
				$t->price,
				($t->postage == 0) ? '出品不可' : $t->postage,
				$t->etc,
				$t->a_category,
				$t->m_category,
				$t->m_category_id,
				$t->exclusion
			]);
		};
		$export = new NotExhibition($unexhibitionData);
		return Excel::download($export, '出品不可商品.xlsx');
	}

	public function mercari_product_save()
	{
		MercariProduct::where('user_id', '=', Auth::id())->delete();
		$export_entry_data = Exhibition::where('user_id', Auth::user()->id)->where('exclusion', '')->get();
		DB::beginTransaction();
		try {
			foreach ($export_entry_data as $t) {
				$mercari_csv = MercariProduct::where('ASIN', $t->ASIN)->first();
				if (!isset($mercari_csv)) {
					$mercari_csv = new MercariProduct;
				}
				$image = explode(';', $t->image);
				for ($i = 1; $i < count($image) + 1; $i++) {
					if ($i > 10)
						break;
					$mercari_csv['image_' . $i] = $t->ASIN . '_' . $i . '.jpg';
				}
				$mercari_csv->image = $image[0];
				$mercari_csv->user_id = $t->user_id;
				$mercari_csv->SKU1_management = $t->m_code;
				$mercari_csv->SKU1_inventory = 1;
				$mercari_csv->product = $t->product;
				$mercari_csv->feature = $t->feature;
				$mercari_csv->ASIN = $t->ASIN;
				$mercari_csv->selling_price = $t->e_price;
				$mercari_csv->category_id = $t->m_category_id;
				$mercari_csv->commodity = 1;
				$mercari_csv->shipping_method = 1;
				$mercari_csv->region_origin = 'jp12';
				$mercari_csv->day_ship = 3;
				$mercari_csv->product_status = 1;
				$mercari_csv->save();
			};
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
		return 'success';
	}

	public function entry_list(Request $request)
	{
		if ($request->ajax()) {
			$data = Exhibition::select('id', 'image', 'ASIN', 'm_code', 'prime',  'product', 'e_price', 'price', 'postage', 'etc', 'm_category')->where('user_id', Auth::id())->where('exclusion', '')->get();
			return Datatables::of($data)->make(true);
		}
	}
	public function not_entry_list(Request $request)
	{
		if ($request->ajax()) {
			$data = Exhibition::select('id', 'prime', 'image', 'ASIN', 'product', 'e_price', 'price', 'postage', 'etc', 'm_category', 'exclusion')->where('user_id', Auth::id())->where('exclusion', '!=', '')->get();
			return Datatables::of($data)
				->addColumn('jsonstr', function ($row) {
					return json_decode($row['product']);
				})
				->rawColumns(['jsonstr'])
				->make(true);
		}
	}
	public function amazon_list(Request $request)
	{
		if ($request->ajax()) {
			$data = AmazonProduct::select('id', 'image', 'prime', 'tracking_condition', 'ASIN', 'product', 'price', 'r_price','product_error')->where('user_id', Auth::id())->where('flag', 1)->get();
			return Datatables::of($data)
				->addColumn('jsonstr', function ($row) {
					return json_decode($row['product']);
				})
				->rawColumns(['jsonstr'])
				->make(true);
		}
	}
	public function mercari_update_list_datatable(Request $request, $from, $to)
	{
		if ($request->ajax()) {
			$data = MercariUpdate::select('id', 'SKU1_product_management_code', 'product_name', 'Selling_price', 'region_origin', 'product_status', 'product_registration_time', 'last_modified')->whereBetween('id', [$from, $to])->get();
			return Datatables::of($data)
				->addColumn('jsonstr', function ($row) {
					return json_decode($row['product_name']);
				})
				->rawColumns(['jsonstr'])
				->make(true);
		}
	}
	public function mercari_update_view(Request $request)
	{
		if ($request->ajax()) {
			$data = MercariUpdate::select('id', 'SKU1_product_management_code', 'product_name', 'Selling_price', 'region_origin', 'product_status', 'product_registration_time', 'last_modified')->where('user_id', Auth::id())->get();
			return Datatables::of($data)
				->addColumn('jsonstr', function ($row) {
					return json_decode($row['product_name']);
				})
				->rawColumns(['jsonstr'])
				->make(true);
		}
	}

	public function delete_entry_data(Request $request, $id)
	{
		// $entrydata = Exhibition::find($id)->first();
		// $mercariUpdateDelete = MercariUpdate::where('user_id', Auth::id())->where('SKU1_product_management_code', $entrydata->m_code)->first();
		// if ($mercariUpdateDelete != null) {
		// 	$mercariUpdateDelete->product_status = 3;
		// 	$mercariUpdateDelete->save();
		// }
		// MercariProduct::where('user_id', Auth::id())->where('SKU1_management', $entrydata->m_code)->delete();
		Exhibition::where("id", $id)->delete();
		return redirect()->route('entry_data');
	}
	public function delete_mercari_update_data(Request $request)
	{
		$deleteUpdate = MercariUpdate::where('id', $request['id'])->first();
		$deleteUpdate->product_status = 3;
		$deleteUpdate->save();
		return $deleteUpdate;
	}
	public function mercari_exist_delete(Request $request)
	{
		$mercariU = MercariUpdate::where('user_id', Auth::id())->get();
		foreach ($mercariU as $mU) {
			MercariProduct::where('SKU1_management', $mU->SKU1_product_management_code)->delete();
		}
		return 'success';
	}
}
