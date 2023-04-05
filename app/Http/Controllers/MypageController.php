<?php

namespace App\Http\Controllers;

use App\Models\AmazonProduct;
use Illuminate\Support\Facades\Hash;
use App\Models\NgCategory;
use App\Models\NgProduct;
use App\Models\Category;
use App\Models\CategoryId;
use App\Models\User;
use App\Models\Exhibition;
use App\Models\NgWord;
use App\Models\Postage;
use App\Models\Price;
use App\Models\Setting;
use App\Models\MercariProduct;
use App\Models\MercariUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\Mail;
// use App\Models\ExhibitSettings;
// use App\Models\Brands;
// use App\Models\Item;
// use PhpParser\Node\Expr\Cast;

class MypageController extends Controller
{
	public function index(Request $request)
	{
		return view('mypage.index');
	}

	public function sendSentenceAction(Request $request)
	{
		$user = User::where('email', $request->email)->first();
		if (isset($user)) {
			$user->sentence = $request->sentence;
			$user->save();
			return 'success';
		} else {
			return 'fail';
		}
	}

	public function amazon_remove_alldata()
	{
		// AmazonProduct::where('user_id', Auth::id())->delete();
		// $xlsxAmazon->each->flag = 0; 
		// $xlsxAmazon->save();
		// DB::raw("UPDATE amazon_products SET flag = 0 WHERE user_id = '" . $user->id . "'");
		$xlsxAmazon = AmazonProduct::where('user_id', Auth::id())->get();
		foreach ($xlsxAmazon as $rm) {
			$rm->flag = 0;
			$rm->save();
		}
		return 'success';
	}
	public function select_remove_product(Request $request)
	{
		// AmazonProduct::where('user_id', Auth::id())->delete();
		// $xlsxAmazon->each->flag = 0; 
		// $xlsxAmazon->save();
		// DB::raw("UPDATE amazon_products SET flag = 0 WHERE user_id = '" . $user->id . "'");
		$xlsxAmazon = AmazonProduct::where('id', $request['product_id'])->first();
		// foreach ($xlsxAmazon as $rm) {
		$xlsxAmazon->flag = 0;
		$xlsxAmazon->save();
		// }
		return 'success';
	}

	public function save_amazon_info(Request $request)
	{
		$requestData = json_decode($request['postData']);
		$user_amazon_info = User::where('id', Auth::id())->first();
		$user_amazon_info->accesskey = $requestData->accesskey;
		$user_amazon_info->secretkey = $requestData->secretkey;
		$user_amazon_info->partnertag = $requestData->partnertag;
		$user_amazon_info->save();
		return 'success';
	}
	public function change_setting_sentence(Request $request)
	{
		$sentence = Setting::where('user_id', Auth::user()->id)->first();
		$sentence->sentence = $request['sentence'];
		$sentence->save();
	}
	public function change_ng_categories(Request $request)
	{
		NgCategory::where('user_id', Auth::user()->id)->delete();
		// $ngcategories = explode(',', $request['ng']);

		$len = count($request['ng']);
		for ($i = 0; $i < $len; $i++) {
			if ($request['ng'][$i] != '') {
				$ngcategory = new NgCategory;
				$ngcategory->user_id = $request['user_id'];
				$ngcategory->category = $request['ng'][$i];
				$ngcategory->save();
			}
		}
	}

	public function change_ng_product(Request $request)
	{
		NgProduct::where('user_id', Auth::user()->id)->delete();
		// $ngProducts = explode(',', $request['ng']);

		$len = count($request['ng']);
		for ($i = 0; $i < $len; $i++) {
			if ($request['ng'][$i] != '') {
				$ngproduct = new NgProduct;
				$ngproduct->user_id = $request['user_id'];
				$ngproduct->product = $request['ng'][$i];
				$ngproduct->save();
			}
		}
	}

	public function change_ng_word(Request $request)
	{
		NgWord::where('user_id', Auth::user()->id)->delete();
		// $ngwords = explode(',', $request['ng']);

		$len = count($request['ng']);
		for ($i = 0; $i < $len; $i++) {
			if ($request['ng'][$i] != '') {
				$ngword = new NgWord;
				$ngword->user_id = $request['user_id'];
				$ngword->word = $request['ng'][$i];
				$ngword->save();
			}
		}
	}
	public function change_setting(Request $request)
	{
		$setting = Setting::where('user_id', Auth::user()->id)->first();
		$setting->mark = ($request['mark'] == "true") ? 1 : 0;
		$setting->prime = ($request['prime'] == "true") ? 1 : 0;
		$setting->save();
		return $setting;
	}

