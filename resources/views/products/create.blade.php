@extends('layouts.app')

@section('title', 'Add New Item / Product • TBM Catalog')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Items Catalog</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Add New Item / Product</h1>
        <p class="text-xs text-secondary mt-0.5">Register a new product in your inventory with pricing, 18% VAT, buying cost, and product photo.</p>
    </div>

    <!-- Limit Indicator Banner -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 shadow-xs flex items-center justify-between text-xs">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-lg">inventory_2</span>
            <span class="font-semibold text-text-rich">
                Product Capacity: <strong>{{ $currentProductCount }}</strong> / {{ $maxProducts === -1 ? 'Unlimited' : $maxProducts }} items used
            </span>
        </div>
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
            Active Catalog
        </span>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">sell</span>
                <span>Basic Item Information</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Product / Item Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. Sugar 50kg Sack">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">SKU / Item Code *</label>
                    <div class="flex gap-2">
                        <input type="text" name="sku" id="skuInput" value="{{ old('sku', $generatedSku) }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                        <button type="button" onclick="generateRandomSku()" class="px-2.5 py-2 bg-bg-subtle hover:bg-slate-200 border border-border-quiet rounded-lg text-xs font-bold text-secondary shrink-0" title="Generate New SKU">
                            <span class="material-symbols-outlined text-sm">autorenew</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Category *</label>
                    <input list="categoryList" name="category" value="{{ old('category', 'General Goods') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="Choose or type category...">
                    <datalist id="categoryList">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                        <option value="Agriculture & Produce">
                        <option value="Consumer Goods & Groceries">
                        <option value="Electronics & Hardware">
                        <option value="Industrial Materials">
                        <option value="Services & Consultancy">
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Initial Opening Stock *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="0">
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
                    <input type="number" name="price_cents" value="{{ old('price_cents') }}" min="0" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. 185000">
                    <span class="text-[10px] text-secondary mt-0.5 block">Customer invoice price</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Cost / Buying Price (UGX)</label>
                    <input type="number" name="cost_price_cents" value="{{ old('cost_price_cents') }}" min="0" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. 155000">
                    <span class="text-[10px] text-secondary mt-0.5 block">For profit margin & stock valuation</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">VAT Rate (%)</label>
                    <input type="number" name="vat_rate" step="0.01" value="{{ old('vat_rate', $defaultVat) }}" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                    <span class="text-[10px] text-primary font-bold mt-0.5 block">Standard Uganda 18.00% VAT</span>
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
                <label class="block text-xs font-bold text-text-rich mb-1">Upload Product Image</label>
                <div class="border-2 border-dashed border-border-quiet rounded-xl p-6 text-center hover:border-primary transition-colors cursor-pointer" onclick="document.getElementById('imageInput').click()">
                    <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImage(this)">
                    <div id="imagePreviewContainer" class="hidden mb-3">
                        <img id="imagePreview" src="" alt="Preview" class="w-24 h-24 object-cover rounded-xl mx-auto border border-border-quiet shadow-sm">
                    </div>
                    <span class="material-symbols-outlined text-3xl text-secondary block mb-1">add_photo_alternate</span>
                    <span class="text-xs font-bold text-text-rich block">Click to browse or drag & drop item photo</span>
                    <span class="text-[10px] text-secondary mt-0.5 block">PNG, JPG, WEBP up to 5MB</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Item Description / Specifications</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="Enter packaging details, dimensions, grade, manufacturer remarks..."></textarea>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all">
                Save Product & Create Item
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generateRandomSku() {
        const rand = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('skuInput').value = 'SKU-' + rand;
    }
</script>
@endsection
