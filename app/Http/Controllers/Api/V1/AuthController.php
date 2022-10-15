<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $service)
    {}

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Retrieving Bearer Token",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"email": "email@mail.com", "password": "12345679"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="token",
     *                         type="string",
     *                         description="Bearer auth token"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="unauth message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (! \Auth::attempt($credentials)) {
            return new JsonResponse([
                'message' => __('auth.incorrect_credentials'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::whereEmail($request->email)
            ->first();

        return new JsonResponse([
            'token' => $user->createToken('api_key')->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logging out current user",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="logout message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        session()->flush();

        return new JsonResponse([
            'message' => __('auth.logout'),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="User Data",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                     ),@OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                     ),@OA\Property(
     *                         property="company",
     *                         type="object",
     *                     ),@OA\Property(
     *                         property="role",
     *                         type="string",
     *                     ),
     *                     example={
     *                         "id": 1,
     *                         "first_name": "Khalid",
     *                         "last_name": "Schinner",
     *                         "company": {
     *                             "id": 2,
     *                             "name": "Anderson Group",
     *                         },
     *                         "role": "admin"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Send Password Reset Link",
     *     tags={"Password Resetting"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 example={"email": "email@mail.com"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function sendPasswordReset(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? new JsonResponse(['message' => __('auth.email.sent')])
            : new JsonResponse(['message' => __('auth.email.send.error')], 500);
    }

    /**
     * @OA\Get(
     *     path="/reset-password/{token}",
     *     summary="Check if token exists",
     *     tags={"Password Resetting"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),@OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="password reset token"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not exists",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),@OA\Response(
     *         response=200,
     *         description="Token exists",
     *     ),
     * )
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = $this->service->getUser($request->email);

        if (! $this->service->checkResetToken($user, $request->token)) {
            return new JsonResponse([
                'message' => __('auth.not.exists')
            ], 401);
        }

        return new JsonResponse(null);
    }

    /**
     * @OA\Post(
     *     path="/reset-password",
     *     summary="Handles password resetting",
     *     tags={"Password Resetting"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),@OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="confirmation token from email"
     *                 ),@OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),@OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="token",
     *                         type="string",
     *                         description="auth bearer token"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),@OA\Response(
     *         response=401,
     *         description="User or Token not found",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="error message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function changePassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->service->getUser($request->email);

        if (!$this->service->checkResetToken($user, $request->token)) {
            return new JsonResponse([
                'message' => __('auth.not.exists')
            ], 401);
        }

        Password::deleteToken($user);

        $user->password = $request->password;
        $user->save();
        $token = $user->createToken('api_token')->plainTextToken;

        return new JsonResponse([
            'token' => $token,
        ]);
    }
}
