<?php
declare(strict_types=1);

namespace MosaicHealth\Tests\Normalizer\Integration;

use MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer;
use MosaicHealth\Normalizer\Implementation\DTO\DateTimeImmutableDTO;
use MosaicHealth\Normalizer\Normalizer;
use MosaicHealth\Normalizer\NormalizerContainer;
use PHPUnit\Framework\TestCase;
use MosaicHealth\Normalizer\Implementation\Normalizer\DateTimeImmutableNormalizer as SUT;

/**
 * @covers SUT
 */
class DateTimeImmutableNormalizerTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testTransform(DateTimeImmutableDTO $dto, \DateTimeImmutable $entity, Normalizer $normalizer, array $options, \DateTimeImmutable $time)
    {
        $actualDto = new SUT();

        $actualDto->transform(
            $dto,
            $entity,
            $normalizer,
            $options
        );

        $this->assertEquals($time->format(\DateTimeInterface::RFC3339), $dto->dateTime);
    }

    public function provider()
    {
        $dateTimeImmutableNormalizer = new DateTimeImmutableNormalizer();
        $container = new NormalizerContainer();
        $container->addNormalizer($dateTimeImmutableNormalizer);

        return [
            'Basic' => [
                new DateTimeImmutableDTO(),
                new \DateTimeImmutable("@1659967822"),
                $this->mockNormalizer($container),
                [],
                new \DateTimeImmutable('2022-08-08T14:10:22+00:00')
            ],
        ];
    }

    private function mockNormalizer(NormalizerContainer $container): Normalizer
    {
        return new Normalizer($container);
    }

}
