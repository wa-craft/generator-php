<?php
declare(strict_types=1);

namespace generator\processor;
use generator\helper\ClassHelper;

final class ProcessorFactory {
    static public function create(ProcessorType $pt): Processor | null
    {
        if( $pt instanceof ProcessorType ) {
            $type_name = strtolower($pt->name);
            $obj = ClassHelper::create("generator\\processor\\".ucfirst($type_name));

            return ($obj instanceof Processor) ? $obj : null;
        }
    }
}