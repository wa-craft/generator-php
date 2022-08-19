<?php

declare(strict_types=1);

namespace generator\resource;

enum ResourceType
{
    case Backend;
    case Frontend;
    case Commandline;
    case Document;
    case Operation;
    case Migration;
}
