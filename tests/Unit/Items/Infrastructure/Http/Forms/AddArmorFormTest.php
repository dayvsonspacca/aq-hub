<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Items\Infrastructure\Http\Forms\AddArmorForm;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\DataProviders\ArmorDataProvider;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;

final class AddArmorFormTest extends TestCase
{
    use DoRequests;

    #[Test]
    public function should_result_success_and_create_item_info()
    {
        $armor = (new ArmorDataProvider())->build();

        $request = $this->makeRequest(
            method: 'POST',
            uri: '/armors/add',
            content: [
                'name' => $armor->name->value,
                'description' => $armor->description->value,
                'rarity' => $armor->rarity->toString(),
                'tags' => [
                    'ac',
                    'legend',
                    'megalegend' // should ignore invalid tag
                ]
            ]
        );

        $result = AddArmorForm::fromRequest($request);
        
        $this->assertTrue($result->isSuccess());

        $itemInfo = $result->getData();

        $this->assertSame($itemInfo->getName(), $armor->name->value);
        $this->assertSame($itemInfo->getDescription(), $armor->description->value);
        $this->assertSame($itemInfo->getRarity(), $armor->rarity);
        $this->assertEquals($itemInfo->tags, new ItemTags([ItemTag::AdventureCoins, ItemTag::Legend]));
    }
}