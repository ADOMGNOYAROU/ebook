@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('subtitle', 'Gérez tous les membres de la plateforme')

@section('content')

{{-- KPIs --}}
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $uStats = [
            ['label'=>'Total',       'val'=> \App\Models\User::count(),                                 'icon'=>'fa-users',       'bg'=>'bg-indigo-50',  'ic'=>'text-indigo-600'],
            ['label'=>'Actifs',      'val'=> \App\Models\User::whereNotNull('email_verified_at')->count(),'icon'=>'fa-user-check',  'bg'=>'bg-emerald-50', 'ic'=>'text-emerald-600'],
            ['label'=>'En attente',  'val'=> \App\Models\User::whereNull('email_verified_at')->count(),  'icon'=>'fa-user-clock',  'bg'=>'bg-amber-50',   'ic'=>'text-amber-600'],
            ['label'=>'Admins',      'val'=> \App\Models\User::where('role','admin')->count(),           'icon'=>'fa-user-shield', 'bg'=>'bg-violet-50',  'ic'=>'text-violet-600'],
        ];
    @endphp
    @foreach($uStats as $s)
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl {{ $s['bg'] }}">
            <i class="fas {{ $s['icon'] }} {{ $s['ic'] }}"></i>
        </div>
        <div>
            <p class="text-xl font-black text-slate-900">{{ $s['val'] }}</p>
            <p class="text-xs font-semibold text-slate-400">{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="mb-5 flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-1 flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
               class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20 min-w-[160px]">
        <select name="role" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-amber-400 focus:outline-none">
            <option value="">Tous les rôles</option>
            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
            <option value="user"  {{ request('role')=='user' ?'selected':'' }}>Utilisateur</option>
        </select>
        <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-700">
            <i class="fas fa-search mr-1.5"></i>Filtrer
        </button>
        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Reset</a>
    </form>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/25 hover:from-amber-600 hover:to-orange-600">
        <i class="fas fa-user-plus text-xs"></i> Ajouter
    </a>
</div>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Utilisateur</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Rôle</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Statut</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Inscrit</th>
                    <th class="px-6 py-3.5 text-right text-xs font-black uppercase tracking-wider text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=f59e0b&background=fef3c7' }}"
                                 class="h-9 w-9 flex-shrink-0 rounded-full ring-2 ring-slate-100" alt="">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="rounded-full bg-violet-50 px-2.5 py-1 text-xs font-bold text-violet-700">Admin</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">Utilisateur</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->email_verified_at)
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Actif</span>
                        @else
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">En attente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <i class="fas fa-users mb-3 block text-4xl text-slate-200"></i>
                        <p class="text-sm font-semibold text-slate-400">Aucun utilisateur trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
        <p class="text-xs font-semibold text-slate-400">{{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }}</p>
        <div class="flex gap-1">
            @if(!$users->onFirstPage())
                <a href="{{ $users->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-left text-xs"></i></a>
            @endif
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-right text-xs"></i></a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
