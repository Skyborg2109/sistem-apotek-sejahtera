<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Apotek Sejahtera</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-slate-900 font-sans text-slate-800 min-h-screen flex items-center justify-center relative overflow-y-auto py-10 selection:bg-emerald-200">

  <!-- Background Image with Overlay -->
  <div class="fixed inset-0 z-0">
    <img src="{{ asset('pharmacy_login_bg_1776941605890.png') }}" alt="Background" class="w-full h-full object-cover scale-105 animate-[pulse_10s_infinite_alternate]">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-slate-900 via-transparent to-emerald-900/20"></div>
  </div>

  <!-- Login Container -->
  <div class="w-full max-w-[420px] px-6 relative z-10 animate-[fade-in_0.5s_ease-out]">
    
    <!-- Header Logo -->
    <div class="text-center mb-8">
        @php
            $logo = \App\Models\Setting::getValue('app_logo');
        @endphp
        @if($logo)
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl overflow-hidden shadow-lg shadow-emerald-500/10 border border-white">
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 mx-auto mb-4">
                <i class="ph ph-activity text-white text-4xl"></i>
            </div>
        @endif
        <h1 class="text-2xl font-bold text-white tracking-tight mb-1">{{ \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera') }}</h1>
        <p class="text-sm text-slate-300">Sistem Manajemen & Kasir Terpadu</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white/95 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.25)] border border-white/50 p-10 relative overflow-hidden">
      <!-- Decorative element inside card -->
      <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
      <div class="mb-8 text-center">
        <h2 class="text-xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun Anda.</p>
      </div>

      <div id="general-error" class="hidden mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-xs font-semibold flex items-center gap-3 animate-[fade-in_0.3s_ease-out]">
        <i class="ph ph-warning-circle text-lg"></i>
        <span id="general-error-message"></span>
      </div>

      <form id="login-form" class="space-y-5" novalidate>
        @csrf
        <!-- Input Username/Email -->
        <div class="space-y-1.5">
          <label class="text-sm font-semibold text-slate-700">Username atau Email</label>
          <div class="relative">
            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input 
              type="text" 
              name="login"
              id="login"
              placeholder="Masukkan username..." 
              class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800"
            >
          </div>
          <p id="error-login" class="text-[11px] text-rose-500 font-semibold mt-1 hidden animate-[fade-in_0.2s_ease-out]"></p>
        </div>

        <!-- Input Password -->
        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-sm font-semibold text-slate-700">Password</label>
            <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Lupa password?</a>
          </div>
          <div class="relative">
            <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input 
              type="password" 
              name="password"
              id="password"
              placeholder="••••••••" 
              class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800 tracking-wider"
            >
            <button type="button" id="btn-toggle-pw" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
              <i class="ph ph-eye-slash text-lg" id="icon-pw"></i>
            </button>
          </div>
          <p id="error-password" class="text-[11px] text-rose-500 font-semibold mt-1 hidden animate-[fade-in_0.2s_ease-out]"></p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2 pt-1">
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="remember" class="sr-only peer">
            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
          </label>
          <span class="text-sm text-slate-600 font-medium">Ingat saya</span>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          id="btn-login"
          class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 mt-4 active:scale-[0.98]"
        >
          <span>Masuk ke Sistem</span>
          <i class="ph ph-arrow-right text-lg"></i>
        </button>
      </form>

      <!-- Mockup Navigation Helpers (Optional) -->
      <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-[10px] text-slate-400 mb-3 font-bold uppercase tracking-widest">Akses Cepat (Demo):</p>
        <div class="grid grid-cols-2 gap-2">
          <div class="px-3 py-2 bg-slate-50 text-slate-600 text-[10px] font-bold rounded-xl border border-slate-200/60 transition-all hover:bg-emerald-50 hover:border-emerald-200 cursor-default">
            Admin: budisantoso
          </div>
          <div class="px-3 py-2 bg-slate-50 text-slate-600 text-[10px] font-bold rounded-xl border border-slate-200/60 transition-all hover:bg-emerald-50 hover:border-emerald-200 cursor-default">
            Kasir: sitirahma
          </div>
        </div>
      </div>

    </div>
    
    <p class="text-center text-xs text-slate-300 mt-8">
      &copy; 2026 {{ \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera') }}. All rights reserved.
    </p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const pwInput = document.getElementById('password');
      const btnTogglePw = document.getElementById('btn-toggle-pw');
      const iconPw = document.getElementById('icon-pw');
      const loginForm = document.getElementById('login-form');
      const btnLogin = document.getElementById('btn-login');
      const errorAlert = document.getElementById('error-alert');
      const errorMessage = document.getElementById('error-message');

      // 1. Toggle Password Visibility
      btnTogglePw.addEventListener('click', () => {
        if (pwInput.type === 'password') {
          pwInput.type = 'text';
          iconPw.classList.replace('ph-eye-slash', 'ph-eye');
        } else {
          pwInput.type = 'password';
          iconPw.classList.replace('ph-eye', 'ph-eye-slash');
        }
      });

      // 2. Login Handling
      loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Reset States
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('input').forEach(el => el.classList.remove('border-rose-400', 'ring-rose-100'));
        document.getElementById('general-error').classList.add('hidden');
        
        // Status Loading
        const originalContent = btnLogin.innerHTML;
        btnLogin.innerHTML = `<i class="ph ph-spinner-gap animate-spin text-xl"></i> <span>Memverifikasi...</span>`;
        btnLogin.disabled = true;
        btnLogin.classList.add('opacity-80', 'cursor-not-allowed');

        const formData = new FormData(loginForm);

        try {
            const response = await fetch('{{ route("login.post") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                btnLogin.innerHTML = `<i class="ph ph-check-circle text-xl"></i> <span>Berhasil! Mengalihkan...</span>`;
                btnLogin.classList.replace('bg-emerald-500', 'bg-blue-500');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 800);
            } else {
                // Handle Validation Errors (422)
                if (response.status === 422 && result.errors) {
                    Object.entries(result.errors).forEach(([field, messages]) => {
                        const errorEl = document.getElementById(`error-${field}`);
                        const inputEl = document.getElementById(field);
                        if (errorEl) {
                            errorEl.textContent = messages[0];
                            errorEl.classList.remove('hidden');
                        }
                        if (inputEl) {
                            inputEl.classList.add('border-rose-400', 'ring-2', 'ring-rose-100');
                        }
                    });
                } else {
                    // Handle General Authentication Errors (e.g., Wrong credentials)
                    document.getElementById('general-error-message').textContent = result.message || 'Login gagal.';
                    document.getElementById('general-error').classList.remove('hidden');
                }
                throw new Error('Authentication failed');
            }
        } catch (error) {
            // Reset Button
            btnLogin.innerHTML = originalContent;
            btnLogin.disabled = false;
            btnLogin.classList.remove('opacity-80', 'cursor-not-allowed');
        }
      });
    });
  </script>
</body>
</html>
