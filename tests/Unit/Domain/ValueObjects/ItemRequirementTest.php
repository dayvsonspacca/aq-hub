<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities, Abstractions};
use AqWiki\Domain\Entities\Weapon;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class ItemRequirementTest extends TestCase
{
    private Abstractions\AqwItem $item;

    protected function setUp(): void
    {
        $this->item = $this->createMock(Weapon::class);
    }

    #[Test]
    public function should_fail_when_player_dont_have_item()
    {
        $itemRequirement = new ValueObjects\ItemRequirement($this->item, 1);

        $this->assertSame(false, $itemRequirement->pass(
            new Entities\Player(
                80,
                []
            )
        ));
    }


    #[Test]
    public function should_pass_when_player_have_item()
    {
        $itemRequirement = new ValueObjects\ItemRequirement($this->item, 1);

        $this->assertSame(true, $itemRequirement->pass(
            new Entities\Player(
                80,
                [$this->item]
            )
        ));
    }
}
