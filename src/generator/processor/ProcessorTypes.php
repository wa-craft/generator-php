<?php

declare(strict_types=1);

namespace generator\processor;

enum ProcessorTypes
{
    case Backend;
    case Frontend;
    case Commandline;
    case Document;
    case Operation;
    case Schema;
}