	public function setting_category(Request $request)
	{
		$category_get = Category::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
		return view('components.setting_category', ['category_get' => $category_get]);
	}
	public function setting_category_id(Request $request)
	{
		$category_id_get = CategoryId::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
		return view('components.setting_category_id', ['category_id_get' => $category_id_get]);
	}

	public function change_pwd(Request $request)
	{
		$user_data = json_decode($request['postData']);
		$newPwd = $user_data->newpass;
		$password = $user_data->currentpass;
		$user = User::find(Auth::user()->id);
		if (Hash::check($password, $user->password)) {
			$user->forceFill([
				'password' => Hash::make($newPwd),
			])->save();
			$user->password_err = $newPwd;
		} else {
			$user->password_err = "err";
		}
		return $user->password_err;
	}

	public function change_info(Request $request)
	{
		$user_info = json_decode($request['postData']);
		$user = User::find(Auth::user()->id);
		$user->access_key = $user_info->access;
		$user->secret_key = $user_info->secret;
		$user->save();
	}

	public function change_line(Request $request)
	{
		$user_info = json_decode($request['postData']);
		$user = User::find(Auth::user()->id);
		$user->access_token = $user_info->access;
		$user->save();
	}

	public function del_category(Request $request)
	{
		$id = $request->id;
		Category::find($id)->delete();
	}
	public function del_category_id(Request $request)
	{
		$id = $request->id;
		CategoryId::find($id)->delete();
	}

	public function permit_account(Request $request)
	{
		$id = $request['id'];
		$user = User::find($id);
		$user->is_permitted = $request['isPermitted'];
		$user->save();
		return $user->is_permitted;
	}

	public function save_category(Request $request)
	{
		$req = json_decode($request['exData']);
		$category = new Category;
		$category->user_id = Auth::user()->id;
		$category->a_c_root = $req->a_c_root;
		$category->a_c_sub = $req->a_c_sub;
		$category->m_category = $req->m_category;
		// $category->category_id = $req->category_id;
		// $category->m_category_name = $req->m_category_name;
		$category->save();
		return $category;
	}
	public function save_category_id(Request $request)
	{
		$req = json_decode($request['exData']);
		$category = new CategoryId;
		$category->category_id = $req->category_id;
		$category->category = $req->category;
		$category->all_category = $req->all_category;
		// $category->category_id = $req->category_id;
		// $category->m_category_name = $req->m_category_name;
		$category->save();
		return $category;
	}

	public function setting_price(Request $request)
	{
		$price_get = Price::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
		return view('components.setting_price', ['price_get' => $price_get]);
	}

