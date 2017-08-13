<?php
namespace thinkbuilder\data\driver;

abstract class Driver
{
    abstract function fetch($options = []);
}