{{-- produits/_grid.blade.php --}}

<div class="produits-grid">

@forelse($produits as $p)
    @include('produits._card', ['p' => $p])
@empty
    <p>Aucun produit trouvé.</p>
@endforelse

</div>