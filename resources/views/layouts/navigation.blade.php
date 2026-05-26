<nav x-data="{ open: false, userMenu: false }" class="fixed inset-x-0 top-0 z-50 border-b border-white/60 bg-white/85 backdrop-blur-2xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('public.home') }}" class="group flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 text-white shadow-lg shadow-indigo-500/25">
                    <i class="fas fa-book-open"></i>
                </span>
                <span>
                    <span class="block text-lg font-black tracking-tight text-slate-950">BookFlow</span>
                    <span class="block text-xs font-semibold uppercase tracking-[0.22em] text-indigo-500">SaaS Library</span>
                </span>
            </a>

            <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-white/80 p-1 shadow-sm lg:flex">
                <a href="{{ route('public.home') }}" class="rounded-full px-5 py-2 text-sm font-bold {{ request()->routeIs('public.home') ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">Accueil</a>
                <a href="{{ route('public.ebooks.index') }}" class="rounded-full px-5 py-2 text-sm font-bold {{ request()->routeIs('public.ebooks.*') ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">Catalogue</a>
                <a href="{{ route('public.categories.index') }}" class="rounded-full px-5 py-2 text-sm font-bold {{ request()->routeIs('public.categories.*') ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">Catégories</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full px-5 py-2 text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">Dashboard</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full px-5 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-950">Admin</a>
                    @endif
                @endauth
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    <a href="{{ route('favorites.index') }}" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-indigo-200 hover:text-indigo-600">
                        <i class="fas fa-heart"></i>
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 rounded-full border border-slate-200 bg-white py-1.5 pl-2 pr-4 shadow-sm hover:border-indigo-200">
                            <img class="h-9 w-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=4f46e5&background=e0e7ff' }}" alt="{{ Auth::user()->name }}">
                            <span class="text-sm font-bold text-slate-700">{{ Str::limit(Auth::user()->name, 14) }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-3 w-64 overflow-hidden rounded-3xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-900/10">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-950"><i class="fas fa-user-circle text-indigo-500"></i> Mon profil</a>
                            <a href="{{ route('downloads.history') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-950"><i class="fas fa-download text-indigo-500"></i> Mes téléchargements</a>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-950"><i class="fas fa-chart-line text-indigo-500"></i> Tableau de bord</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-100 pt-2">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold text-rose-600 hover:bg-rose-50"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-5 py-3 text-sm font-bold text-slate-600 hover:bg-white hover:text-slate-950">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-slate-950 px-6 py-3 text-sm font-black text-white shadow-xl shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-indigo-600">Commencer</a>
                @endauth
            </div>

            <button @click="open = !open" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm sm:hidden">
                <i x-show="!open" class="fas fa-bars"></i>
                <i x-show="open" x-cloak class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-5 shadow-xl sm:hidden">
        <div class="space-y-2">
            <a href="{{ route('public.home') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Accueil</a>
            <a href="{{ route('public.ebooks.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Catalogue</a>
            <a href="{{ route('public.categories.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Catégories</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full rounded-2xl px-4 py-3 text-left text-sm font-bold text-rose-600 hover:bg-rose-50">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-2xl px-4 py-3 text-center text-sm font-bold text-slate-700 hover:bg-slate-50">Connexion</a>
                <a href="{{ route('register') }}" class="block rounded-2xl bg-slate-950 px-4 py-3 text-center text-sm font-black text-white">Créer un compte</a>
            @endauth
        </div>
    </div>
</nav>
