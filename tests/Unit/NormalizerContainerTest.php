<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Unit;

use DateTimeImmutable;
use MosaicHealth\Normalizer\Exception\NormalizerNotFoundException;
use MosaicHealth\Normalizer\NormalizerInterface;
use MosaicHealth\Normalizer\NormalizerContainer as SUT;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @author Hugh O'REILLY <hscoreilly@gmail.com>
 * @covers SUT
 */
class NormalizerContainerTest extends TestCase
{
    /**
     * @dataProvider providerIsSupportingEntity
     */
    public function testIsSupportingEntity(array $data, array $expected): void
    {
        // Given
        $sut = new SUT();
        $sut->addNormalizer($data['Normalizer']);

        // When
        $isSupportingEntity = $sut->isSupportingEntity($data['Entity']);

        // Then
        $this->assertEquals($isSupportingEntity, $expected['IsSupportingEntity']);
    }

    public function providerIsSupportingEntity(): array
    {
        return [
            'SupportingEntity' => [
                'Data' =>
                    [
                        'Entity' => new DateTimeImmutable(),
                        'Normalizer' => $this->mockNormalizerInterface('DateTimeImmutable')
                    ],
                'Expected' =>
                    [
                        'IsSupportingEntity' => true
                    ]
            ],
            'NotSupportingEntity' =>
                [
                    'Data' =>
                        [
                            'Entity' => new DateTimeImmutable(),
                            'Normalizer' => $this->mockNormalizerInterface('DateTime')
                        ],
                    'Expected' =>
                        [
                            'IsSupportingEntity' => false
                        ]
                ]
        ];
    }

    /**
    * @dataProvider providerGet
    */
    public function testGet(array $data, array $expected): void
    {
        // Given
        $sut = new SUT();
        foreach ($data['Normalizers'] as $normalizer) {
            $sut->addNormalizer($normalizer);
        }

        // When
        if (true === $expected['ExceptionThrown']) {
            $this->expectException(NormalizerNotFoundException::class);
        }
        $returnedNormalizer = $sut->get($data['className']);

        // Then
        if (true !== $expected['ExceptionThrown']) {
            $this->assertEquals($returnedNormalizer, $expected['ReturnedNormalizer']);
        }
    }

    public function providerGet(): array
    {
        return [
            'NormalizerFound' => [
                'Data' =>
                    [
                        'className' => DateTimeImmutable::class,
                        'Normalizers' =>
                            [
                                $this->mockNormalizerInterface('DateTimeImmutable'),
                                $this->mockNormalizerInterface('DateTime')
                            ]
                    ],
                'Expected' =>
                    [
                        'ReturnedNormalizer' => $this->mockNormalizerInterface('DateTimeImmutable'),
                        'ExceptionThrown' => false
                    ]
            ],
                'NormalizerNotFound' =>
                    [
                        'Data' =>
                            [
                                'className' => DateTimeImmutable::class,
                                'Normalizers' =>
                                    [
                                        $this->mockNormalizerInterface('DateTime'),
                                        $this->mockNormalizerInterface('DatePeriod')
                                    ]
                            ],
                        'Expected' =>
                            [
                                'ExceptionThrown' => true
                            ]
                    ],
            'EmptyNormalizerContainer' =>
                [
                    'Data' =>
                        [
                            'className' => DateTimeImmutable::class,
                            'Normalizers' => []
                        ],
                    'Expected' =>
                        [
                            'ExceptionThrown' => true
                        ]
                ]

        ];

    }

    public function mockNormalizerInterface(string $entityQualifiedName): NormalizerInterface
    {
        $mockNormalizerInterface = $this->createMock(NormalizerInterface::class);

        $mockNormalizerInterface->method('getEntityQualifiedName')->willReturn($entityQualifiedName);

        return $mockNormalizerInterface;
    }
}
