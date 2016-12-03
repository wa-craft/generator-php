<?php
namespace thinkbuilder\generator;


interface IGenerator
{
    public function generate();
    public function writeToFile();
}