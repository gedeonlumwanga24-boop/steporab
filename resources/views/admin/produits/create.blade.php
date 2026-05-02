@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-3xl shadow-lg">
    <h1 class="text-2xl font-semibold mb-4">Ajouter un produit</h1>

    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('produits.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Nom du produit"
                       class="mt-1 w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Prix (CDF)</label>
                <input type="number" name="prix" value="{{ old('prix') }}" placeholder="Prix"
                       class="mt-1 w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select name="category_id"
                        class="mt-1 w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" placeholder="Quantité disponible"
                       class="mt-1 w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="5" placeholder="Description du produit"
                      class="mt-1 w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Image principale</label>
            <input type="file" name="image" class="mt-2 block w-full text-sm text-gray-700" accept="image/*">
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
            Ajouter le produit
        </button>
    </form>
</div>
@endsection