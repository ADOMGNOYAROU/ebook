<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — {{ config('app.name', 'BookFlow') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        .login-bg {
            background-color: #0f0c29;
            background-image:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(99,102,241,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 110%, rgba(139,92,246,0.3) 0%, transparent 60%);
            min-height: 100vh;
        }

        .login-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .input-field {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            transition: all .2s;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.3); }
        .input-field:focus {
            outline: none;
            border-color: rgba(99,102,241,0.8);
            background: rgba(99,102,241,0.1);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
            transition: all .2s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            box-shadow: 0 12px 32px rgba(99,102,241,0.5);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }

        .social-btn {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all .2s;
        }
        .social-btn:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.2);
        }

        .error-badge {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
        }

        .label-text {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .divider-line {
            border-color: rgba(255,255,255,0.08);
        }
    </style>
</head>
<body>
<div class="login-bg flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="mb-8 text-center">
            <a href="{{ route('public.home') }}" class="inline-flex flex-col items-center gap-3">
                <span class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-2xl shadow-indigo-500/40">
                    <i class="fas fa-book-open text-2xl text-white"></i>
                </span>
                <span class="text-2xl font-black text-white tracking-tight">BookFlow</span>
            </a>
            <p class="mt-3 text-sm font-medium" style="color:rgba(255,255,255,0.45)">
                Content numérique. Lecture illimitée.
            </p>
        </div>

        {{-- Card --}}
        <div class="login-card rounded-3xl p-8">

            <div class="mb-6">
                <h1 class="text-xl font-black text-white">Connexion</h1>
                <p class="mt-1 text-sm" style="color:rgba(255,255,255,0.4)">
                    Pas de compte ?
                    <a href="{{ route('register') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition">Créer un compte gratuit</a>
                </p>
            </div>

            {{-- Alert erreur --}}
            @if(session('error') || $errors->has('email'))
            <div class="error-badge mb-5 flex items-start gap-3 rounded-2xl px-4 py-3">
                <i class="fas fa-circle-exclamation mt-0.5 text-rose-400"></i>
                <p class="text-sm font-semibold text-rose-300">
                    @if(session('error')){{ session('error') }}@else{{ $errors->first('email') }}@endif
                </p>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="label-text mb-2 block">Adresse email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:rgba(255,255,255,0.3)"></i>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               placeholder="vous@exemple.com"
                               class="input-field w-full rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="label-text">Mot de passe</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition">
                                Oublié ?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:rgba(255,255,255,0.3)"></i>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="input-field w-full rounded-2xl py-3.5 pl-11 pr-12 text-sm font-medium">
                        <button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 transition hover:text-white" style="color:rgba(255,255,255,0.3)">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs font-semibold text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-white/20 bg-white/10 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0">
                    <span class="text-sm font-medium" style="color:rgba(255,255,255,0.5)">Se souvenir de moi</span>
                </label>

                {{-- Submit --}}
                <button type="submit"
                        class="btn-login mt-2 flex w-full items-center justify-center gap-2.5 rounded-2xl py-3.5 text-sm font-bold text-white">
                    Se connecter
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <hr class="divider-line">
                <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 px-3 text-xs font-semibold" style="background:#ffffff08;color:rgba(255,255,255,0.3);border-radius:99px">
                    ou
                </span>
            </div>

            {{-- Social --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="#" class="social-btn flex items-center justify-center gap-2.5 rounded-2xl py-3 text-sm font-semibold text-white/70 hover:text-white">
                    <i class="fab fa-google"></i>
                    Google
                </a>
                <a href="#" class="social-btn flex items-center justify-center gap-2.5 rounded-2xl py-3 text-sm font-semibold text-white/70 hover:text-white">
                    <i class="fab fa-github"></i>
                    GitHub
                </a>
            </div>
        </div>

        {{-- Back --}}
        <div class="mt-6 text-center">
            <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 text-sm font-semibold transition hover:text-white" style="color:rgba(255,255,255,0.35)">
                <i class="fas fa-arrow-left text-xs"></i>
                Retour à l'accueil
            </a>
        </div>

    </div>
</div>

<script>
function togglePwd() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('eyeIcon');
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    ico.className = show ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
}
</script>
</body>
</html>
