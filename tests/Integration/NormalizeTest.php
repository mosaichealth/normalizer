<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Integration;

use MosaicHealth\Normalizer\Implementation\DTO\CollectionDTO;
use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;
use MosaicHealth\Normalizer\Implementation\Entity\TypeLessCollection;
use MosaicHealth\Normalizer\Implementation\Normalizer\CollectionNormalizer;
use MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer;
use MosaicHealth\Normalizer\Normalizer as SUT;
use MosaicHealth\Normalizer\NormalizerContainer;
use PHPUnit\Framework\TestCase;


/**
 * @covers \SUT
 */
class NormalizeTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testNormalizer($entity, NormalizerContainer $container, $expected)
    {
        $normalizer = new SUT($container);

        // When
        $actual = $normalizer->normalize($entity);

        // Then
        $this->assertEquals($expected, $actual);
    }

    public function provider()
    {
        $dateTimeImmutableNormalizer = new DateTimeImmutableNormalizer();
        $collectionNormalizer = new CollectionNormalizer();

        $simpleExpectedDto = new DateTimeImmutableDTO();
        $simpleExpectedDto->dateTime = new \DateTimeImmutable("@1659967822");

        $nestedExpectedDto = new CollectionDTO();
        $nestedExpectedDto->items =new TypeLessCollection([
            new \DateTimeImmutable("@1659967822")
        ]);

        $multipleNestedExpectedDto = new CollectionDTO();
        $multipleNestedExpectedDto->items =new TypeLessCollection([
            new \DateTimeImmutable("@1659967822"),
            new \DateTimeImmutable("@1659967821")
        ]);

        $emptyNestedExpectedDto = new CollectionDTO();
        $emptyNestedExpectedDto->items =new TypeLessCollection([]);

        $container = new NormalizerContainer([$dateTimeImmutableNormalizer, $collectionNormalizer]);
        return [
            'Simple' => [
                new \DateTimeImmutable("@1659967822"),
                $container,
                $simpleExpectedDto
            ],
            'Nested' => [
                new TypeLessCollection([
                    new \DateTimeImmutable("@1659967822")
                ]),
                $container,
                $nestedExpectedDto
            ],
            'Nested with 2 items' => [
                new TypeLessCollection([
                    new \DateTimeImmutable("@1659967822"),
                    new \DateTimeImmutable("@1659967821"),
                ]),
                $container,
                $multipleNestedExpectedDto
            ],
            'Collection without items' => [
                new TypeLessCollection([]),
                $container,
                $emptyNestedExpectedDto
            ],
        ];
    }
}
