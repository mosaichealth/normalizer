# normalizer
Stable and simple PHP Normalizer


Test : `vendor/bin/phpunit`

### How to implement it

- Create an Entity. example: https://github.com/mosaichealth/normalizer/blob/main/src/Implementation/Entity/TypeLessCollection.php

- Create a DTO with all the necessary information that you wants to put in. example: https://github.com/mosaichealth/normalizer/blob/main/src/Implementation/DTO/CollectionDTO.php

- Create the normalizer. example: https://github.com/mosaichealth/normalizer/blob/main/src/Implementation/Normalizer/CollectionNormalizer.php
- Make sure that your normalizer is implementing the NormalizerInterface. `class CollectionNormalizer implements NormalizerInterface`

### How to use it

- Declare all your Normalizer inside your services.xml
- Declare the Normalizer like this `<service id="MosaicHealth\Normalizer\Normalizer" />`

- Declare the NormalizerInterface like this `<instanceof id="MosaicHealth\Normalizer\NormalizerInterface"><tag name="app.normalizer" /></instanceof>`

- Declare the NormalizerContainer like this `<service id="MosaicHealth\Normalizer\NormalizerContainer" ><argument type="tagged_iterator" tag="app.normalizer"/></service>`

You need to declare the NormalizerInterface like this to get all the Normalizer that implement it. Then you give it as argument to the NormalizeContainer.


## Contribute

### Install

- Start Docker container `docker-compose up -d`

### Tests

- All tests `bin/phpunit`
- Only one file `bin/phpunit tests/Integration/NormalizeTest.php`

### Using xDebug on PHPStorm

Create a server
Name: `[MosaicHealth] Xdebug`
Host: `172.17.0.1`
