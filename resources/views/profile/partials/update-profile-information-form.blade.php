<h2><i class="bi bi-person me-1" style="color:var(--accent)"></i> Informations du profil</h2>
<p class="subtitle">Modifiez votre nom, votre email et votre photo de profil.</p>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    {{-- Photo de profil --}}
    <div class="mb-3">
        <label class="form-label-soft">Photo de profil</label>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-preview" id="avatar-preview-wrap">
                @if($user->photo)
                    <img id="photo-preview" src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                @else
                    <span id="photo-preview-letter">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>

            <label for="photo" class="btn-upload mb-0">
                <i class="bi bi-upload me-1"></i> Changer la photo
            </label>
            <input
                id="photo"
                name="photo"
                type="file"
                accept="image/png, image/jpeg, image/jpg, image/webp"
                class="d-none"
                onchange="previewPhoto(this)"
            >
        </div>
        @error('photo')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="name" class="form-label-soft">Nom</label>
        <input id="name" name="name" type="text" class="form-control-soft" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label-soft">Email</label>
        <input id="email" name="email" type="email" class="form-control-soft" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="field-error">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="mb-0" style="font-size:12.5px;color:var(--slate-500)">
                    Votre adresse email n'est pas vérifiée.
                    <button form="send-verification" class="btn btn-link p-0" style="font-size:12.5px">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 mb-0" style="font-size:12.5px;color:#059669;font-weight:600">
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center gap-3 mt-3">
        <button type="submit" class="btn-save">Enregistrer</button>

        @if (session('status') === 'profile-updated')
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

<script>
function previewPhoto(input) {
    const wrap = document.getElementById('avatar-preview-wrap');
    if (input.files && input.files[0]) {
        const url = URL.createObjectURL(input.files[0]);
        wrap.innerHTML = '<img id="photo-preview" src="' + url + '">';
    }
}
</script>