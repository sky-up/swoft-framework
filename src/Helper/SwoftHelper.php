<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Helper;

use RuntimeException;
use function extension_loaded;
use function implode;
use function version_compare;
use const PHP_VERSION;
use const SWOOLE_VERSION;

/**
 * Class SwoftHelper
 *
 * @since 2.0
 */
class SwoftHelper
{
    /**
     * @param array $stats
     *
     * @return string
     */
    public static function formatStats(array $stats): string
    {
        $strings = [];

        foreach ($stats as $name => $count) {
            $strings[] = "$name $count";
        }

        return implode(', ', $strings);
    }

    /**
     * Check runtime extension conflict
     *
     * @param string $minPhp
     * @param string $minSwoole
     */
    public static function checkRuntime(string $minPhp = '8.1.0', string $minSwoole = '6.0.0'): void
    {
        if (version_compare(PHP_VERSION, $minPhp, '<')) {
            throw new RuntimeException('Run the server requires PHP version >= ' . $minPhp . '! Current is ' . PHP_VERSION);
        }

        if (!extension_loaded('swoole')) {
            throw new RuntimeException("Run the server, extension 'swoole' is required!");
        }

        if (version_compare(SWOOLE_VERSION, $minSwoole, '<')) {
            throw new RuntimeException('Run the server requires swoole version >= ' . $minSwoole . '! Current is ' . SWOOLE_VERSION);
        }

        $conflicts = [
            'blackfire',
            'xdebug',
            'uopz',
            'xhprof',
            'zend',
            'trace',
        ];

        foreach ($conflicts as $ext) {
            if (extension_loaded($ext)) {
                throw new RuntimeException("The extension of '{$ext}' must be closed, otherwise swoft will be affected!");
            }
        }
    }
}
