<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;

use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
	use UploadFile;

	public function home()
	{
		$products = Product::with('category', 'file')
			->whereHas('category')
			->where('stock', '>', 0)
			->get();

		return view('index', compact('products'));
	}

	// add to cart
	public function addToCart(Product $product)
	{
		CartItem::create([
			'user_id' => auth()->id(), // Obtener el ID del usuario autenticado
			'product_id' => $product->id,
			'quantity' => 1, // Puedes definir una cantidad por defecto o dejar que el usuario especifique
			'price_unit' => $product->price, // Precio unitario del producto
			'price_total' => $product->price // Precio total inicialmente igual al precio unitario
		]);

		return Redirect::route('cart.index')->with('success', 'Producto agregado al carrito.');
	}

	public function index()
	{
		$products = Product::with('category', 'file')->whereHas('category')->get();
		return view('products.index', compact('products'));
	}

	public function store(ProductRequest $request)
	{
		try {
			DB::beginTransaction();
			$product = new Product($request->all());
			$product->save();
			$this->uploadFile($product, $request);
			DB::commit();
			return response()->json([], 200);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function show(Product $product)
	{
		return view('categories.show', compact('category'));
	}

	public function showAll($id)
	{
		// Cargar el producto por su ID
		$product = Product::findOrFail($id);

		// Devolver la vista con los datos del producto
		return view('product', compact('product'));
	}

	public function update(ProductUpdateRequest $request, Product $product)
	{
		try {
			DB::beginTransaction();
			$product->update($request->all());
			$this->uploadFile($product, $request);
			DB::commit();
			return response()->json([], 204);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}

	public function destroy(Product $product)
	{
		$product->delete();
		$this->deleteFile($product);
		return response()->json([], 204);
	}
}
