<?php


namespace App\Helpers;


use Exception;
use Firebase\JWT\JWT;

class JwtHelper {
    const AUTH_TOKEN_KEY = "VerySecretKeyForJwtToken213983908345493852-23948329842";
    const SESSION_TIME = 3600;

    public static function generateUserToken($username) {
        return JWT::encode(self::buildTokenPayload($username), self::AUTH_TOKEN_KEY);
    }

    public static function verifyToken($token) {
        try {
            JWT::decode($token, self::AUTH_TOKEN_KEY, array('HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    private static function buildTokenPayload($username) {
        $issuedAt = time();

        return array(
            "sub" => $username,
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" => $issuedAt,
            "exp" => $issuedAt + self::SESSION_TIME
        );
    }
}