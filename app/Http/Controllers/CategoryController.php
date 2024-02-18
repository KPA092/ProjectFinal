<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Category\CategoryRequest;

class CategoryController extends Controller
{
	public function home()
	{
		$categories = Category::with('products')->get();
		return view('index', compact('categories'));
	}

	public function index(Request $request)
	{
		$categories = Category::get();
		if (!$request->ajax()) return view('categories.index');
		return response()->json(['categories' => $categories], 200);
	}

	public function store(Request $request)
	{
		$category = new Category($request->all());
		$category->save();
		return response()->json([], 200);
	}

	public function getAll()
	{
		$categories = Category::query();
		return DataTables::of($categories)->toJson();
	}


	public function show(Category $category)
	{
		return response()->json(['category' => $category], 200);
	}

	public function showAll($id)
	{
		// Cargar la categoría por su ID
		$category = Category::findOrFail($id);

		// Cargar los productos asociados a la categoría
		$products = $category->products;

		// Devolver la vista con los datos de la categoría y los productos
		return view('category', compact('category', 'products'));
	}

	public function update(CategoryRequest $request, Category $category)
	{
		$category->update($request->all());
		return response()->json([], 204);
	}

	public function destroy(Category $category)
	{
		$category->delete();
		return response()->json([], 204);
	}
}
