<?php

namespace App\Http\Controllers;

use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRegistrationController extends Controller
{
    public function form(Request $request)
    {
        $user = Auth::user();
        $registration = $user->registration;

        return view('registration.form', [
            'registration' => $registration,
            'redirectTo' => $request->query('redirect_to', ''),
        ]);
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'nationality' => ['required', 'string', 'max:80'],
            'gender' => ['required', 'in:Male,Female,Other,Prefer not to say'],
            'contact_number' => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_contact_number' => ['nullable', 'string', 'max:30'],
        ]);

        UserRegistration::updateOrCreate(
            ['user_id' => $user->id],
            $data + ['user_id' => $user->id]
        );

        // Keep users.name in sync with personal info
        $user->name = trim($data['first_name'] . ' ' . $data['last_name']);
        $user->save();

        $redirectTo = $request->input('redirect_to');
        if (is_string($redirectTo) && $redirectTo !== '') {
            return redirect($redirectTo)->with('success', 'Personal information saved.');
        }

        return redirect()->route('enroll')->with('success', 'Personal information saved. You can now enroll.');
    }
}

