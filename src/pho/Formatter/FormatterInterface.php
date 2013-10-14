<?php

namespace pho\Formatter;

use pho;

interface FormatterInterface
{
    public function beforeRun();

    public function afterRun();

    public function beforeSuite(pho\Suite $suite);

    public function afterSuite(pho\Suite $suite);

    public function beforeSpec(pho\Spec $spec);

    public function afterSpec(pho\Spec $spec);
}
