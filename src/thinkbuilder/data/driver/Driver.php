<?php
namespace generator\data\driver;

abstract class Driver
{
    abstract function fetch($options = []);
}