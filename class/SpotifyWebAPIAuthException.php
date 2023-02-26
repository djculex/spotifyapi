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

namespace XoopsModules\Spotifyapi;

// Extends from SpotifyWebApiException for backwards compatibility
class SpotifyWebAPIAuthException extends SpotifyWebAPIException
{
    public const INVALID_CLIENT = 'Invalid client';
    public const INVALID_CLIENT_SECRET = 'Invalid client secret';
    public const INVALID_REFRESH_TOKEN = 'Invalid refresh token';

    /**
     * Returns whether the exception was thrown because of invalid credentials.
     *
     * @return bool
     */
    public function hasInvalidCredentials()
    {
        return in_array($this->getMessage(), [
            self::INVALID_CLIENT,
            self::INVALID_CLIENT_SECRET,
        ]);
    }

    /**
     * Returns whether the exception was thrown because of an invalid refresh token.
     *
     * @return bool
     */
    public function hasInvalidRefreshToken()
    {
        return $this->getMessage() === self::INVALID_REFRESH_TOKEN;
    }
}
