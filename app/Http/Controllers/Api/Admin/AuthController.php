<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /** POST /api/admin/login { username, password } → { token, user } */
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $data['username'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Pogrešno korisničko ime ili lozinka.'], 401);
        }

        $token = $user->createToken('admin')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    /** POST /api/admin/logout — revoke the current token. */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['ok' => true]);
    }

    /** GET /api/admin/me */
    public function me(Request $request)
    {
        return response()->json($this->userPayload($request->user()));
    }

    /**
     * PUT /api/admin/credentials — change username and/or password.
     * Requires the current password for confirmation.
     */
    public function credentials(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'username' => ['sometimes', 'required', 'string', 'min:3', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return response()->json(['message' => 'Trenutna lozinka nije tačna.'], 422);
        }

        if (! empty($data['username'])) {
            $user->username = $data['username'];
        }
        if (! empty($data['password'])) {
            $user->password = $data['password']; // hashed via cast
        }
        $user->save();

        return response()->json([
            'ok' => true,
            'user' => $this->userPayload($user),
        ]);
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
