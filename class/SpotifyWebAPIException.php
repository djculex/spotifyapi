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

class SpotifyWebAPIException extends \Exception
{
    public const TOKEN_EXPIRED = 'The access token expired';
    public const RATE_LIMIT_STATUS = 429;

    /**
     * The reason string from the request's error object.
     *
     * @var string
     */
    private $reason;

    /**
     * Returns the reason string from the request's error object.
     *
     * @see https://developer.spotify.com/documentation/web-api/reference/object-model/#player-error-reasons
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set the reason string.
     *
     * @param string $reason
     *
     * @return void
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Returns whether the exception was thrown because of an expired access token.
     *
     * @return bool
     */
    public function hasExpiredToken()
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }

    /**
     * Returns whether the exception was thrown because of rate limiting.
     *
     * @return bool
     */
    public function isRateLimited()
    {
        return $this->getCode() === self::RATE_LIMIT_STATUS;
    }
}
