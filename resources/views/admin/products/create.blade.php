@extends('layouts.admin')

@section('title', 'Create Product • TBM Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Create Product / Service</h1>
            <p class="text-xs text-secondary mt-0.5">Define new catalog item with integer cents base pricing.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Products</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Dedicated Hardware POS Hub" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">SKU Identifier <span class="text-danger">*</span></label>
                    <input type="text" name="sku" required value="{{ old('sku', 'TBM-' . rand(100, 999)) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Features and technical overview..." class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category" required value="{{ old('category', 'Hardware & Devices') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Base Price ($ USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price', '150.00') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" required value="{{ old('stock_quantity', '100') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Image URL / Path</label>
                <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="/images/products/pos-terminal.jpg or https://..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Or Upload Image from Computer</label>
                <input type="file" name="image_file" accept="image/*" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-[10px] text-secondary mt-1">JPG, PNG, GIF, WebP. Max 2MB. If both URL and file are provided, file upload takes priority.</p>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-text-rich cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-border-quiet text-primary focus:ring-primary">
                    <span>Active in POS and Public Catalog</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Save Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
