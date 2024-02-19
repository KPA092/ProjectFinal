<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class CartController extends Controller
{
	//Show
	public function index()
	{
		$user = Auth::user();

		$cartItems = $user->cartItems()->get();

		return view('cart.index', compact('cartItems'));
	}


	//Update
	public function update(Request $request, $id)
	{
		$request->validate([
			'quantity' => 'required|integer|min:1'
		]);

		$cartItem = CartItem::findOrFail($id);

		$cartItem->update([
			'quantity' => $request->quantity,
			'price_total' => $cartItem->product->price * $request->quantity,
		]);

		return redirect()->route('cart.index')->with('success', 'Cantidad actualizada en el carrito.');
	}

	//Delete
	public function destroy($id)
	{
		CartItem::findOrFail($id)->delete();

		return Redirect::route('cart.index')->with('success', 'Producto eliminado del carrito.');
	}

	//Clear
	public function clear()
	{
		auth()->user()->cartItems()->delete();

		return redirect()->route('cart.index')->with('success', 'Carrito limpiado exitosamente.');
	}
}
