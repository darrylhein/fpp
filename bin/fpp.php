<?php

declare(strict_types=1);

namespace Fpp;

if (! isset($argv[1])) {
    echo 'Missing input directory or file argument';
    exit(1);
}

if (! isset($argv[2])) {
    echo 'Missing output file argument';
    exit(1);
}

$path = $argv[1];
$output = $argv[2];

if (! is_readable($path)) {
    echo "$path is not readable";
    exit(1);
}

require __DIR__ . '/../vendor/autoload.php';

$scanner = new Scanner($path);
$parser = new Parser();
$collection = new DefinitionCollection();

foreach ($scanner as $file) {
    /* @var \SplFileInfo $file */
    $definition = $parser->parseFile($file->getRealPath());
    $collection = $collection->merge($definition);
}

$dumper = new Dumper();
$php = $dumper->dump($collection);

file_put_contents($output, $php);