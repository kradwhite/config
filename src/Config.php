<?php
/**
 * Date: 27.05.2020
 * Time: 20:08
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\config;

const DS = DIRECTORY_SEPARATOR;

/**
 * Class Config
 * @package kradwhite\config
 */
class Config
{
    /** @var string */
    private string $source;

    /** @var array */
    private array $templates;

    /** @var string */
    private string $locale = '';

    /**
     * Config constructor.
     * @param string $source
     * @throws ConfigException
     */
    public function __construct(string $source)
    {
        $this->source = $source;
        $this->checkDirectory($source);
        $this->checkDirectory($source . DS . 'templates');
        $this->checkDirectory($source . DS . 'locales');
        $this->templates = $this->loadRecursive($source . DS . 'templates');
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): Config
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @param string $target
     * @param string $locale
     * @return Config
     * @throws ConfigException
     */
    public function build(string $target, string $locale = ''): Config
    {
        $this->checkDirectory($target);
        $this->buildRecursive($target, $this->templates, $this->loadLanguage($locale));
        return $this;
    }

    /**
     * @param string $directory
     * @return array
     * @throws ConfigException
     */
    private function loadRecursive(string $directory): array
    {
        $result = [];
        if (!$files = scandir($directory)) {
            throw new ConfigException('scan-dir-error', $directory);
        }
        $files = array_diff($files, ['.', '..']);
        foreach ($files as $file) {
            $filename = $directory . DS . $file;
            if (is_dir($filename)) {
                $result[$file] = $this->loadRecursive($filename);
            } else {
                $result[$file] = $filename;
            }
        }
        return $result;
    }

    /**
     * @param string $directory
     * @param array $templates
     * @param array $language
     * @return void
     * @throws ConfigException
     */
    private function buildRecursive(string $directory, array $templates, array $language)
    {
        foreach ($templates as $name => $template) {
            $filename = $directory . DS . $name;
            if (!file_exists($filename)) {
                $languageFilename = $this->getLanguageFilename($language, $name);
                if (!$languageFilename) {
                    throw new ConfigException('language-texts-not-found', [$filename]);
                } else if (is_array($template)) {
                    if (!mkdir($filename, 0775)) {
                        throw new ConfigException('directory-create-error', [$filename]);
                    }
                    $this->buildRecursive($filename, $template, $language[$name]);
                } else {
                    $content = sprintf(file_get_contents($template), ...require $languageFilename);
                    if (false === file_put_contents($filename, $content)) {
                        throw new ConfigException('file-create-error', [$filename]);
                    }
                }
            }
        }
    }

    /**
     * @param string $directory
     * @return void
     * @throws ConfigException
     */
    private function checkDirectory(string $directory)
    {
        if (!file_exists($directory)) {
            throw new ConfigException('directory-not-found', [$directory]);
        } else if (!is_dir($directory)) {
            throw new ConfigException('directory-not-directory', [$directory]);
        }
    }

    /**
     * @param string $locale
     * @return array
     * @throws ConfigException
     */
    private function loadLanguage(string $locale): array
    {
        if (!$locale = $locale ? $locale : $this->locale) {
            throw new ConfigException('locale-is-empty');
        }
        $localeDirectory = $this->source . DS . 'locales' . DS . $locale;
        $this->checkDirectory($localeDirectory);
        return $this->loadRecursive($localeDirectory);
    }

    /**
     * @param array $language
     * @param string $name
     * @return string|array
     */
    private function getLanguageFilename(array $language, string $name)
    {
        if (isset($language[$name])) {
            return $language[$name];
        } else if (isset($language[$name . '.php'])) {
            return $language[$name . '.php'];
        } else {
            return '';
        }
    }
}