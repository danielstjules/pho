<?php

namespace pho\Formatter;

use pho;

class CLIFormatter implements FormatterInterface
{
    private $depth;

    public function construct()
    {
        $this->depth = 0;
    }

    public function beforeRun()
    {
        echo "pho by Daniel St. Jules\n\n";
    }

    public function afterRun()
    {
        // TODO: List number of passed tests
    }

    public function beforeSuite(pho\Suite $suite)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$suite->title}\n";

        $this->depth += 1;
    }

    public function afterSuite(pho\Suite $suite)
    {
        $this->depth -= 1;
    }

    public function beforeSpec(pho\Spec $spec)
    {
        $leftPad = str_repeat('    ', $this->depth);
        echo "$leftPad{$spec->title}\n";

        $this->depth += 1;
    }

    public function afterSpec(pho\Spec $spec)
    {
        $this->depth -= 1;
    }
}
