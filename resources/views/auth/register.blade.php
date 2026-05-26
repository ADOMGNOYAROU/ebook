<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un compte — {{ config('app.name', 'BookFlow') }}</title>
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

        .btn-register {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
            transition: all .2s;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            box-shadow: 0 12px 32px rgba(99,102,241,0.5);
            transform: translateY(-1px);
        }
        .btn-register:active { transform: translateY(0); }

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

        .divider-line { border-color: rgba(255,255,255,0.08); }

        .strength-bar {
            height: 3px;
            border-radius: 9px;
            transition: width .3s, background .3s;
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
                Rejoignez des milliers de lecteurs
            </p>
        </div>

        {{-- Card --}}
        <div class="login-card rounded-3xl p-8">

            <div class="mb-6">
                <h1 class="text-xl font-black text-white">Créer un compte</h1>
                <p class="mt-1 text-sm" style="color:rgba(255,255,255,0.4)">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition">Se connecter</a>
                </p>
            </div>

            {{-- Erreurs globales --}}
            @if($errors->any())
            <div class="error-badge mb-5 flex items-start gap-3 rounded-2xl px-4 py-3">
                <i class="fas fa-circle-exclamation mt-0.5 flex-shrink-0 text-rose-400"></i>
                <ul class="space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li class="text-sm font-semibold text-rose-300">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nom --}}
                <div>
                    <label for="name" class="label-text mb-2 block">Nom complet</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:rgba(255,255,255,0.3)"></i>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               value="{{ old('name') }}"
                               placeholder="Jean Dupont"
                               class="input-field w-full rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium">
                    </div>
                </div>

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
                    <label for="password" class="label-text mb-2 block">Mot de passe</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:rgba(255,255,255,0.3)"></i>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               placeholder="Min. 8 caractères"
                               oninput="checkStrength(this.value)"
                               class="input-field w-full rounded-2xl py-3.5 pl-11 pr-12 text-sm font-medium">
                        <button type="button" onclick="togglePwd('password','eye1')" class="absolute right-4 top-1/2 -translate-y-1/2 transition hover:text-white" style="color:rgba(255,255,255,0.3)">
                            <i class="fas fa-eye text-sm" id="eye1"></i>
                        </button>
                    </div>
                    {{-- Barre de force --}}
                    <div class="mt-2 flex gap-1">
                        <div id="s1" class="strength-bar flex-1 bg-white/10"></div>
                        <div id="s2" class="strength-bar flex-1 bg-white/10"></div>
                        <div id="s3" class="strength-bar flex-1 bg-white/10"></div>
                        <div id="s4" class="strength-bar flex-1 bg-white/10"></div>
                    </div>
                    <p id="strengthLabel" class="mt-1 text-xs font-semibold" style="color:rgba(255,255,255,0.3)"></p>
                </div>

                {{-- Confirm password --}}
                <div>
                    <label for="password_confirmation" class="label-text mb-2 block">Confirmer le mot de passe</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:rgba(255,255,255,0.3)"></i>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                               placeholder="••••••••"
                               class="input-field w-full rounded-2xl py-3.5 pl-11 pr-12 text-sm font-medium">
                        <button type="button" onclick="togglePwd('password_confirmation','eye2')" class="absolute right-4 top-1/2 -translate-y-1/2 transition hover:text-white" style="color:rgba(255,255,255,0.3)">
                            <i class="fas fa-eye text-sm" id="eye2"></i>
                        </button>
                    </div>
                </div>

                {{-- CGU --}}
                <label class="flex cursor-pointer items-start gap-3 pt-1">
                    <input type="checkbox" name="terms" required
                           class="mt-0.5 h-4 w-4 flex-shrink-0 rounded border-white/20 bg-white/10 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0">
                    <span class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.45)">
                        J'accepte les
                        <a href="#" class="font-bold text-indigo-400 hover:text-indigo-300 transition">conditions d'utilisation</a>
                        et la
                        <a href="#" class="font-bold text-indigo-400 hover:text-indigo-300 transition">politique de confidentialité</a>
                    </span>
                </label>

                {{-- Submit --}}
                <button type="submit"
                        class="btn-register mt-2 flex w-full items-center justify-center gap-2.5 rounded-2xl py-3.5 text-sm font-bold text-white">
                    Créer mon compte
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
                    <i class="fab fa-google"></i> Google
                </a>
                <a href="#" class="social-btn flex items-center justify-center gap-2.5 rounded-2xl py-3 text-sm font-semibold text-white/70 hover:text-white">
                    <i class="fab fa-github"></i> GitHub
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
function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    ico.className = show ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
}

function checkStrength(val) {
    const bars = ['s1','s2','s3','s4'];
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Faible','Moyen','Bon','Excellent'];

    bars.forEach((id, i) => {
        document.getElementById(id).style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.1)';
    });

    label.textContent = val.length ? labels[score - 1] || '' : '';
    label.style.color = val.length ? colors[score - 1] : 'rgba(255,255,255,0.3)';
}
</script>
</body>
</html>
