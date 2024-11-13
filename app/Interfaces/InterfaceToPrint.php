<?php

namespace App\Interfaces;

use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface InterfaceToPrint
{
    public function generate(): View;
    public function download();
}
