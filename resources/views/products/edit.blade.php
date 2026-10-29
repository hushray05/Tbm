@extends('layouts.app')

@section('title', 'Edit ' . $product->name . ' • TBM Catalog')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div>
        <a href="{{ route('products.show', $product) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Item Details</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Edit Item: {{ $product->name }}</h1>
        <p class="text-xs text-secondary mt-0.5">Update item pricing, description, categorization, and product image.</p>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Info Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">sell</span>
                <span>Basic Item Information</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Product / Item Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">SKU / Item Code *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Category *</label>
                    <input list="categoryList" name="category" value="{{ old('category', $product->category) }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                    <datalist id="categoryList">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Current Stock on Hand</label>
                    <div class="px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich flex items-center justify-between">
                        <span>{{ number_format($product->stock_quantity) }} units</span>
                        <a href="{{ route('inventory.index', ['search' => $product->sku]) }}" class="text-primary hover:underline font-normal text-[11px]">Adjust via Stock Control →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Tax Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">payments</span>
                <span>Pricing & Uganda 18% VAT</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Selling Price (UGX) *</label>
                    <input type="number" name="price_cents" value="{{ old('price_cents', $product->price_cents) }}" min="0" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Cost / Buying Price (UGX)</label>
                    <input type="number" name="cost_price_cents" value="{{ old('cost_price_cents', $product->cost_price_cents) }}" min="0" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">VAT Rate (%)</label>
                    <input type="number" name="vat_rate" step="0.01" value="{{ old('vat_rate', $product->vat_rate) }}" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Product Image & Description Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">image</span>
                <span>Product Picture & Details</span>
            </h2>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Current Picture</label>
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-bg-subtle border border-border-quiet shrink-0 flex items-center justify-center">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-3xl text-secondary">inventory_2</span>
                        @endif
                    </div>
                    <div class="text-xs text-secondary">
                        <span class="font-semibold text-text-rich block">Replace item photo</span>
                        <span>Upload a new image below to update this photo.</span>
                    </div>
                </div>

                <div class="border-2 border-dashed border-border-quiet rounded-xl p-4 text-center hover:border-primary transition-colors cursor-pointer" onclick="document.getElementById('imageEditInput').click()">
                    <input type="file" name="image" id="imageEditInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewEditImage(this)">
                    <div id="imageEditPreviewContainer" class="hidden mb-2">
                        <img id="imageEditPreview" src="" alt="Preview" class="w-20 h-20 object-cover rounded-xl mx-auto border border-border-quiet">
                    </div>
                    <span class="text-xs font-bold text-text-rich block">Click to select new image</span>
                    <span class="text-[10px] text-secondary mt-0.5 block">PNG, JPG, WEBP up to 5MB</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Item Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('products.show', $product) }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all">
                Update Product
            </button>
        </div>
    </form>
</div>

<script>
    function previewEditImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imageEditPreview').src = e.target.result;
                document.getElementById('imageEditPreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
