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
		// Obtener el usuario autenticado
		$user = Auth::user();

		// Obtener los elementos del carrito del usuario
		$cartItems = $user->cartItems()->get();

		// Retornar la vista del carrito con los elementos
		return view('cart.index', compact('cartItems'));
	}


	//Update
	public function update(Request $request, $id)
	{
		$request->validate([
			'quantity' => 'required|integer|min:1'
		]);

		// Buscar el elemento del carrito por ID
		$cartItem = CartItem::findOrFail($id);

		// Actualizar la cantidad del elemento del carrito
		$cartItem->update([
			'quantity' => $request->quantity,
			// Actualizar el precio total según la nueva cantidad
			'price_total' => $cartItem->product->price * $request->quantity,
		]);

		// Redirigir de vuelta al carrito con un mensaje de éxito
		return redirect()->route('cart.index')->with('success', 'Cantidad actualizada en el carrito.');
	}

	//Delete
	public function destroy($id)
	{
		// Buscar y eliminar el elemento del carrito por ID
		CartItem::findOrFail($id)->delete();

		// Redirigir de vuelta al carrito con un mensaje de éxito
		return Redirect::route('cart.index')->with('success', 'Producto eliminado del carrito.');
	}

	//Clear
	public function clear()
	{
		// Eliminar todos los elementos del carrito del usuario autenticado
		auth()->user()->cartItems()->delete();

		// Redirigir de vuelta al carrito con un mensaje de éxito
		return redirect()->route('cart.index')->with('success', 'Carrito limpiado exitosamente.');
	}
}
