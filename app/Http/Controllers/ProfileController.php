<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class ProfileController extends Controller
{
    // diag 4 : عرض الپروفيل
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    // diag 4 : تعديل الپروفيل
    public function update(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $data = ['name' => $request->name];

        // رفع الصورة — diag 4
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')
                            ->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        // تسجيل في الأوديت — diag 4
        AuditLog::log(
            action     : 'updated',
            module     : 'profile',
            description: 'Profil mis à jour',
            model      : $user
        );

        return back()->with('success', 'Profil mis à jour avec succès');
    }
}