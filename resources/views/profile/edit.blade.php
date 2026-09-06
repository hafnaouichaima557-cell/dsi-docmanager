@extends('layouts.app')

@section('content')

<style>
    :root {
        --accent: #4f8ef7;
        --accent-100: #eaf1ff;
        --navy-950: #0d2b6b;
        --slate-500: #6b7686;
        --slate-300: #dfe3ea;
        --slate-100: #f4f6f9;
    }

    .profile-wrap { max-width: 720px; margin: 0 auto; }

    .page-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg, var(--accent), #0d2b6b);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 18px;
        box-shadow: 0 4px 10px rgba(79,142,247,0.35);
        flex-shrink: 0;
    }
    .page-title { font-weight: 800; color: var(--navy-950); margin-bottom: 2px; font-size: 21px; }
    .page-subtitle { font-size: 13px; color: var(--slate-500); }

    .btn-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 16px; border-radius: 10px;
        background: var(--slate-100); border: 1.5px solid var(--slate-300); color: var(--navy-950);
        font-size: 13.5px; font-weight: 600; text-decoration: none;
        transition: background .15s ease, border-color .15s ease;
    }
    .btn-back:hover { background: var(--accent-100); border-color: var(--accent); color: var(--navy-950); }

    .profile-card {
        background: #fff; border: 1px solid #ecf0f5; border-radius: 16px;
        box-shadow: 0 2px 16px rgba(13,43,107,0.06);
        padding: 1.75rem 1.85rem; margin-bottom: 1.25rem;
        position: relative; overflow: hidden;
    }
    .profile-card::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--accent), var(--navy-950));
    }
    .profile-card.danger::before { background: linear-gradient(90deg, #f87171, #dc2626); }

    .profile-card h2 { font-size: 16px; font-weight: 800; color: var(--navy-950); margin-bottom: 4px; }
    .profile-card p.subtitle { font-size: 12.5px; color: var(--slate-500); margin-bottom: 1.25rem; }

    .form-label-soft { font-size: 12.5px; font-weight: 700; color: var(--navy-950); margin-bottom: 6px; display:block; }
    .form-control-soft {
        border-radius: 10px; border: 1.5px solid var(--slate-300);
        font-size: 13.5px; background-color: var(--slate-100);
        padding: 9px 13px; width: 100%;
        transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
    }
    .form-control-soft:focus {
        outline: none; border-color: var(--accent); background: #fff;
        box-shadow: 0 0 0 4px var(--accent-100);
    }
    .field-error { color: #dc2626; font-size: 12px; margin-top: 5px; }

    .btn-save {
        background: linear-gradient(135deg, var(--accent), #3f74e0);
        border: none; color: #fff; border-radius: 10px;
        padding: 9px 22px; font-size: 13.5px; font-weight: 600;
        box-shadow: 0 4px 10px rgba(79,142,247,0.3);
        transition: filter .15s ease, transform .15s ease;
    }
    .btn-save:hover { filter: brightness(1.06); color: #fff; transform: translateY(-1px); }

    .btn-danger-soft {
        background: linear-gradient(135deg, #f87171, #dc2626);
        border: none; color: #fff; border-radius: 10px;
        padding: 9px 22px; font-size: 13.5px; font-weight: 600;
        box-shadow: 0 4px 10px rgba(220,38,38,0.3);
        transition: filter .15s ease, transform .15s ease;
    }
    .btn-danger-soft:hover { filter: brightness(1.06); color: #fff; transform: translateY(-1px); }

    .avatar-preview {
        width: 68px; height: 68px; border-radius: 50%;
        object-fit: cover; border: 2px solid var(--accent-100);
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--accent), var(--navy-950));
        color: #fff; font-weight: 800; font-size: 26px; flex-shrink: 0;
    }
    .avatar-preview img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }

    .btn-upload {
        border-radius: 10px; border: 1.5px solid var(--slate-300);
        background: #fff; color: var(--navy-950);
        font-size: 12.5px; font-weight: 600; padding: 8px 15px;
        cursor: pointer; transition: background .15s ease, border-color .15s ease;
    }
    .btn-upload:hover { background: var(--accent-100); border-color: var(--accent); }

    .status-saved {
        font-size: 12.5px; color: #059669; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px;
    }
</style>

<div class="profile-wrap">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="page-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div>
                <div class="page-title">Mon profil</div>
                <div class="page-subtitle">Gérer vos informations personnelles</div>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <div class="profile-card">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Mot de passe et suppression de compte : réservés à l'admin et au responsable --}}
    @if(auth()->user()->hasRole('administrateur') || auth()->user()->hasRole('responsable'))

    <div class="profile-card">
        @include('profile.partials.update-password-form')
    </div>

    <div class="profile-card danger">
        @include('profile.partials.delete-user-form')
    </div>

    @endif

</div>

@endsection