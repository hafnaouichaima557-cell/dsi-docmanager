@php($fullPage = true)
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="flex min-h-screen bg-gray-100">

        {{-- Sidebar --}}
        <div class="w-56 bg-blue-900 flex flex-col">
            <div class="px-5 py-5 border-b border-white/10">
                <span class="text-white font-medium text-sm">DSI DocManager</span>
                <small class="block text-white/50 text-xs mt-1">Direction des Systèmes d'Info</small>
            </div>
            <nav class="flex-1 py-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-white bg-white/15 border-l-4 border-blue-400 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Tableau de bord
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3 text-white/65 hover:bg-white/10 hover:text-white text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Documents
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3 text-white/65 hover:bg-white/10 hover:text-white text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Workflow
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3 text-white/65 hover:bg-white/10 hover:text-white text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Utilisateurs
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3 text-white/65 hover:bg-white/10 hover:text-white text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Audit logs
                </a>
            </nav>
            <div class="px-5 py-4 border-t border-white/10 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-white/75 text-xs">{{ auth()->user()->name }}</span>
            </div>
        </div>

        {{-- Main --}}
        <div class="flex-1 flex flex-col">

            {{-- Topbar --}}
            <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center gap-4">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Rechercher un document..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-blue-400">
                </div>
                <a href="#" class="flex items-center gap-2 bg-blue-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter un document
                </a>
            </div>

            {{-- Content --}}
            <div class="flex-1 p-6 space-y-6 overflow-y-auto">

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-5">
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Total documents</p>
                        <p class="text-3xl font-medium text-blue-900">{{ $totalDocuments }}</p>
                        <p class="text-xs text-gray-400 mt-1">Tous statuts confondus</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Documents validés</p>
                        <p class="text-3xl font-medium text-green-700">{{ $approvedDocuments }}</p>
                        <p class="text-xs text-gray-400 mt-1">Approuvés</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">En relecture</p>
                        <p class="text-3xl font-medium text-amber-700">{{ $pendingDocuments }}</p>
                        <p class="text-xs text-gray-400 mt-1">En attente d'approbation</p>
                    </div>
                </div>

                {{-- Bottom --}}
                <div class="grid grid-cols-2 gap-5">

                    {{-- Donut --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <p class="text-sm font-medium text-gray-700 mb-4">Répartition des documents</p>
                        <div class="flex items-center gap-6">
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <canvas id="donutChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-medium text-gray-800">{{ $totalDocuments }}</span>
                                    <span class="text-xs text-gray-400">total</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="w-3 h-3 rounded-full bg-blue-700 flex-shrink-0"></span>
                                    Validés
                                    <span class="ml-auto font-medium text-gray-800">{{ $approvedDocuments }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="w-3 h-3 rounded-full bg-amber-600 flex-shrink-0"></span>
                                    En relecture
                                    <span class="ml-auto font-medium text-gray-800">{{ $pendingDocuments }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="w-3 h-3 rounded-full bg-purple-600 flex-shrink-0"></span>
                                    Publiés
                                    <span class="ml-auto font-medium text-gray-800">{{ $publishedDocuments }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent docs --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <p class="text-sm font-medium text-gray-700 mb-4">Derniers documents</p>
                        @if($recentDocuments->isEmpty())
                            <p class="text-gray-400 text-sm text-center py-6">Aucun document pour le moment</p>
                        @else
                            <div class="space-y-3">
                                @foreach($recentDocuments as $doc)
                                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="flex-1 text-sm text-gray-700 truncate">{{ $doc->title }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full font-medium
                                        {{ $doc->status === 'approved'     ? 'bg-green-100 text-green-800' :
                                          ($doc->status === 'under_review' ? 'bg-amber-100 text-amber-800' :
                                          ($doc->status === 'published'    ? 'bg-purple-100 text-purple-800' :
                                           'bg-gray-100 text-gray-600')) }}">
                                        {{ $doc->status }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $approvedDocuments }}, {{ $pendingDocuments }}, {{ $publishedDocuments }}],
                    backgroundColor: ['#1d4ed8', '#d97706', '#7c3aed'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    </script>
    @endpush
</x-app-layout>