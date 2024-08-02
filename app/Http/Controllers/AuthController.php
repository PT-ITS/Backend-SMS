<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DateTimeZone;
use DateTime;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Http\Controllers\EmailController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'import']]);
    }

    public function listUser()
    {
        $data = User::all();
        if ($data) {
            return response()->json([
                'message' => 'success',
                'data' => $data
            ]);
        }
        return response()->json([
            'message' => 'failed',
            'data' => null
        ]);
    }

    public function userById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $user
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imagePath = $image->storeAs('public/profile', $image->hashName());
            $dataGambar =  $image->hashName();

            Storage::delete($user->gambar);
        } else {
            return response()->json(['message' => 'Harap inputkan gambar!!'], 400);
        }

        $user->name = $request->input('name');
        $user->gambar = $dataGambar;
        $user->nik = $request->input('nik');
        $user->telp = $request->input('telp');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $user
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt(request('password')),
        ]);

        if ($user) {
            // $this->emailController->index($user->id);
            Mail::to($user->email)->send(new SendEmail($user->id));
            // Mail::to($user->email)->send(new EmailVerification($user));
            return response()->json(['message' => 'Registration successful'], 201);
        } else {
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    // fixed
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', request('email'))->first();

        // Menambahkan keterangan khusus langsung ke token yang dihasilkan
        $customClaims = [
            'id' => $user->id,
            'name' => $user->name,
            'level' => $user->level,
        ];

        $tokenWithClaims = JWTAuth::claims($customClaims)->fromUser($user);

        return $this->respondWithToken($tokenWithClaims, $user);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'sub' => $user->id,
            'name' => $user->name,
            'level' => $user->level,
            'iat' => now()->timestamp,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function listPengguna()
    {
        try {
            $result = User::get();
            return response()->json(
                [
                    'message' => 'data pengguna ditemukan',
                    'data' => $result
                ],
                200
            );
        } catch (\Exception  $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 401);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt(request('password')),
        ]);

        if ($user) {
            return response()->json(['message' => 'Registration successful'], 201);
        } else {
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save();

        return response()->json([
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    public function delete($id)
    {
        $data = User::find($id);
        if ($data) {
            $data->delete();
            return response()->json([
                'message' => 'success',

            ], 200);
        }
        return response()->json([
            'message' => 'data not found'
        ], 404);
    }
}
