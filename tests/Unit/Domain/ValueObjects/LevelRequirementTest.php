<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class LevelRequirementTest extends TestCase
{
    #[Test]
    public function fails_when_level_is_below_required()
    {
        $levelRequirement = new ValueObjects\LevelRequirement(50);

        $this->assertSame(false, $levelRequirement->pass(
            new Entities\Player(
                20,
                []
            )
        ));
    }

    #[Test]
    public function passes_when_level_exceeds_required()
    {
        $levelRequirement = new ValueObjects\LevelRequirement(50);

        $this->assertSame(true, $levelRequirement->pass(
            new Entities\Player(
                80,
                []
            )
        ));
    }

    #[Test]
    public function passes_when_level_equals_required()
    {
        $levelRequirement = new ValueObjects\LevelRequirement(50);

        $this->assertSame(true, $levelRequirement->pass(
            new Entities\Player(
                50,
                []
            )
        ));
    }
}
