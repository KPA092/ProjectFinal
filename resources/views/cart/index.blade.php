<x-app title="Carrito de Compras">
    <div class="container">
        <h1>Carrito de Compras</h1>

        @if (count($cartItems) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Precio Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $cartItem)
                        <tr>
                            <td>
                                @if ($cartItem->product->file->route)
                                    <img src="{{ $cartItem->product->file->route }}" alt="{{ $cartItem->product->name }}"
                                        style="width: 100px; height: auto;">
                                @else
                                    No hay imagen disponible
                                @endif
                            </td>
                            <td>{{ $cartItem->product->name }}</td>
                            <td>
                                <form action="{{ route('cart.update', ['id' => $cartItem->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}"
                                        min="1">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </form>
                            </td>
                            <td>${{ $cartItem->price_unit }}</td>
                            <td>${{ $cartItem->price_total }}</td>
                            <td>
                                <form action="{{ route('cart.destroy', ['id' => $cartItem->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--Clear button-->
            @if (count($cartItems) > 0)
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Limpiar Carrito</button>
                </form>
            @endif

            <a href="{{ route('home') }}" class="btn btn-primary">¿Desea seguir comprando?</a>
        @else
            <p>No hay productos en el carrito.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">¿Deseas comprar un producto?</a>
        @endif
    </div>
</x-app>
