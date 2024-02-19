<x-app title="Resultados de Búsqueda">
    <div class="container">
        <form id="search-form" action="{{ route('search') }}" method="GET" class="d-flex">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar productos...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        @if (count($products) > 0)
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title">{{ $product->name }}</h5>
                            </div>
                            <div class="card-body">
                                <img src="{{ $product->file->route }}" alt="{{ $product->name }}"
                                    class="card-img-top img-fluid">
                                <p><strong>Precio:</strong> ${{ $product->price }}</p>
                                <p><strong>Descripción:</strong> {{ $product->description }}</p>
                                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                                <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                    class="btn btn-primary">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" role="alert">
                No se encontraron productos que coincidan con la búsqueda.
            </div>
        @endif
    </div>

    <script>
        document.getElementById('search-form').addEventListener('submit', function(event) {
            var queryInput = document.querySelector('input[name="query"]');
            if (queryInput.value.trim() === '') {
                event.preventDefault();
            }
        });
    </script>
</x-app>
