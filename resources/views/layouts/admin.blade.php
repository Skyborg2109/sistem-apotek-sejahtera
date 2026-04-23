<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Apotek Sejahtera</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Custom Styles -->
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .active-dot {
            transition: all 0.3s ease;
        }
    </style>

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
    @stack('styles')
</head>
<body class="flex h-screen bg-slate-50 font-sans text-slate-800 overflow-hidden">

    <!-- SIDEBAR -->
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- TOPBAR -->
        @include('admin.partials.topbar')

        <!-- DASHBOARD CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 no-scrollbar pb-24">
            @yield('content')
        </main>
    </div>

    <!-- MODALS -->
    @stack('modals')

    <!-- SCRIPTS -->
    <script>
        // Common UI Logic
        document.getElementById('btn-notif')?.addEventListener('click', () => {
            document.getElementById('panel-notif')?.classList.toggle('hidden');
        });
        document.getElementById('btn-profile')?.addEventListener('click', () => {
            document.getElementById('panel-profile')?.classList.toggle('hidden');
        });

        function toggleModal(id) {
            document.getElementById(id)?.classList.toggle('hidden');
        }

        // Sidebar Mobile Toggle
        const btnSidebarMobile = document.getElementById('btn-sidebar-mobile');
        const sidebarAdmin = document.getElementById('sidebar-admin');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        btnSidebarMobile?.addEventListener('click', () => {
            sidebarAdmin?.classList.remove('-translate-x-full');
            sidebarOverlay?.classList.remove('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebarAdmin?.classList.add('-translate-x-full');
            sidebarOverlay?.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!document.getElementById('dropdown-wrapper-notif')?.contains(e.target)) {
                document.getElementById('panel-notif')?.classList.add('hidden');
            }
            if (!document.getElementById('dropdown-wrapper-profile')?.contains(e.target)) {
                document.getElementById('panel-profile')?.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
