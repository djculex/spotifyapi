<?php

/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2023 Michael Albertsen (www.culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

declare(strict_types=1);

namespace XoopsModules\Spotifyapi;

use Exception;

/**
 *
 */
class Session
{
    protected string $accessToken = '';
    protected string $clientId = '';
    protected string $clientSecret = '';
    protected int $expirationTime = 0;
    protected string $redirectUri = '';
    protected string $refreshToken = '';
    protected string $scope = '';
    protected $request = null;

    /**
     * Constructor
     * Set up client credentials.
     *
     * @param string $clientId The client ID.
     * @param string $clientSecret Optional. The client secret.
     * @param string $redirectUri Optional. The redirect URI.
     * @param Request $request Optional. The Request object to use.
     */
    public function __construct($clientId, $clientSecret = '', $redirectUri = '', $request = null)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setRedirectUri($redirectUri);

        $this->request = $request ?? new Request();
    }

    /**
     * Generate a code challenge from a code verifier for use with the PKCE flow.
     *
     * @param string $codeVerifier The code verifier to create a challenge from.
     * @param string $hashAlgo Optional. The hash algorithm to use. Defaults to "sha256".
     *
     * @return string The code challenge.
     */
    public function generateCodeChallenge($codeVerifier, $hashAlgo = 'sha256')
    {
        $challenge = hash($hashAlgo, $codeVerifier, true);
        $challenge = base64_encode($challenge);
        $challenge = strtr($challenge, '+/', '-_');
        $challenge = rtrim($challenge, '=');

        return $challenge;
    }

    /**
     * Generate a code verifier for use with the PKCE flow.
     *
     * @param int $length Optional. Code verifier length. Must be between 43 and 128 characters long, default is 128.
     *
     * @return string A code verifier string.
     */
    public function generateCodeVerifier($length = 128)
    {
        return $this->generateState($length);
    }

    /**
     * Generate a random state value.
     *
     * @param int $length Optional. Length of the state. Default is 16 characters.
     *
     * @return string A random state value.
     * @throws Exception
     */
    public function generateState($length = 16): string
    {
        // Length will be doubled when converting to hex
        return bin2hex(
            random_bytes($length / 2)
        );
    }

    /**
     * Get the authorization URL.
     *
     * @param array|object $options Optional. Options for the authorization URL.
     * - string code_challenge Optional. A PKCE code challenge.
     * - array scope Optional. Scope(s) to request from the user.
     * - boolean show_dialog Optional. Whether or not to force the user to always approve the app. Default is false.
     * - string state Optional. A CSRF token.
     *
     * @return string The authorization URL.
     */
    public function getAuthorizeUrl($options = []): string
    {
        $options = (array) $options;

        $parameters = [
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope' => isset($options['scope']) ? implode(' ', $options['scope']) : null,
            'show_dialog' => !empty($options['show_dialog']) ? 'true' : null,
            'state' => $options['state'] ?? null,
        ];

        // Set some extra parameters for PKCE flows
        if (isset($options['code_challenge'])) {
            $parameters['code_challenge'] = $options['code_challenge'];
            $parameters['code_challenge_method'] = $options['code_challenge_method'] ?? 'S256';
        }

        return Request::ACCOUNT_URL . '/authorize?' . http_build_query($parameters, '', '&');
    }

    /**
     * Get the access token.
     *
     * @return string The access token.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Get the client ID.
     *
     * @return string The client ID.
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Get the client secret.
     *
     * @return string The client secret.
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * Get the access token expiration time.
     *
     * @return int A Unix timestamp indicating the token expiration time.
     */
    public function getTokenExpiration(): int
    {
        return $this->expirationTime;
    }

    /**
     * Get the client's redirect URI.
     *
     * @return string The redirect URI.
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * Get the refresh token.
     *
     * @return string The refresh token.
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Get the scope for the current access token
     *
     * @return array The scope for the current access token
     */
    public function getScope(): array
    {
        return explode(' ', $this->scope);
    }

    /**
     * Refresh an access token.
     *
     * @param null|string $refreshToken Optional. The refresh token to use.
     *
     * @return bool Whether the access token was successfully refreshed.
     * @throws SpotifyWebAPIAuthException
     * @throws SpotifyWebAPIException
     */
    public function refreshAccessToken($refreshToken = null): bool
    {
        $parameters = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken ?? $this->refreshToken,
        ];

        $headers = [];
        if ($this->getClientSecret()) {
            $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

            $headers = [
                'Authorization' => 'Basic ' . $payload,
            ];
        }

        $response = $this->request->account('POST', '/api/token', $parameters, $headers);
        $response = $response['body'];

        if (isset($response->access_token)) {
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            if (isset($response->refresh_token)) {
                $this->refreshToken = $response->refresh_token;
            } elseif (empty($this->refreshToken)) {
                $this->refreshToken = $refreshToken;
            }

            return true;
        }

        return false;
    }

    /**
     * Request an access token given an authorization code.
     *
     * @param string $authorizationCode The authorization code from Spotify.
     * @param string $codeVerifier Optional. A previously generated code verifier. Will assume a PKCE flow if passed.
     *
     * @return bool True when the access token was successfully granted, false otherwise.
     * @throws SpotifyWebAPIAuthException
     * @throws SpotifyWebAPIException
     */
    public function requestAccessToken($authorizationCode, $codeVerifier = ''): bool
    {
        $parameters = [
            'client_id' => $this->getClientId(),
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
        ];

        // Send a code verifier when PKCE, client secret otherwise
        if ($codeVerifier) {
            $parameters['code_verifier'] = $codeVerifier;
        } else {
            $parameters['client_secret'] = $this->getClientSecret();
        }

        $response = $this->request->account('POST', '/api/token', $parameters, []);
        $response = $response['body'];

        if (isset($response->refresh_token) && isset($response->access_token)) {
            $this->refreshToken = $response->refresh_token;
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            return true;
        }

        return false;
    }

    /**
     * Request an access token using the Client Credentials Flow.
     *
     * @return bool True when an access token was successfully granted, false otherwise.
     * @throws SpotifyWebAPIAuthException
     * @throws SpotifyWebAPIException
     */
    public function requestCredentialsToken(): bool
    {
        $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

        $parameters = [
            'grant_type' => 'client_credentials',
        ];

        $headers = [
            'Authorization' => 'Basic ' . $payload,
        ];

        $response = $this->request->account('POST', '/api/token', $parameters, $headers);
        $response = $response['body'];

        if (isset($response->access_token)) {
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            return true;
        }

        return false;
    }

    /**
     * Set the access token.
     *
     * @param string $accessToken The access token
     *
     * @return void
     */
    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Set the client ID.
     *
     * @param string $clientId The client ID.
     *
     * @return void
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Set the client secret.
     *
     * @param string $clientSecret The client secret.
     *
     * @return void
     */
    public function setClientSecret($clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Set the client's redirect URI.
     *
     * @param string $redirectUri The redirect URI.
     *
     * @return void
     */
    public function setRedirectUri($redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Set the session's refresh token.
     *
     * @param string $refreshToken The refresh token.
     *
     * @return void
     */
    public function setRefreshToken($refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
