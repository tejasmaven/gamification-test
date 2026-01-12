<?php
$config = require __DIR__ . '/config.php';

$mongoManager = new MongoDB\Driver\Manager($config['mongo_dsn'], []);

function mongo_manager(): MongoDB\Driver\Manager
{
    global $mongoManager;
    return $mongoManager;
}

function mongo_namespace(string $collection): string
{
    $config = require __DIR__ . '/config.php';
    return $config['mongo_db'] . '.' . $collection;
}

function mongo_find(string $collection, array $filter = [], array $options = []): array
{
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = mongo_manager()->executeQuery(mongo_namespace($collection), $query);
    return $cursor->toArray();
}

function mongo_find_one(string $collection, array $filter = [], array $options = []): ?object
{
    $options['limit'] = 1;
    $results = mongo_find($collection, $filter, $options);
    return $results[0] ?? null;
}

function mongo_insert_one(string $collection, array $document): MongoDB\BSON\ObjectId
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $id = $bulk->insert($document);
    mongo_manager()->executeBulkWrite(mongo_namespace($collection), $bulk);
    return $id;
}

function mongo_update_one(string $collection, array $filter, array $update, array $options = []): int
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->update($filter, $update, array_merge(['limit' => 1], $options));
    $result = mongo_manager()->executeBulkWrite(mongo_namespace($collection), $bulk);
    return $result->getModifiedCount();
}

function mongo_delete_one(string $collection, array $filter): int
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->delete($filter, ['limit' => 1]);
    $result = mongo_manager()->executeBulkWrite(mongo_namespace($collection), $bulk);
    return $result->getDeletedCount();
}

function mongo_command(array $commandArray): array
{
    $command = new MongoDB\Driver\Command($commandArray);
    $config = require __DIR__ . '/config.php';
    $cursor = mongo_manager()->executeCommand($config['mongo_db'], $command);
    return $cursor->toArray();
}

function oid(?string $id): MongoDB\BSON\ObjectId
{
    if (!$id) {
        throw new InvalidArgumentException('Missing identifier.');
    }

    if (!preg_match('/^[a-f0-9]{24}$/i', $id)) {
        throw new InvalidArgumentException('Invalid identifier format.');
    }

    return new MongoDB\BSON\ObjectId($id);
}
