<x-app title="Inicio">
    <div class="container">
        <form id="search-form" action="{{ route('search') }}" method="GET" class="d-flex">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar productos...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        @foreach ($categories as $category)
            <div class="card my-5">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="h4">{{ $category->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
                        @php
                            $products = $category->products->where('stock', '>', 0)->take(5);
                        @endphp

                        @foreach ($products as $product)
                            <div class="col">
                                <div class="card h-100 text-center">
                                    <img src="{{ $product->file->route }}" class="card-img-top img-fluid"
                                        alt="{{ $product->name }}" style="max-height: 200px; object-fit: contain;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">Precio: ${{ $product->price }}</p>
                                        <p class="card-text">Descripción: {{ $product->description }}</p>
                                        <p class="card-text">Stock: {{ $product->stock }}</p>
                                    </div>
                                    <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                        class="btn btn-primary">Ver Detalles del Producto</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('category.show', ['category' => $category->id]) }}" class="btn btn-secondary">Ver
                    todos los productos</a>
            </div>
        @endforeach
    </div>
</x-app>
