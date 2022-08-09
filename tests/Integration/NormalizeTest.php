<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Integration;

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
    public function testNormalizer($entity, NormalizerContainer $container, array $expected)
    {
        $normalizer = new SUT($container);

        // When
        $actual = $normalizer->normalize($entity);

        // Then
        $this->assertEquals($expected, $actual);
    }

    public function provider()
    {
        $container = new NormalizerContainer();

        $dateTimeImmutableNormalizer = new DateTimeImmutableNormalizer();
        $collectionNormalizer = new CollectionNormalizer();

        $container->addNormalizer($dateTimeImmutableNormalizer);
        $container->addNormalizer($collectionNormalizer);
        return [
            'Simple' => [
                new \DateTimeImmutable("@1659967822"),
                $container,
                ['dateTime' => '2022-08-08T14:10:22+00:00']
            ],
            'Nested' => [
                new TypeLessCollection([
                    new \DateTimeImmutable("@1659967822")
                ]),
                $container,
                [
                     "items" => [
                         ['dateTime' => '2022-08-08T14:10:22+00:00']
                     ]
                ]
            ],
            'Nested with 2 items' => [
                new TypeLessCollection([
                    new \DateTimeImmutable("@1659967822"),
                    new \DateTimeImmutable("@1659967821"),
                ]),
                $container,
                [
                    "items" => [
                        ['dateTime' => '2022-08-08T14:10:22+00:00'],
                        ['dateTime' => '2022-08-08T14:10:21+00:00']
                    ]
                ]
            ],
            'Collection without items' => [
                new TypeLessCollection([]),
                $container,
                [
                    "items" => []
                ]
            ],
        ];
    }
}
