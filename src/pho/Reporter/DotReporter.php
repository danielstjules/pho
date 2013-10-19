<?php

namespace pho\Reporter;

use pho\Suite\Suite;
use pho\Runnable\Spec;

class DotReporter extends AbstractReporter implements ReporterInterface
{
    private static $maxPerLine = 60;

    private $lineLength;

    /**
     * Creates a SpecReporter object, used to render a nested view of test
     * suites and specs.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lineLength = 0;
    }

    /**
     * Ran before an individual spec.
     *
     * @param Spec $spec The spec before which to run this method
     */
    public function beforeSpec(Spec $spec)
    {
        if ($this->lineLength == self::$maxPerLine) {
            echo "\n";
        }
    }

    /**
     * Ran after an individual spec.
     *
     * @param Spec $spec The spec after which to run this method
     */
    public function afterSpec(Spec $spec)
    {
        $this->lineLength += 1;

        if (!$spec->passed()) {
            $this->failedSpecs[] = $spec;
            echo 'F';
        } else {
            echo '.';
        }
    }
}
