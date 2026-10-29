@extends('layouts.admin')

@section('title', 'Featured Companies & Partners • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header & Add CTA -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Featured Companies & Partners</h1>
            <p class="text-sm text-secondary mt-0.5">Manage the trusted company showcase displayed on the public homepage.</p>
        </div>
        <button type="button" id="addCompanyBtn" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Add Company</span>
        </button>
    </div>

    <!-- Companies Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Logo</th>
                        <th class="px-4 py-3.5">Name</th>
                        <th class="px-4 py-3.5">Industry</th>
                        <th class="px-4 py-3.5">Website</th>
                        <th class="px-4 py-3.5 text-center">Order</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($companies as $company)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                @if($company->logo_url)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-10 h-10 object-contain rounded-lg bg-bg-subtle border border-border-quiet" loading="lazy">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center font-bold text-primary text-sm">
                                        {{ $company->monogram }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-text-rich">{{ $company->name }}</td>
                            <td class="px-4 py-3.5 text-secondary">{{ $company->industry ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline text-xs flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">open_in_new</span>
                                        <span class="truncate max-w-xs">{{ $company->website }}</span>
                                    </a>
                                @else
                                    <span class="text-outline">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold font-numeric text-text-rich">{{ $company->sort_order }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $company->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $company->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" class="editCompanyBtn p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit"
                                        data-id="{{ $company->id }}"
                                        data-name="{{ $company->name }}"
                                        data-description="{{ $company->description }}"
                                        data-industry="{{ $company->industry }}"
                                        data-website="{{ $company->website }}"
                                        data-logo_url="{{ $company->logo_url }}"
                                        data-sort_order="{{ $company->sort_order }}"
                                        data-is_active="{{ $company->is_active }}">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>

                                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Remove this company from the public showcase?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">
                                No featured companies yet. Click "Add Company" to start building the showcase.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>
</div>

<!-- Add/Edit Company Modal -->
<div id="companyModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-surface rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-hidden flex flex-col">
            <form id="companyForm" action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="company_id" id="company_id" value="">

                <div class="px-6 py-4 border-b border-border-quiet flex items-center justify-between">
                    <h2 id="modalTitle" class="font-headline font-bold text-lg text-text-rich">Add Company</h2>
                    <button type="button" id="closeModalBtn" class="p-1.5 text-secondary hover:text-danger hover:bg-red-50 rounded-lg transition-colors" aria-label="Close modal">
                        <span class="material-symbols-outlined text-xl font-bold">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto">
                    <div>
                        <label for="name" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" required maxlength="255" placeholder="e.g. Victoria Logistics & Trading Ltd" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="3" maxlength="1000" placeholder="Brief description of the company..." class="w-full p-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none"></textarea>
                    </div>

                    <div>
                        <label for="industry" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Industry</label>
                        <input type="text" name="industry" id="industry" maxlength="120" placeholder="e.g. Logistics & Trade, Agriculture, Technology" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                    </div>

                    <div>
                        <label for="website" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Website URL</label>
                        <input type="url" name="website" id="website" maxlength="255" placeholder="https://example.com" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                    </div>

                    <div>
                        <label for="logo_url" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Logo Image URL</label>
                        <input type="url" name="logo_url" id="logo_url" maxlength="500" placeholder="https://example.com/logo.png (leave empty for monogram)" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                        <p class="text-[10px] text-secondary mt-1">PNG/SVG recommended. Transparent background preferred.</p>
                    </div>

                    <div>
                        <label for="logo_file" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Or Upload Logo from Computer</label>
                        <input type="file" name="logo_file" id="logo_file" accept="image/*" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <p class="text-[10px] text-secondary mt-1">JPG, PNG, SVG. Max 2MB. If both URL and file are provided, file upload takes priority.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0" value="0" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                        </div>

                        <div class="flex items-end">
                            <label class="flex items-center gap-2 cursor-pointer w-full">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-primary border-border-quiet rounded focus:ring-primary focus:ring-2">
                                <span class="text-xs font-semibold text-text-rich">Active (show on public site)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-border-quiet bg-bg-subtle flex items-center justify-end gap-3">
                    <button type="button" id="cancelModalBtn" class="px-4 py-2 rounded-lg text-xs font-semibold text-secondary border border-border-quiet hover:bg-surface transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 rounded-lg text-xs font-bold text-white bg-primary hover:bg-primary-container shadow-sm transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">save</span>
                        <span id="submitText">Add Company</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('companyModal');
    const form = document.getElementById('companyForm');
    const addBtn = document.getElementById('addCompanyBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const editBtns = document.querySelectorAll('.editCompanyBtn');
    const modalTitle = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    const submitBtn = document.getElementById('submitBtn');
    const companyIdInput = document.getElementById('company_id');

    function openModal(editData = null) {
        form.reset();
        companyIdInput.value = '';
        
        if (editData) {
            modalTitle.textContent = 'Edit Company';
            submitText.textContent = 'Save Changes';
            companyIdInput.value = editData.id;
            
            document.getElementById('name').value = editData.name || '';
            document.getElementById('description').value = editData.description || '';
            document.getElementById('industry').value = editData.industry || '';
            document.getElementById('website').value = editData.website || '';
            document.getElementById('logo_url').value = editData.logo_url || '';
            document.getElementById('logo_file').value = '';
            document.getElementById('sort_order').value = editData.sort_order || 0;
            document.getElementById('is_active').checked = editData.is_active === 'true' || editData.is_active === true;
            
            form.action = `{{ route('admin.companies.update', ':id') }}`.replace(':id', editData.id);
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        } else {
            modalTitle.textContent = 'Add Company';
            submitText.textContent = 'Add Company';
            form.action = '{{ route('admin.companies.store') }}';
            let methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (addBtn) addBtn.addEventListener('click', () => openModal());
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    
    modal.querySelector('.fixed.inset-0.bg-slate-900\\/40').addEventListener('click', closeModal);

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            openModal({
                id: this.dataset.id,
                name: this.dataset.name,
                description: this.dataset.description,
                industry: this.dataset.industry,
                website: this.dataset.website,
                logo_url: this.dataset.logo_url,
                sort_order: this.dataset.sort_order,
                is_active: this.dataset.is_active,
            });
        });
    });

    // Handle form submit with SweetAlert confirmation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const isEdit = !!companyIdInput.value;
        const action = isEdit ? 'updated' : 'added';
        
        Swal.fire({
            title: isEdit ? 'Save Changes?' : 'Add Company?',
            text: isEdit ? 'This will update the featured company.' : 'This company will appear on the public homepage.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#003e97',
            confirmButtonText: isEdit ? 'Yes, save changes' : 'Yes, add it',
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endpush