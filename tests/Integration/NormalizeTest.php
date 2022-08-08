<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Integration;

use MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer;
use MosaicHealth\Normalizer\Normalizer as SUT;
use MosaicHealth\Normalizer\NormalizerContainer;
use PHPUnit\Framework\TestCase;


/**
 * @covers \SUT
 */
class NormalizeTest extends TestCase
{
    public function testNormalizer()
    {
        // Given
        $subNormalizer1 = new DateTimeImmutableNormalizer();
        $container = new NormalizerContainer(
            [
                \DateTimeImmutable::class => [
                    '*' => DateTimeImmutableNormalizer::class
                ]
            ]
        );

        $container->addNormalizer($subNormalizer1, DateTimeImmutableNormalizer::class);

        $normalizer = new SUT($container);

        $entity = new \DateTimeImmutable("@1659967822");

        // When
        $actual = $normalizer->normalize($entity);
        $expected = ['dateTime' => '2022-08-08T14:10:22+00:00'];

        // Then
        $this->assertEquals($expected, $actual);
    }
}
