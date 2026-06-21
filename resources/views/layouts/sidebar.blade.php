<div class="w-56 bg-blue-900 flex flex-col min-h-screen">
    <div class="px-5 py-5 border-b border-white/10">
        <span class="text-white font-medium text-sm">Doc Flow</span>
        <small class="block text-white/50 text-xs mt-1">Direction des Systèmes d'Info</small>
    </div>
    <nav class="flex-1 py-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-sm {{ request()->routeIs('dashboard') ? 'text-white bg-white/15 border-l-4 border-blue-400' : 'text-white/65 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Tableau de bord
        </a>
        <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-5 py-3 text-sm {{ request()->routeIs('documents.*') ? 'text-white bg-white/15 border-l-4 border-blue-400' : 'text-white/65 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Documents
        </a>
        <a href="#" class="flex items-center gap-3 px-5 py-3 text-sm text-white/65 hover:bg-white/10 hover:text-white border-l-4 border-transparent">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Workflow
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-5 py-3 text-sm {{ request()->routeIs('users.*') ? 'text-white bg-white/15 border-l-4 border-blue-400' : 'text-white/65 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Utilisateurs
        </a>
        <a href="{{ route('audit.index') }}" class="flex items-center gap-3 px-5 py-3 text-sm {{ request()->routeIs('audit.*') ? 'text-white bg-white/15 border-l-4 border-blue-400' : 'text-white/65 hover:bg-white/10 hover:text-white border-l-4 border-transparent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Audit logs
        </a>
        @endif
    </nav>
    <div class="px-5 py-4 border-t border-white/10 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="text-white/75 text-xs">{{ auth()->user()->name }}</span>
    </div>
</div>