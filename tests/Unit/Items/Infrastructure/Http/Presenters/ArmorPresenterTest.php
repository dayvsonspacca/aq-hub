<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Presenters;

use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Infrastructure\Http\Presenters\ArmorPresenter;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ArmorPresenterTest extends TestCase
{
    #[Test]
    public function should_convert_array_of_armor_data_to_array()
    {
        $armors = [$this->createMock(ArmorData::class), $this->createMock(ArmorData::class)];

        $result = ArmorPresenter::array($armors);

        $this->assertCount(2, $result);
        $this->assertIsArray($result);
    }
}
