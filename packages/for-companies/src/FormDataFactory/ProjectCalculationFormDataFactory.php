<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\FormDataFactory;

use Rector\Website\ForCompanies\ValueObject\ProjectCalculationFormData;

final class ProjectCalculationFormDataFactory
{
    public function create(): ProjectCalculationFormData
    {
        // demo data
        return new ProjectCalculationFormData(3, 7_500, 250_000);
    }
}
