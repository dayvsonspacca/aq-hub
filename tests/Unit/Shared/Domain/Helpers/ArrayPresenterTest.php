<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\Helpers;

use AqHub\Shared\Domain\Abstractions\Data;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ArrayPresenterTest extends TestCase
{
    #[Test]
    public function should_convert_data_class_to_array()
    {
        $data = new class extends Data {
            public function toArray(): array
            {
                return [
                    'test' => 'test'
                ];
            }
        };

        $array = ArrayPresenter::presentItem($data);

        $this->assertIsArray($array);
        $this->assertArrayHasKey('test', $array);
        $this->assertSame('test', $array['test']);
    }

    #[Test]
    public function should_convert_array_of_data_class_to_array_of_arrays()
    {
        $data = new class extends Data {
            public function toArray(): array
            {
                return [
                    'test' => 'test'
                ];
            }
        };

        $array = ArrayPresenter::presentCollection([$data, $data]);

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
    }
}