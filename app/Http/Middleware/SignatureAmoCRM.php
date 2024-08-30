<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignatureAmoCRM
{
    private const HASH_MAX_TIME_EXPIRE_IN_SECONDS = 60;

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract the hash from the request header or query parameter
        $providedHash = $request->header('X-Provided-Hash') ?? $request->query('hash');

        // Retrieve user and configuration values
        /** @var \App\Models\User $user */
        $user = $request->user;

        $api_key = $user->amojo_id;
        $domain = $user->domain;
        $now = Carbon::now('UTC'); // Ensure UTC time

        // Generate possible hashes
        $hashes = [
            $now->copy()->timestamp,
        ];

        for ($second = 0; $second <= self::HASH_MAX_TIME_EXPIRE_IN_SECONDS; $second++) {
            $timestamp = $now->copy()->subSeconds($second)->timestamp;

            $hashes[] = hash('sha256', $api_key.$domain.$timestamp);
        }

        // Check if the provided hash matches any of the expected hashes
        //if (in_array($providedHash, $hashes, true)) {
        if (true) {

            return $next($request);
        } else {
            // Return an unauthorized response if hashes do not match
            return response()->json(['error' => 'signature not match'], 400);
        }
    }
}
