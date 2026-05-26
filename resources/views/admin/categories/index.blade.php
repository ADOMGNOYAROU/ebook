@extends('layouts.admin')

@section('title', 'Catégories')
@section('subtitle', 'Organisez les e-books par catégorie')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-end">
        <button type="button" onclick="openCreateModal()"
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/25 transition hover:from-amber-600 hover:to-orange-600">
            <i class="fas fa-plus text-xs"></i> Nouvelle catégorie
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Catégorie</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Icône</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">E-books</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Créée le</th>
                        <th class="px-6 py-3.5 text-right text-xs font-black uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                                    <i class="{{ $category->icon ?? 'fas fa-tag' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $category->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-mono text-slate-600">{{ $category->icon ?? '—' }}</code>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                {{ $category->ebooks_count }} ebook{{ $category->ebooks_count > 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-400">{{ $category->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                        onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->icon }}', '{{ addslashes($category->description) }}')"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                @if($category->ebooks_count === 0)
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @else
                                <span class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-xl bg-slate-50 text-slate-300"
                                      title="Catégorie non vide">
                                    <i class="fas fa-trash text-xs"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <i class="fas fa-tags mb-3 block text-4xl text-slate-200"></i>
                            <p class="text-sm font-semibold text-slate-400">Aucune catégorie trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
            <p class="text-xs font-semibold text-slate-400">{{ $categories->firstItem() }}–{{ $categories->lastItem() }} sur {{ $categories->total() }}</p>
            <div class="flex gap-1">
                @if(!$categories->onFirstPage())
                    <a href="{{ $categories->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif
                @if($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-right text-xs"></i></a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL --}}
<div id="categoryModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative w-full max-w-md rounded-3xl bg-white shadow-2xl">
        <form id="categoryForm" action="" method="POST">
            @csrf
            <div id="formMethod"></div>
            <div class="border-b border-slate-100 px-6 py-5">
                <h3 class="text-base font-black text-slate-900" id="modalTitle">Nouvelle catégorie</h3>
            </div>
            <div class="space-y-4 px-6 py-5">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Nom *</label>
                    <input type="text" name="name" id="name" required
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Icône Font Awesome</label>
                    <div class="relative">
                        <i id="iconPreview" class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="icon" id="icon"
                               placeholder="fas fa-book"
                               class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <button type="button" onclick="closeModal()"
                        class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    Annuler
                </button>
                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/25 transition hover:from-amber-600 hover:to-orange-600">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nouvelle catégorie';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('name').value = '';
    document.getElementById('icon').value = '';
    document.getElementById('description').value = '';
    document.getElementById('iconPreview').className = 'fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400';
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
}
function openEditModal(id, name, icon, description) {
    document.getElementById('modalTitle').textContent = 'Modifier la catégorie';
    document.getElementById('categoryForm').action = '{{ url("admin/categories") }}/' + id;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('name').value = name;
    document.getElementById('icon').value = icon || '';
    document.getElementById('description').value = description || '';
    document.getElementById('iconPreview').className = (icon ? icon : 'fas fa-tag') + ' absolute left-4 top-1/2 -translate-y-1/2 text-slate-400';
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryModal').classList.add('flex');
}
function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryModal').classList.remove('flex');
}
document.getElementById('icon').addEventListener('input', function() {
    document.getElementById('iconPreview').className = (this.value || 'fas fa-tag') + ' absolute left-4 top-1/2 -translate-y-1/2 text-slate-400';
});
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });
</script>
@endpush

@endsection
