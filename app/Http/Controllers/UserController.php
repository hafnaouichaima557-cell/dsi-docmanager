<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // diag 6 : قائمة المستخدمين
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // diag 6 : فورم إضافة مستخدم
   public function create()
{
    // Responsable : يمكنه إنشاء مستخدم عادي فقط
    if (auth()->user()->hasRole('responsable')) {
        $roles = Role::where('name', 'utilisateur')->get();
    } else {
        // Admin : يرى جميع الأدوار
        $roles = Role::all();
    }

    return view('users.create', compact('roles'));
}
    // diag 6 : حفظ مستخدم جديد
   public function store(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:users',
        'password'   => 'required|min:8',
        'department' => 'nullable|string',
        'role'       => 'required|exists:roles,name',
        'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Si c'est un responsable
    if (auth()->user()->hasRole('responsable')) {

        // Il ne peut créer qu'un utilisateur simple
        $request->merge([
            'role' => 'utilisateur',
            'department' => auth()->user()->department,
        ]);
    }

    $user = User::create([
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'department' => $request->department,
        'is_active'  => true,
    ]);

    // Photo de profil
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('avatars', 'public');
        $user->update(['photo' => $path]);
    }

    // Attribution du rôle
    $user->assignRole($request->role);

    // Audit
    AuditLog::log(
        action     : 'created',
        module     : 'user',
        description: 'Utilisateur créé : ' . $user->email,
        model      : $user
    );

    return redirect()->route('users.index')
                     ->with('success', 'Utilisateur créé avec succès');
}

    // diag 7 : تعطيل مستخدم
    public function disable(Request $request, User $user)
    {
        $user->update(['is_active' => false]);

        // إلغاء كل sessions — diag 7
        $user->tokens()->delete();

        // تسجيل في الأوديت — diag 7
        AuditLog::log(
            action     : 'disabled',
            module     : 'user',
            description: 'Utilisateur désactivé : ' . $user->email,
            model      : $user
        );

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur désactivé');
    }

    // diag 8 : تعديل الرول
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $oldRole = $user->getRoleNames()->first();

        // تغيير الرول — diag 8
        $user->syncRoles($request->role);

        // تسجيل في الأوديت — diag 8
        AuditLog::log(
            action     : 'role_changed',
            module     : 'user',
            description: 'Rôle modifié : ' . $oldRole . ' → ' . $request->role,
            model      : $user,
            oldValues  : ['role' => $oldRole],
            newValues  : ['role' => $request->role]
        );

        return redirect()->route('users.index')
                         ->with('success', 'Rôle mis à jour');
    }

    public function show(User $user)
    {
        $user->load('roles', 'documents');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'department' => 'nullable|string',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->update($request->only('name', 'department'));

        // photo de profil (optionnelle) — remplace l'ancienne si une nouvelle est envoyée
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $user->update(['photo' => $path]);
        }

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur mis à jour');
    }

    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur supprimé');
    }
}