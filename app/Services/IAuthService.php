<?php

namespace App\Services;
use App\Exceptions\ErrorException;
use App\Exceptions\RecordExistsException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserResource;
use Illuminate\Http\Request;

interface IAuthService
{
    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return AuthUserResource Returns user data and token on success, false on failure
     * @throws UnauthorizedException
     */
    public function login(LoginRequest $request): AuthUserResource;

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return void
     * @throws RecordExistsException If email already exists and is verified
     */
    public function register(RegisterRequest $request): void;

    /**
     * Verify email with token.
     *
     * @param Request $request
     * @return AuthUserResource
     * @throws ErrorException
     */
    public function verifyEmail(Request $request): AuthUserResource;

}
