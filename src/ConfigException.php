<?php
/**
 * Date: 27.05.2020
 * Time: 20:11
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\config;


use Exception;
use kradwhite\language\Lang;
use kradwhite\language\LangException;
use Throwable;

class ConfigException extends Exception
{
    /** @var Lang */
    private static ?Lang $lang = null;

    public function __construct($id, $params = [], Throwable $previous = null)
    {
        parent::__construct($this->getLang()->phrase('exceptions', $id, $params), 0, $previous);
    }

    /**
     * @return Lang
     * @throws LangException
     */
    private function getLang(): Lang
    {
        if (!self::$lang) {
            $rawLocale = (string)getenv('LANG');
            $locale = substr($rawLocale, 0, 2);
            self::$lang = new Lang(require_once __DIR__ . '/../language.php', $locale);
        }
        return self::$lang;
    }
}