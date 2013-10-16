<?php

namespace pho\Reporter;

use pho\Suite\Suite;
use pho\Runnable\Spec;

interface ReporterInterface
{
    public function beforeRun();

    public function afterRun();

    public function beforeSuite(Suite $suite);

    public function afterSuite(Suite $suite);

    public function beforeSpec(Spec $spec);

    public function afterSpec(Spec $spec);
}