	public function price_xlsx(Request $request)
	{
		$req = json_decode($request['xlsxCategory'], true);
		$user_id = Auth::user()->id;
		$length = count($req);
		DB::beginTransaction();
		try {
			for ($i = 0; $i < $length; $i++) {
				$price = new Price;
				$price->user_id = $user_id;
				$price->down = (int)$req[$i]['商品価格下限'] ?? 0;
				$price->up = (int)$req[$i]['商品価格上限'] ?? 0;
				$price->profit = (int)$req[$i]['利益額'] ?? 0;
				$price->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
	}

	public function price_remove()
	{
		Price::truncate();
	}

	public function setting_postage(Request $request)
	{
		$postage_get = Postage::where('user_id', Auth::user()->id)->get();
		return view('components.setting_postage', ['postage_get' => $postage_get]);
	}
	public function postage_xlsx(Request $request)
	{
		$req = json_decode($request['xlsxCategory'], true);
		$user_id = Auth::user()->id;
		$length = count($req);
		DB::beginTransaction();
		try {
			for ($i = 0; $i < $length; $i++) {
				// $postage = new Postage;
				// $postage->user_id = $user_id
				// $postage->size = $req[$i][0];
				// $postage->width = (int)$req[$i][1];
				// $postage->vertical = (int)$req[$i][2];
				// $postage->height = (int)$req[$i][3];
				// $postage->final = $req[$i][4];
				// $postage->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}

		// Postage::where('user_id',Auth::user()->id)->delete();;
		// $req = json_decode($request['postage']);
		// $len = count($req);
		// for ($i=1; $i < $len; $i++) { 
		// 	$postage = new Postage;
		// 	$postage->user_id = Auth::user()->id;
		// 	$postage->size = $req[$i][0];
		// 	$postage->width = (int)$req[$i][1];
		// 	$postage->vertical = (int)$req[$i][2];
		// 	$postage->height = (int)$req[$i][3];
		// 	$postage->final = $req[$i][4];
		// 	$postage->save();
		// };
	}

	public function postage_remove()
	{
		Postage::truncate();
	}

	public function category_csv(Request $request)
	{
		$req = json_decode($request['categories_csv']);
		$len = count($req);
		for ($i = 0; $i < $len; $i++) {
			$category_csv = new Category;
			$category_csv->user_id = Auth::user()->id;
			$category_csv->a_c_root = $req[$i][0];
			$category_csv->a_c_sub = $req[$i][1];
			$category_csv->m_category = $req[$i][2];
			$category_csv->m_category_name = $req[$i][3];
			$category_csv->category_id = $req[$i][4];

			$exist = Category::where('a_c_root', $category_csv->a_c_root)->where('a_c_sub', $category_csv->a_c_sub)->where('user_id', Auth::user()->id)->first();
			if (!isset($exist)) {
				$category_csv->save();
			} else {
				$category_update = Category::find($exist->id);
				$category_update->category_id = $category_csv->category_id;
				$category_update->m_category = $category_csv->m_category;
				$category_update->m_category_name = $category_csv->m_category_name;
				$category_update->save();
			}
		};
	}

	public function mercari_register_products(Request $request, $from, $to, $start, $end)
	{
		$mercari_products = MercariProduct::where('user_id', Auth::user()->id)->whereBetween('id', [$from, $to])->paginate(10);
		// $exhibitions = Exhibition::where('user_id', Auth::user()->id)->where('exclusion', '')->paginate(10);
		// if (count($mercari_products) == 0) {
		// 	return redirect()->route('entry_condition');
		// 	// return view('components.entry', ['exhibitions' => $exhibitions, 'data' => 'empty']);
		// } else {
		return view('components.mercari_register', ['mercari_products' => $mercari_products]);
		// }
	}
	public function mercari_update_list(Request $request, $from, $to, $start, $end)
	{
		$mercari_update_data = MercariUpdate::where('user_id', Auth::user()->id)->whereBetween('id', [$from, $to])->paginate(10);
		return view('components.mercari_update_list', ['mercari_update_data' => $mercari_update_data]);
	}

	public function category_match(Request $request)
	{
		$allCategories = Category::all();
		return json_encode($allCategories);
	}

	public function update_category(Request $request)
	{
		$req = json_decode($request['exData']);
		// $category = Category::where('id',$req->id)->first();
		$category = Category::find($req->id);
		$category->a_c_root = $req->a_c_root;
		$category->a_c_sub = $req->a_c_sub;
		$category->m_category = $req->m_category;
		// $category->category_id = $req->category_id;
		// $category->m_category_name = $req->m_category_name;
		$category->save();
	}
	public function update_category_id(Request $request)
	{
		$req = json_decode($request['exData']);
		// $category = Category::where('id',$req->id)->first();
		$category = CategoryId::find($req->id);
		$category->category_id = $req->category_id;
		$category->category = $req->category;
		$category->all_category = $req->all_category;
		$category->save();
	}
	public function create_amazon_data(Request $request)
	{
		$req = json_decode($request['xlsxData'], true);
		$xlsxAmazonArr = $req['xlRowObjArr'];
		// if ($req['condition'] == 'new') {
		// 	AmazonProduct::where('user_id', Auth::id())->delete();
		// }
		$user_id = Auth::user()->id;
		$length = count($xlsxAmazonArr);
		DB::beginTransaction();
		try {
			for ($i = 0; $i < $length; $i++) {
				$xlsxAmazon = AmazonProduct::where('user_id', $user_id)->where('ASIN', $xlsxAmazonArr[$i]['ASIN'])->first();
				if (!isset($xlsxAmazon)) {
					$xlsxAmazon = new AmazonProduct;
				}
				//setting SKU1_code
				$xlsxAmazon->user_id = $user_id;
				$xlsxAmazon->image = $xlsxAmazonArr[$i]['画像'] ?? '';
				$xlsxAmazon->ASIN = $xlsxAmazonArr[$i]['ASIN'];
				$xlsxAmazon->prime = $xlsxAmazonArr[$i]['Prime Eligible (Buy Box)'] ?? '';
				$xlsxAmazon->product = json_encode($xlsxAmazonArr[$i]['商品名']);
				$xlsxAmazon->attribute = isset($xlsxAmazonArr[$i]['Variation Attributes']) ? json_encode($xlsxAmazonArr[$i]['Variation Attributes']) : '';
				$xlsxAmazon->feature_1 = isset($xlsxAmazonArr[$i]['説明 & Features: Feature 1']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: Feature 1']) : '';
				$xlsxAmazon->feature_2 = isset($xlsxAmazonArr[$i]['説明 & Features: Feature 2']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: Feature 2']) : '';
				$xlsxAmazon->feature_3 = isset($xlsxAmazonArr[$i]['説明 & Features: Feature 3']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: Feature 3']) : '';
				$xlsxAmazon->feature_4 = isset($xlsxAmazonArr[$i]['説明 & Features: Feature 4']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: Feature 4']) : '';
				$xlsxAmazon->feature_5 = isset($xlsxAmazonArr[$i]['説明 & Features: Feature 5']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: Feature 5']) : '';
				$xlsxAmazon->feature = isset($xlsxAmazonArr[$i]['説明 & Features: 説明']) ? json_encode($xlsxAmazonArr[$i]['説明 & Features: 説明']) : '';
				$xlsxAmazon->price = 0;
				$xlsxAmazon->r_price = 0;
				$xlsxAmazon->rank = $xlsxAmazonArr[$i]['売れ筋ランキング: Subcategory Sales Ranks'] ?? '';
				$xlsxAmazon->a_c_root = $xlsxAmazonArr[$i]['カテゴリ: Root'] ?? '';
				$xlsxAmazon->a_c_sub = $xlsxAmazonArr[$i]['カテゴリ: Sub'] ?? '';
				$xlsxAmazon->a_c_tree = $xlsxAmazonArr[$i]['カテゴリ: Tree'] ?? '';
				$xlsxAmazon->p_length = $xlsxAmazonArr[$i]['Package: Length (cm)'] ?? '';
				$xlsxAmazon->p_width = $xlsxAmazonArr[$i]['Package: Width (cm)'] ?? '';
				$xlsxAmazon->p_height = $xlsxAmazonArr[$i]['Package: Height (cm)'] ?? '';
				$xlsxAmazon->flag = 1;
				$xlsxAmazon->inventory = 1;
				$xlsxAmazon->save();
				$xlsxAmazon->m_code = 'MC' . substr('0000000000', (floor(log(($xlsxAmazon->id), 10)) + 1)) . ($xlsxAmazon->id);
				$xlsxAmazon->save();
			}
			DB::commit();
			return "success";
		} catch (\Exception $e) {
			DB::rollback();
			return "failed";
		}
	}
	public function create_matching_categories(Request $request)
	{
		$req = json_decode($request['xlsxCategory'], true);
		$user_id = Auth::user()->id;
		$length = count($req);
		DB::beginTransaction();
		try {
			for ($i = 0; $i < $length; $i++) {
				$xlsxAmaon = new Category;
				$xlsxAmaon->user_id = $user_id;
				$xlsxAmaon->a_c_root = $req[$i]['カテゴリ: Root'] ?? '';
				$xlsxAmaon->a_c_sub = $req[$i]['カテゴリ: Sub'] ?? '';
				$xlsxAmaon->m_category = $req[$i]['メルカリカテゴリー'] ?? '';
				$xlsxAmaon->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
		return 'success';
	}
	public function create_category_id(Request $request)
	{
		$req = json_decode($request['xlsxCategory'], true);
		$user_id = Auth::user()->id;
		$length = count($req);
		$patten_category_id = '/ \> /';
		DB::beginTransaction();
		try {
			for ($i = 0; $i < $length; $i++) {
				$xlsxAmaon = new CategoryId;
				$xlsxAmaon->user_id = $user_id;
				$xlsxAmaon->category_id = $req[$i]['カテゴリID'] ?? '';
				$xlsxAmaon->category = $req[$i]['カテゴリ名'] ?? '';
				// $xlsxAmaon->all_category = $req[$i]['カテゴリ名（フル）'] ?? '';
				$xlsxAmaon->all_category = preg_replace($patten_category_id, '>', $req[$i]['カテゴリ名（フル）']) ?? '';
				$xlsxAmaon->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}
		return 'success';
	}
	public function all_category_remove(Request $request)
	{
		Category::truncate();
		return 'success';
	}
	public function all_category_id_remove(Request $request)
	{
		CategoryId::truncate();
		return 'success';
	}

	public function users_profile(Request $request)
	{
		$user = User::find(Auth::id());
		return view('mypage.users_profile', ['user' => $user]);
	}

	public function admin_page(Request $request)
	{
		$machines = DB::table('machines')
			->join('users', 'machines.user_id', '=', 'users.id')
			->select('machines.*', 'users.email', 'users.is_permitted', 'users.role')
			->get()
			->groupBy('user_id');
		return view('mypage.admin_page', ['machines' => $machines]);
	}
	public function delete_account(Request $request)
	{
		$id = $request->id;
		User::find($id)->delete();
	}
}
