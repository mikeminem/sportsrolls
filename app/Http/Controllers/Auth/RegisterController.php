<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Roles;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'phone'         => ['required', 'string', 'min:10'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $defaults = Roles::where('is_deleted', 0)->where('is_default', 1)->get();
            if ($defaults->count() !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'No se pudo completar el registro: debe existir un único rol predeterminado. Contacta al administrador.',
                ]);
            }
            $role = $defaults->first();
            $user = User::create([
                'name'      => $data['name'],
                'last_name' => $data['last_name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'],
                'active'    => 1,
                'role'      => 'Player',
                'password'  => Hash::make($data['password']),
            ]);
            $user->roles()->attach($role->id);

            return $user;
        });
    }


}
