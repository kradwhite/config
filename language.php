<?php

/**
 * Допускается одновременное использование нескольких хранилищ
 * Значения в массивах 'names' должны быть уникальными на уровне приложения
 */

return [
    // допустимые языки, первое значение будет значением по умолчанию
    'locales' => ['ru', 'en'],

    // имя набора фраз по умолчанию
    //'default' => 'errors',

    // допустимо создать свою фабрику, унаследовав \kradwhite\language\text\TextFactory
    //'factory' => \kradwhite\language\text\TextFactory::class,

    // хранилища языков
    'texts' => [
        /* конфигурация для хранения фраз в файлах */
        [
            // тип хранилища для языка
            'type' => 'php',

            // имена набора фраз, в файловом хранилище имена файлов
            'names' => ['exceptions'],

            // путь к директории, которая будет хранить файлы в фразами
            'directory' => __DIR__ . DIRECTORY_SEPARATOR . 'language'
        ],

        // конфигурация для хранения фраз в базе данных
        /*
        [
            // тип хранилища для языка
            'type' => 'database',

            // имена набора фраз, в бд хранилище значение в колонке name
            'names' => ['messages'],

            // имя таблицы хранящей фразы
            'table' => 'kw_language',

            // ограничение длинны фразы в бд
            'textLimit' => 256,

            // ограничение длинны строки параметров в бд
            'paramsLimit' => 256,

            // имена колонок
            'columns => [
                'locale' => 'locale',
                'name' => 'name',
                'id' => 'id',
                'text' => 'text',
                'params' => 'params'
            ],

            // допустимо создать свой репозиторий, реализовав интерфейс \kradwhite\language\text\TextRepository
            'repository' => \kradwhite\language\text\SqlTextRepository::class,

            // конфигурация передаётся в конструктор репозитория
            'connection' => [
                'driver' => 'pgsql',                                // или 'mysql'
                'host' => 'localhost',
                'user' => 'admin',
                'password' => 'admin',
                'dbName' => 'test',
                'port' => '5432'
            ]
        ]
        */
    ]
];