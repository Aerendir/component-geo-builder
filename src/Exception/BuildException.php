<?php declare(strict_types=1);

namespace SerendipityHQ\Component\GeoBuilder\Exception;

use Throwable;

/**
 * Contains all exception throwable by BuildCommand.
 */
class BuildException extends \Exception
{
    /**
     * @param string $requestedCountry
     *
     * @return static
     * @throws \Safe\Exceptions\StringsException
     */
    public static function noSourceFileFound(string $requestedCountry): self
    {
        return new self(\Safe\sprintf('Impossible to find the source file for country %s.', $requestedCountry));
    }

    /**
     * {@inheritDoc}
     *
     * Avoid this Exception being initialized without using static methods.
     */
    protected function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
