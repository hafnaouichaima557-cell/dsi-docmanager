<h2><i class="bi bi-exclamation-triangle me-1" style="color:#dc2626"></i> Supprimer le compte</h2>
<p class="subtitle">Une fois votre compte supprimé, toutes ses données seront définitivement effacées. Téléchargez vos documents importants avant de continuer.</p>

<button type="button" class="btn-danger-soft" onclick="document.getElementById('confirm-user-deletion').classList.toggle('d-none')">
    <i class="bi bi-trash me-1"></i> Supprimer le compte
</button>

<div id="confirm-user-deletion" class="d-none mt-3 p-3" style="background:#fef2f2;border-radius:12px;border:1px solid #fecaca">
    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <p style="font-size:13px;color:var(--navy-950);font-weight:600;margin-bottom:12px">
            Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.
        </p>

        <div class="mb-3">
            <label for="password" class="form-label-soft">Mot de passe</label>
            <input id="password" name="password" type="password" class="form-control-soft" placeholder="Confirmez avec votre mot de passe">
            @error('password', 'userDeletion')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-danger-soft">Confirmer la suppression</button>
            <button type="button" class="btn-back" onclick="document.getElementById('confirm-user-deletion').classList.add('d-none')">Annuler</button>
        </div>
    </form>
</div>