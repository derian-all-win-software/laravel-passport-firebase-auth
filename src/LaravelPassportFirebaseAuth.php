<?php

namespace Square1\LaravelPassportFirebaseAuth;

use Firebase\Auth\Token\Exception\InvalidToken;

class LaravelPassportFirebaseAuth
{
    public function getUidFromToken(string $token) : string
    {
        $auth = app('firebase.auth');

        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($token);
        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage(),
            ], 401);
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage(),
            ], 401);
        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        return $verifiedIdToken->getClaim('sub');
    }
}
