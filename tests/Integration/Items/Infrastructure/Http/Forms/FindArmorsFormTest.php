<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Items\Infrastructure\Http\Forms\FindArmorsForm;
use AqHub\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class FindArmorsFormTest extends TestCase
{
    private function createRequest(array $query = []): Request
    {
        return new Request($query);
    }

    #[Test]
    public function should_create_default_filter_if_request_empty()
    {
        $request = $this->createRequest();

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isSuccess());

        $filter = $result->getData();

        $this->assertSame($filter->page, 1);
        $this->assertSame($filter->pageSize, 25);
        $this->assertSame($filter->rarities, []);
        $this->assertSame($filter->tags, []);
        $this->assertSame($filter->name, null);
    }

    #[Test]
    public function should_fail_when_page_param_is_zero_or_negative()
    {
        $request = $this->createRequest(['page' => 0]);

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'Param page cannot be zero or negative.');

        $request = $this->createRequest(['page' => -1]);

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'Param page cannot be zero or negative.');
    }

    #[Test]
    public function should_ignore_invalid_tags_and_filter_by_valid_ones()
    {
        $request = $this->createRequest(['tags' => 'Legend,ac,rar']);

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isSuccess());

        $filter = $result->getData();

        $this->assertSame(array_values($filter->tags), [TagType::Legend, TagType::AdventureCoins]);
        $this->assertNotContains(TagType::Rare, $filter->tags);
    }

    #[Test]
    public function should_ignore_invalid_rarities_and_filter_by_valid_ones()
    {
        $request = $this->createRequest(['rarities' => 'Unknown,rare rarity,legend rarity']);

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isSuccess());

        $filter = $result->getData();

        $this->assertSame(array_values($filter->rarities), [ItemRarity::Unknown, ItemRarity::Rare]);
        $this->assertNotContains(ItemRarity::Legendary, $filter->rarities);
    }

    #[Test]
    public function should_can_filter_by_name()
    {
        $request = $this->createRequest(['name' => 'awe']);

        $result = FindArmorsForm::fromRequest($request);
        $this->assertTrue($result->isSuccess());

        $filter = $result->getData();

        $this->assertEquals($filter->name, Name::create('awe')->unwrap());
    }

    #[Test]
    public function should_fail_when_name_present_in_query_but_invalid()
    {
        $request = $this->createRequest(['name' => '   ']);

        $result = FindArmorsForm::fromRequest($request);

        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'The name of an item cant be empty.');
    }
}
