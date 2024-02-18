<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
	public function search(Request $request)
	{
		$query = $request->input('query');

		$products = Product::where('name', 'LIKE', "%$query%")->get();
		$categories = Category::where('name', 'LIKE', "%$query%")->get();

		return view('search', compact('products', 'categories', 'query'));
	}
}
