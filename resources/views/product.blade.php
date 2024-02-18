<x-app title="Detalles del Producto">
    <div class="container">
        <div class="card my-5" style="max-width: 500px; margin: 0 auto; border: 1px solid #ccc; border-radius: 8px;">
            <div class="card-body text-center">
                <img src="{{ $product->file->route }}" class="img-fluid mb-3" alt="{{ $product->name }}"
                    style="border-radius: 8px;">
                <h2 class="h4">{{ $product->name }}</h2>
                <p class="mb-2">Precio: ${{ $product->price }}</p>
                <p class="mb-2">Descripción: {{ $product->description }}</p>
                <p class="mb-2">Stock: {{ $product->stock }}</p>
            </div>
        </div>
    </div>
</x-app>
