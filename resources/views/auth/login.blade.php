<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>NextClean Admin - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface-variant": "#5a3f47",
                        "on-tertiary-container": "#eff3ff",
                        "on-secondary": "#ffffff",
                        "outline": "#8e6f77",
                        "on-primary-fixed": "#3e001d",
                        "surface-tint": "#b90062",
                        "primary-container": "#d80073",
                        "on-tertiary": "#ffffff",
                        "on-surface": "#191c1d",
                        "inverse-surface": "#2e3132",
                        "on-tertiary-fixed-variant": "#3d4759",
                        "inverse-primary": "#ffb1c7",
                        "primary-fixed": "#ffd9e2",
                        "tertiary-fixed-dim": "#bdc7dc",
                        "outline-variant": "#e2bdc6",
                        "secondary-container": "#dfe0e0",
                        "surface": "#f8f9fa",
                        "on-primary-fixed-variant": "#8e0049",
                        "secondary-fixed": "#e2e2e2",
                        "on-tertiary-fixed": "#121c2c",
                        "surface-bright": "#f8f9fa",
                        "primary-fixed-dim": "#ffb1c7",
                        "on-error": "#ffffff",
                        "surface-variant": "#e1e3e4",
                        "on-primary-container": "#fff0f2",
                        "secondary": "#5d5f5f",
                        "error": "#ba1a1a",
                        "background": "#f8f9fa",
                        "surface-container-low": "#f3f4f5",
                        "on-background": "#191c1d",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-container": "#616363",
                        "primary": "#ab005a",
                        "surface-container": "#edeeef",
                        "secondary-fixed-dim": "#c6c6c7",
                        "on-secondary-fixed": "#1a1c1c",
                        "tertiary": "#4d5769",
                        "inverse-on-surface": "#f0f1f2",
                        "tertiary-container": "#656f82",
                        "surface-dim": "#d9dadb",
                        "tertiary-fixed": "#d9e3f9",
                        "on-secondary-fixed-variant": "#454747",
                        "surface-container-highest": "#e1e3e4",
                        "on-primary": "#ffffff",
                        "on-error-container": "#93000a",
                        "surface-container-high": "#e7e8e9",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "display-lg": ["Inter"],
                        "headline-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "body-sm": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-sm": ["11px", { "lineHeight": "14px", "fontWeight": "700" }],
                        "label-md": ["13px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "body-sm": ["12px", { "lineHeight": "18px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background min-h-screen flex flex-col justify-center items-center text-on-background p-4">
    <main class="w-full max-w-md bg-surface-container-lowest rounded-lg border border-surface-variant p-8 flex flex-col items-center shadow-sm">
        <!-- Brand Header -->
        <div class="mb-8 text-center flex flex-col items-center gap-2">
            <div class="w-12 h-12 bg-primary-container rounded-lg flex items-center justify-center text-on-primary-container mb-2 shadow-sm">
                <span class="material-symbols-outlined text-headline-md" style="font-variation-settings: 'FILL' 1;">local_laundry_service</span>
            </div>
            <h1 class="text-headline-md font-headline-md text-on-surface">NextClean Admin</h1>
            <p class="text-body-md font-body-md text-secondary">Laundry Operations Management</p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" class="w-full flex flex-col gap-6" method="POST">
            @csrf
            
            <div class="flex flex-col gap-1.5">
                <label class="text-label-sm font-label-sm text-on-surface-variant" for="email">Email</label>
                <input class="w-full h-10 px-3 bg-surface-bright border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-body-md font-body-md text-on-surface transition-colors @error('email') border-error @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required type="email" autofocus />
                @error('email')
                    <span class="text-error text-xs mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center">
                    <label class="text-label-sm font-label-sm text-on-surface-variant" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-label-sm font-label-sm text-primary hover:text-surface-tint transition-colors" href="{{ route('password.request') }}">Lupa Password?</a>
                    @endif
                </div>
                <div class="relative">
                    <input class="w-full h-10 px-3 pr-10 bg-surface-bright border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-body-md font-body-md text-on-surface transition-colors @error('password') border-error @enderror" id="password" name="password" placeholder="Masukkan password" required type="password"/>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-on-surface transition-colors" type="button" onclick="togglePassword()">
                        <span class="material-symbols-outlined text-[20px]" id="toggleIcon">visibility_off</span>
                    </button>
                </div>
                @error('password')
                    <span class="text-error text-xs mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-outline-variant text-primary focus:ring-primary" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="text-body-md text-on-surface-variant cursor-pointer">Remember Me</label>
            </div>

            <button class="w-full h-10 mt-2 bg-primary-container hover:bg-surface-tint text-on-primary-container text-label-md font-label-md rounded flex items-center justify-center transition-colors active:scale-[0.98]" type="submit">
                Masuk
            </button>
        </form>
    </main>

    <!-- Footer -->
    <footer class="mt-8 text-center">
        <p class="text-body-sm font-body-sm text-secondary">© {{ date('Y') }} NextClean. All rights reserved.</p>
    </footer>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>
