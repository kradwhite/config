<?php

namespace kradwhite\tests\unit;

use kradwhite\config\Config;
use kradwhite\config\ConfigException;

class ConfigTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInitFailDirNotFound()
    {
        $directory = __DIR__ . '/../_data/sources';
        $this->tester->expectThrowable(new ConfigException('directory-not-found', [$directory]), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitFailDirNotDir()
    {
        $directory = __DIR__ . '/../_data/.gitkeep';
        $this->tester->expectThrowable(new ConfigException('directory-not-directory', [$directory]), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitFailTemplatesNotFound()
    {
        $directory = __DIR__ . '/../_data/wrong1';
        $this->tester->expectThrowable(new ConfigException('directory-not-found', [$directory . '/templates']), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitFailTemplatesNotDir()
    {
        $directory = __DIR__ . '/../_data/wrong2';
        $this->tester->expectThrowable(new ConfigException('directory-not-directory', [$directory . '/templates']), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitFailLocalesNotFound()
    {
        $directory = __DIR__ . '/../_data/wrong3';
        $this->tester->expectThrowable(new ConfigException('directory-not-found', [$directory . '/locales']), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitFailLocalesNotDir()
    {
        $directory = __DIR__ . '/../_data/wrong4';
        $this->tester->expectThrowable(new ConfigException('directory-not-directory', [$directory . '/locales']), function () use ($directory) {
            new Config($directory);
        });
    }

    public function testInitSuccess()
    {
        new Config(__DIR__ . '/../_data/wrong5');
    }

    public function testBuildTargetDirectoryNotFound()
    {
        $directory = __DIR__ . '/../_data/wrong5';
        $target = __DIR__ . '/../_data/wrong6';
        $this->tester->expectThrowable(new ConfigException('directory-not-found', [$target]), function () use ($directory, $target) {
            (new Config($directory))->build($target);
        });
    }

    public function testBuildTargetDirectoryNotDirectory()
    {
        $directory = __DIR__ . '/../_data/wrong5';
        $target = __DIR__ . '/../_data/.gitkeep';
        $this->tester->expectThrowable(new ConfigException('directory-not-directory', [$target]), function () use ($directory, $target) {
            (new Config($directory))->build($target);
        });
    }

    public function testBuildLanguageNotChoose()
    {
        $directory = __DIR__ . '/../_data/wrong5';
        $target = __DIR__ . '/../_data/target';
        $this->tester->expectThrowable(new ConfigException('locale-is-empty', [$target]), function () use ($directory, $target) {
            (new Config($directory))->build($target);
        });
    }

    public function testBuildLanguageTextNotFound()
    {
        $directory = __DIR__ . '/../_data/wrong5';
        $target = __DIR__ . '/../_data/wrong1';
        $this->tester->expectThrowable(new ConfigException('language-texts-not-found', [$target . '/config.php']), function () use ($directory, $target) {
            (new Config($directory))->build($target, 'ru');
        });
    }

    public function testBuildSuccess()
    {
        $directory = __DIR__ . '/../_data/source';
        $target = __DIR__ . '/../_data/target';
        if (file_exists(__DIR__ . '/../_data/target/config.php')) {
            $this->tester->deleteFile(__DIR__ . '/../_data/target/config.php');
        }
        if (file_exists(__DIR__ . '/../_data/target/subtemplates')) {
            $this->tester->deleteDir(__DIR__ . '/../_data/target/subtemplates');
        }
        (new Config($directory))->build($target, 'ru');
    }
}