<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Unit;

use MosaicHealth\Normalizer\Implementation\DTO\CollectionDTO;
use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;
use MosaicHealth\Normalizer\Normalizer;
use PHPUnit\Framework\TestCase;
use MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer as SUT;

/**
 * @author Hugh O'REILLY <hscoreilly@gmail.com>
 * @covers SUT
 */

class DateTimeImmutableNormalizerTest extends TestCase
{
    /**
     * @dataProvider providerTransform
     */
    public function testTransform(array $data, array $expected)
    {
        // Given
        $sut = new SUT();
        $normalizer = $this->mockNormalizer();
        $actualDTO = $data['dto'];
        $expectedDTO = $expected['dto'];

        if (true === $expected['exceptionThrown']) {
            $this->expectException(\LogicException::class);
            $this->expectExceptionMessage($expected['exceptionMessage']);
        }

        // When
        $sut->transform(
            $actualDTO,
            $data['entity'],
            $normalizer
        );

        // Then
        $this->assertEquals($actualDTO, $expectedDTO);
    }

    public function providerTransform(): array
    {
        $testDateLinuxTimeStamp = '1659967822';
        $testDateRFC3339 = '2022-08-08T14:10:22+00:00';

        $validEntityExpectedDTO = new DateTimeImmutableDTO();
        $validEntityExpectedDTO->dateTime = $testDateRFC3339;

        $invalidDTO = new CollectionDTO();
        $invalidEntity = new \DateTime("@$testDateLinuxTimeStamp");
        return [
            'validInputs' =>
                [
                    'data' =>
                        [
                            'dto' => new DateTimeImmutableDTO(),
                            'entity' => new \DateTimeImmutable("@$testDateLinuxTimeStamp")
                        ],
                    'expected' =>
                        [
                            'dto' => $validEntityExpectedDTO,
                            'exceptionThrown' => false
                        ]
                ],
            'invalidDTO' =>
                [
                    'data' =>
                        [
                            'dto' => $invalidDTO,
                            'entity' => new \DateTimeImmutable("@$testDateLinuxTimeStamp")
                        ],
                    'expected' =>
                        [
                            'dto' => $invalidDTO,
                            'exceptionThrown' => true,
                            'exceptionMessage' => 'Normalizer "MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer" is not accepting DTO "MosaicHealth\Normalizer\Implementation\DTO\CollectionDTO". It accepts "MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO".'
                        ]
                ],
            'invalidEntity' =>
                [
                    'data' =>
                        [
                            'dto' => new DateTimeImmutableDTO(),
                            'entity' => $invalidEntity
                        ],
                    'expected' =>
                        [
                            'dto' => $invalidEntity,
                            'exceptionThrown' => true,
                            'exceptionMessage' => 'Normalizer "MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer" is not accepting Entity "DateTime". It accepts "DateTimeImmutable".'
                        ]
                ]
            ];
    }

    private function mockNormalizer(): Normalizer
    {
        return $this->createStub(Normalizer::class);
    }
}
