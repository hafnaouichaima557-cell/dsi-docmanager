@extends('layouts.app')

@section('content')

<style>
.dept-card {
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, #1a4fa0 0%, #0d2b6b 100%);
    color: #fff !important;
    text-decoration: none;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.25s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 8px 24px rgba(13,43,107,0.20);
    position: relative;
    overflow: hidden;
}
.dept-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(13,43,107,0.30);
    color: #fff !important;
}
.dept-card::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 110px; height: 110px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}
.dept-icon {
    width: 54px; height: 54px;
    border-radius: 14px;
    background: rgba(255,255,255,0.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}
.dept-name {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 2px;
}
.dept-count {
    font-size: 13px;
    color: rgba(255,255,255,0.75);
}
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h5 class="fw-bold mb-0" style="color:#0d2b6b">Choisir un département</h5>
</div>

<div class="row g-3">
    @forelse($departmentsWithCount as $dept)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('documents.index', ['department' => $dept['name']]) }}" class="dept-card">
                <div class="dept-icon"><i class="bi bi-building"></i></div>
                <div>
                    <p class="dept-name mb-0">{{ $dept['name'] }}</p>
                    <span class="dept-count">{{ $dept['count'] }} document{{ $dept['count'] > 1 ? 's' : '' }}</span>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-inbox" style="font-size:32px;color:#bfdbfe"></i>
            <p class="mt-2 mb-0">Aucun département trouvé</p>
        </div>
    @endforelse
</div>

@endsection