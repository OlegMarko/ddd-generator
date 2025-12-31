<?php

namespace Fixik\DddGenerator\Support;

class NamespaceResolver
{
    public static function base(): string
    {
        return config('ddd.base_namespace', 'App\\Modules');
    }

    public static function module(string $module): string
    {
        return self::base() . "\\$module";
    }

    public static function entity(string $module, string $entity): string
    {
        return self::module($module) . "\\Domain\\Entities\\$entity";
    }

    public static function valueObject(string $module, string $valueObject): string
    {
        return self::module($module) . "\\Domain\\ValueObjects\\$valueObject";
    }

    public static function event(string $module, string $event): string
    {
        return self::module($module) . "\\Domain\\Events\\$event";
    }

    public static function repository(string $module, string $repository): string
    {
        return self::module($module) . "\\Domain\\Repositories\\$repository";
    }

    public static function repositoryImplementation(string $module, string $implementation): string
    {
        return self::module($module) . "\\Infrastructure\\Persistence\\Repositories\\$implementation";
    }

    public static function model(string $module, string $model): string
    {
        return self::module($module) . "\\Infrastructure\\Persistence\\Models\\$model";
    }

    public static function mapper(string $module, string $mapper): string
    {
        return self::module($module) . "\\Infrastructure\\Persistence\\Mappers\\$mapper";
    }

    public static function useCase(string $module, string $useCase): string
    {
        return self::module($module) . "\\Application\\UseCases\\$useCase";
    }

    public static function dto(string $module, string $dto): string
    {
        return self::module($module) . "\\Application\\DTO\\$dto";
    }

    public static function listener(string $module, string $listener): string
    {
        return self::module($module) . "\\Application\\Listeners\\$listener";
    }

    public static function command(string $module, string $command): string
    {
        return self::module($module) . "\\Application\\Commands\\$command";
    }

    public static function commandHandler(string $module, string $handler): string
    {
        return self::module($module) . "\\Application\\CommandHandlers\\$handler";
    }

    public static function query(string $module, string $query): string
    {
        return self::module($module) . "\\Application\\Queries\\$query";
    }

    public static function queryHandler(string $module, string $handler): string
    {
        return self::module($module) . "\\Application\\QueryHandlers\\$handler";
    }

    public static function serviceProvider(string $module): string
    {
        return self::module($module) . "\\{$module}ServiceProvider";
    }
}

