<x-app title="Productos">
    <section class="container">
        <div class="d-flex justify-content-center my-4">
            <h1>Listado de produtos</h1>
        </div>

        <the-product-list :products="{{ $products }}" />
    </section>
</x-app>
