<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // IMPORTANT : Spatie/laravel-permission met les rôles/permissions
        // en cache. Avec RefreshDatabase, la base est recréée à chaque
        // test mais le cache, lui, ne l'est pas forcément — on le vide
        // explicitement pour éviter les faux 403 aléatoires.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'administrateur', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'responsable', 'guard_name' => 'web']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrateur');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrateur');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrateur');

        // On vide le cache juste après l'assignation, par sécurité.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrateur');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    /**
     * Un utilisateur simple (sans rôle admin/responsable) ne peut PAS
     * modifier son email. La validation rejette toute valeur différente
     * de l'email actuel (voir ProfileUpdateRequest).
     */
    public function test_simple_user_cannot_edit_email(): void
    {
        $user = User::factory()->create();
        // Aucun rôle assigné volontairement : utilisateur simple

        $originalEmail = $user->email;

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'nouveau@example.com',
            ]);

        // La validation doit rejeter le changement d'email
        $response->assertSessionHasErrors('email');

        $user->refresh();

        // Le nom et l'email ne doivent pas avoir changé
        $this->assertNotSame('Test User', $user->name);
        $this->assertSame($originalEmail, $user->email);
    }

    /**
     * Un utilisateur simple PEUT modifier son nom et sa photo,
     * tant qu'il renvoie son email actuel (inchangé) dans le formulaire.
     */
    public function test_simple_user_can_edit_name_when_email_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nouveau Nom',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Nouveau Nom', $user->name);
    }

    /**
     * Un utilisateur simple ne peut pas supprimer son compte :
     * cette action est réservée à l'administrateur.
     */
    public function test_simple_user_cannot_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertForbidden();

        $this->assertNotNull($user->fresh());
    }

    /**
     * Un responsable ne peut pas supprimer son compte non plus :
     * réservé à l'administrateur uniquement.
     */
    public function test_responsable_cannot_delete_their_account(): void
    {
        $user = User::factory()->create();
        $user->assignRole('responsable');

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertForbidden();

        $this->assertNotNull($user->fresh());
    }
}