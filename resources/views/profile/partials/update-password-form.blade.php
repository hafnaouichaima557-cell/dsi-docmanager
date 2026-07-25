<h2><i class="bi bi-key me-1" style="color:var(--accent)"></i> Mot de passe</h2>
<p class="subtitle">Utilisez un mot de passe long et unique pour sécuriser votre compte.</p>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label-soft">Mot de passe actuel</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control-soft" autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label-soft">Nouveau mot de passe</label>
        <input id="update_password_password" name="password" type="password" class="form-control-soft" autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label-soft">Confirmer le mot de passe</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control-soft" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3 mt-3">
        <button type="submit" class="btn-save">Enregistrer</button>

        @if (session('status') === 'password-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="status-saved"
            ><i class="bi bi-check-circle-fill"></i> Enregistré.</span>
        @endif
    </div>
</form>