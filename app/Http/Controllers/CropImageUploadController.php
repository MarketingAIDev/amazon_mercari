<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CropImageUploadController extends Controller
{
	public function store(Request $request)
	{
		$folder_path = public_path('primeIMG/');
		// $folder_path = public_path(Auth::id() . '/1/');
		$folder_path1 = '/primeIMG/';
		$image_name = "";

		$request = json_decode($request->all()["image"], true);

		if ($request != "") {
			$image_parts = explode(";base64,", $request['image']);
			$image_type_aux = explode("image/", $image_parts[0]);

			if (count($image_type_aux) > 1) {
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$image_name = uniqid() . '.' . $image_type; // wer9782343owerowl.jpg
				$image_fullpath = $folder_path . $image_name; // https://amazon_mercari/public/1/images/1/wer978343ewreoi.jpg
				file_put_contents($image_fullpath, $image_base64);
			}
		}

		$newImageurl = $folder_path1 . $image_name;
		return $newImageurl;
	}
}
