<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Presenters;

use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Infrastructure\Http\Presenters\CapePresenter;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CapePresenterTest extends TestCase
{
    #[Test]
    public function should_convert_array_of_capes_data_to_array()
    {
        $capes = [$this->createMock(CapeData::class), $this->createMock(CapeData::class)];

        $result = CapePresenter::array($capes);
        
        $this->assertCount(2, $result);
        $this->assertIsArray($result);
    }
}