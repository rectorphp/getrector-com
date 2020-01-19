<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Validator;

use Rector\Website\GetRectorKernel;
use Rector\Website\ValueObject\DemoFormData;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class DemoFormDataValidatorTest extends AbstractKernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel(GetRectorKernel::class);

        $this->validator = self::$container->get(ValidatorInterface::class);
    }

    public function test(): void
    {
        $demoFormData = new DemoFormData();
        $this->validator->validate($demoFormData);
    }
}
