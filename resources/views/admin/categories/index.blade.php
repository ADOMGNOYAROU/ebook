@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Catégories</h1>
            <p class="text-gray-600">Ajoutez, modifiez ou supprimez des catégories d'e-books</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button type="button" 
                    onclick="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Nouvelle Catégorie
            </button>
        </div>
    </div>

    <!-- Tableau des catégories -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nom
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Icône
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            E-books
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date de création
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->icon)
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                            <i class="{{ $category->icon }}"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <code class="text-xs">{{ $category->icon ?? 'Aucune icône' }}</code>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $category->ebooks_count }} e-book{{ $category->ebooks_count > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button type="button" 
                                            onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->icon }}', '{{ $category->description }}')"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2" 
                                            title="Modifier">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    @if($category->ebooks_count === 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed" title="Impossible de supprimer une catégorie contenant des e-books">
                                            <i class="far fa-trash-alt"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune catégorie trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($categories->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                            Précédent
                        </span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </a>
                    @endif
                    
                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                            Suivant
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-medium">{{ $categories->firstItem() }}</span>
                            à
                            <span class="font-medium">{{ $categories->lastItem() }}</span>
                            sur
                            <span class="font-medium">{{ $categories->total() }}</span>
                            résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <!-- Previous Page Link -->
                            @if($categories->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            <!-- Pagination Elements -->
                            @for($i = 1; $i <= $categories->lastPage(); $i++)
                                @if($i == $categories->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $categories->url($i) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            <!-- Next Page Link -->
                            @if($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de création/édition -->
<div id="categoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="categoryForm" action="" method="POST">
                @csrf
                <div id="formMethod"></div>
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-tag text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                                Nouvelle Catégorie
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Le nom de la catégorie sera utilisé pour générer un slug automatiquement.</p>
                                </div>
                                
                                <div>
                                    <label for="icon" class="block text-sm font-medium text-gray-700">Icône (Font Awesome)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i id="iconPreview" class="fas fa-fw text-gray-400"></i>
                                        </div>
                                        <input type="text" name="icon" id="icon" 
                                               class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               placeholder="Ex: fa-book">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Utilisez une icône de <a href="https://fontawesome.com/icons" target="_blank" class="text-indigo-600 hover:text-indigo-800">Font Awesome</a> (ex: book, film, music).
                                    </p>
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="description" name="description" rows="3" 
                                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Enregistrer
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ouvrir la modal de création
    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Nouvelle Catégorie';
        document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
        document.getElementById('formMethod').innerHTML = '';
        document.getElementById('name').value = '';
        document.getElementById('icon').value = '';
        document.getElementById('description').value = '';
        document.getElementById('iconPreview').className = 'fas fa-fw text-gray-400';
        document.getElementById('categoryModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    // Ouvrir la modal d'édition
    function openEditModal(id, name, icon, description) {
        document.getElementById('modalTitle').textContent = 'Modifier la Catégorie';
        document.getElementById('categoryForm').action = '{{ url("admin/categories") }}/' + id;
        document.getElementById('formMethod').innerHTML = '@method('PUT')';
        document.getElementById('name').value = name;
        document.getElementById('icon').value = icon || '';
        document.getElementById('description').value = description || '';
        
        // Mettre à jour l'aperçu de l'icône
        const iconPreview = document.getElementById('iconPreview');
        if (icon) {
            iconPreview.className = 'fas fa-fw ' + icon;
        } else {
            iconPreview.className = 'fas fa-fw text-gray-400';
        }
        
        document.getElementById('categoryModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    // Fermer la modal
    function closeModal() {
        document.getElementById('categoryModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Mettre à jour l'aperçu de l'icône en temps réel
    document.getElementById('icon').addEventListener('input', function(e) {
        const iconPreview = document.getElementById('iconPreview');
        if (e.target.value) {
            iconPreview.className = 'fas fa-fw ' + e.target.value;
        } else {
            iconPreview.className = 'fas fa-fw text-gray-400';
        }
    });
    
    // Gérer la soumission du formulaire
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        if (!name) {
            e.preventDefault();
            alert('Le nom de la catégorie est requis.');
            return false;
        }
        return true;
    });
    
    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush

@endsection
