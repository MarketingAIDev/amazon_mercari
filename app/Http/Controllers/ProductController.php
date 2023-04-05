<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AmazonProduct;
use App\Models\MercariProduct;
use App\Models\MercariUpdate;

class ProductController extends Controller
{
	public function register_products(Request $request)
	{
		$user = User::find(Auth::user()->id);
		AmazonProduct::where('user_id', $user->id)->delete();

		$req = json_decode($request['asin']);
		$codes = $req->codes;
		foreach ($codes as $c) {
			$product = new AmazonProduct;
			$product->user_id = $user->id;
			$product->asin = $c->asin;
			// $product->image = $user->image;
			$product->reg_price = $c->price;
			$product->pro = $c->pro;
			$product->price = $c->price;
			$product->tar_price = floor($c->price * $c->pro / 100);
			$product->url = 'https://www.amazon.co.jp/dp/' . $c->asin . '?tag=gnem03010a-22&linkCode=ogi&th=1&psc=1';
			$product->save();
		}
	}

	public function list_product()
	{
		$user = Auth::user();
		$products = AmazonProduct::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
		return view('mypage.product_list', ['user' => $user, 'products' => $products]);
	}
	//Checking Del
	public function delete_product()
	{
		$user = Auth::user();
		// $products = AmazonProduct::where('user_id', $user->id);
		// $products->delete();
		$xlsxAmazon = AmazonProduct::where('user_id', $user->id)->get();
		$xlsxAmazon->flag = 0;
		$xlsxAmazon->save();
		return;
	}

	public function remove_product(Request $request)
	{
		//$products = AmazonProduct::where('id', $request->product_id)->delete();
		$xlsxAmazon = AmazonProduct::where('id', $request->product_id)->first();
		$xlsxAmazon->flag = 0;
		$xlsxAmazon->save();
		return true;
	}

	public function csv_down(Request $request)
	{
		$data = "";
		$filename = "";
		$user = Auth::user();

		$data .= "ASIN\n";
		$products = AmazonProduct::where('user_id', $user->id)->get();
		foreach ($products as $p) {
			$data .= $p['asin'] . "\n";
		}

		$filename = "ASINリスト";

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $filename . "_" . date("Y-m-d") . '.csv"');
		echo $data;
		exit();
	}

	public function edit_track(Request $request)
	{
		$product = AmazonProduct::find($request->id);
		$product->tar_price = $request->price;
		$product->save();
	}

	public function etc_function()
	{
		// $mercari_updates_limit = MercariUpdate::select('id', 'SKU1_product_management_code', 'last_modified', 'product_name', 'Selling_price', 'product_status')->where('user_id', '=', Auth::id())->where('SKU1_current_inventory', 0)->where('product_status', '!=', 3)->orderBy('id', 'asc')->get();
		// dd($mercari_updates_limit);
		// return view('components.etc_function', ['mercari_updates_limit' => $mercari_updates_limit]);

		// ==========================  change  4-4  =====================================================
		$mercari_updates_limit = [];
		$mercari_update = MercariUpdate::select('id', 'SKU1_product_management_code', 'last_modified', 'product_name', 'Selling_price', 'product_status')->where('user_id', '=', Auth::id())->where('product_status', '!=', 3)->orderBy('id', 'asc')->get();
		foreach ($mercari_update as $mu) {
			$amazon_info = AmazonProduct::where('user_id', Auth::id())->where('m_code', '=', $mu['SKU1_product_management_code'])->first();
			if (isset($amazon_info)) {
				if ($amazon_info['inventory'] == 0) {
					array_push($mercari_updates_limit, [$mu['id'], explode(';', $amazon_info['image'])[0], $mu['SKU1_product_management_code'], $mu['product_name'], $mu['Selling_price'], $mu['product_status'], $mu['last_modified']]);
				}
			}
		}
		return view('components.etc_function', ['mercari_updates_limit' => $mercari_updates_limit]);
	}
}
