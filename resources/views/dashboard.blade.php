@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

<div class="grid grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow text-center">
        <p class="text-4xl font-bold text-blue-800">{{ $totalDocuments }}</p>
        <p class="text-gray-500 mt-2">Total Documents</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow text-center">
        <p class="text-4xl font-bold text-yellow-500">{{ $pendingDocuments }}</p>
        <p class="text-gray-500 mt-2">En attente</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow text-center">
        <p class="text-4xl font-bold text-green-500">{{ $approvedDocuments }}</p>
        <p class="text-gray-500 mt-2">Approuvés</p>
    </div>
</div>
@endsection