<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request via AJAX.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validate the request data
            $credentials = $this->authService->validateLoginData($request->all());

            // Attempt to login
            $result = $this->authService->login($credentials);

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect_url' => $result['redirect_url'],
                'user' => [
                    'name' => $result['user']->full_name,
                    'email' => $result['user']->email,
                    'role' => $result['user']->role,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle logout request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Logout user
            auth()->logout();

            // Clear session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to login page
            return redirect()->route('login')->with('success', 'Logged out successfully');

        } catch (\Exception $e) {

            // Redirect back with normal error message
            return redirect()->back()->with('error', 'Something went wrong during logout.');
        }
    }

    /**
     * Check authentication status.
     *
     * @return JsonResponse
     */
    public function checkAuth(): JsonResponse
    {
        if (auth()->check()) {
            $user = auth()->user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);
        }

        return response()->json([
            'authenticated' => false
        ], 200);
    }
}
