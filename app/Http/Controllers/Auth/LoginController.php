<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use function PHPUnit\Framework\exactly;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest', ['except' => 'logout']);
    }
    public function loginview()
    {
        echo "FFF";
    }
    public function login(Request $request)
    {
        //echo "FFF";
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'メールフィールドは必須です。',
                'email.email' => 'メールは有効なメールアドレスである必要があります。',
                'password.required' => 'パスワードフィールドは必須です。',
            ]
        );

        $user = User::where('email', $request["email"])->first();
        // return response()->json($request["email"]);

        if (!isset($user)) {
            return back()->withErrors([
                'message' => 'メールアドレスまたはパスワードが間違っています。',
            ]);
        }

        if (isset($user) && $user->is_permitted == 0) {
            return back()->withErrors([
                'message' => '管理者からのご連絡をお待ちください',
            ]);
        }

        if (isset($user) && $user->is_permitted == 1) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('welcome');
            }
        }

        return back()->withErrors([
            'error' => '提供されたクレデンシャルは、当社の記録と一致しません。',
        ]);
    }

    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */

    public function logout()
    {
        $user = Auth::user();
        Log::info('User Logged Out. ', [$user]);
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
        // return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
