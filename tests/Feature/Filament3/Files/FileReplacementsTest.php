<?php

use Generators\Filament3\Generators\Files\FileReplacements;
use Generators\Filament3\Modules\BaseModule;

$crudExamples = function () {

    $list = [];

    $baseCrud = (new BaseModule())->getCruds()[1];
    unset($baseCrud->parent_id);

    $list[] = [$baseCrud];

    $productCrud = (new BaseModule())->getCruds()[1];
    $productCrud->title = 'Product';
    $productCrud->visual_title = 'Products';
    unset($productCrud->parent_id);
    $list[] = [$productCrud];

    $productCrud = (new BaseModule())->getCruds()[1];
    $productCrud->title = 'Data';
    $productCrud->visual_title = 'Database Data';
    unset($productCrud->parent_id);
    $list[] = [$productCrud];

    $productCrud = (new BaseModule())->getCruds()[1];
    $productCrud->title = 'Asset Category';
    $productCrud->visual_title = 'Asset Category';
    unset($productCrud->parent_id);
    $list[] = [$productCrud];

    return $list;

};

// TODO: See pest for broken code
// https://github.com/pestphp/pest/issues/978

//
//test('replacements are correct for list file', function ($baseCrud) {
//
//    $replacementsService = new FileReplacements($baseCrud);
//    $createReplacements = $replacementsService->getReplacementsForListPage();
//
//    $className = str($baseCrud->title)->singular()->studly();
//    $listName = str($baseCrud->title)->plural()->studly();
//    $singularName = str($baseCrud->title)->singular()->studly();
//
//    $expected = [
//        'baseResourcePage' => "Filament\Resources\Pages\ListRecords",
//        'baseResourcePageClass' => 'ListRecords',
//        'namespace' => "App\Filament\Resources\\".$singularName."Resource\Pages",
//        'resourceClass' => $className.'Resource',
//        'resourcePageClass' => 'List'.$listName,
//        'resource' => "App\Filament\Resources\\".$singularName.'Resource',
//    ];
//
//    expect($createReplacements)->toEqual($expected);
//
//})->with($crudExamples);
//
//test('replacements are correct for edit file', function ($baseCrud) {
//
//    $replacementsService = new FileReplacements($baseCrud);
//    $createReplacements = $replacementsService->getReplacementsForEditPage();
//
//    $singularName = str($baseCrud->title)->singular()->studly();
//    $namespace = str($baseCrud->title)->singular()->studly();
//
//    $editPageActions = [];
//    $editPageActions[] = '            Actions\DeleteAction::make(),';
//    $editPageActions[] = '            Actions\ForceDeleteAction::make(),';
//    $editPageActions[] = '            Actions\RestoreAction::make(),';
//    $editPageActions = implode(PHP_EOL, $editPageActions);
//
//    $expected = [
//        'baseResourcePage' => 'Filament\\Resources\\Pages\\EditRecord',
//        'baseResourcePageClass' => 'EditRecord',
//        'namespace' => 'App\\Filament\\Resources\\'.$namespace.'Resource\\Pages',
//        'resourceClass' => $singularName.'Resource',
//        'resourcePageClass' => 'Edit'.$singularName,
//        'actions' => $editPageActions,
//        'resource' => 'App\\Filament\\Resources\\'.$singularName.'Resource',
//    ];
//
//    expect($createReplacements)->toEqual($expected);
//
//})->with($crudExamples);
//
//test('replacements are correct for create file', function ($baseCrud) {
//
//    $replacementsService = new FileReplacements($baseCrud);
//    $createReplacements = $replacementsService->getReplacementsForCreatePage();
//
//    $singularName = str($baseCrud->title)->singular()->studly();
//    $namespace = str($baseCrud->title)->singular()->studly();
//    $expected = [
//        'baseResourcePage' => 'Filament\\Resources\\Pages\\CreateRecord',
//        'baseResourcePageClass' => 'CreateRecord',
//        'namespace' => 'App\\Filament\\Resources\\'.$namespace.'Resource\\Pages',
//        'resourceClass' => $singularName.'Resource',
//        'resourcePageClass' => 'Create'.$singularName,
//        'resource' => 'App\\Filament\\Resources\\'.$singularName.'Resource',
//    ];
//
//    expect($createReplacements)->toEqual($expected);
//
//})->with($crudExamples);
//
//test('replacements are correct for resource file', function ($baseCrud) {
//    $replacementsService = new FileReplacements($baseCrud);
//    $createReplacements = $replacementsService->getReplacementsForResource();
//
//    $singularName = str($baseCrud->title)->singular()->studly();
//
//    $expected = [
//        'namespace' => 'App\Filament\Resources',
//        'resourceClass' => $singularName.'Resource',
//        'resource' => 'App\Filament\Resources\\'.$singularName.'Resource',
//        'model' => $singularName,
//        'modelClass' => $singularName,
//        'navigationGroup' => '',
//    ];
//
//    // TODO: Think about a way to test ALL replacements.
//    // For now, we skipped the forms and so on... :D
//
//    expect($createReplacements)->toMatchArray($expected);
//
//})->with($crudExamples);
