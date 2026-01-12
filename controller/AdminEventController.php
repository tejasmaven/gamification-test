<?php
require_once __DIR__ . '/../config/mongodb.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminEventController
{
    public function typesList(): void
    {
        $types = mongo_find('gamification_event_types', [], ['sort' => ['createdAt' => -1]]);
        render('admin/events/types_list', ['types' => $types], 'admin');
    }

    public function typesAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventKey = sanitize($_POST['eventKey'] ?? '');
            $name = sanitize($_POST['name'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $isActive = !empty($_POST['isActive']);
            $errors = $this->validateEventType($eventKey, $name);

            if (empty($errors)) {
                $existing = mongo_find_one('gamification_event_types', ['eventKey' => $eventKey]);
                if ($existing) {
                    $errors[] = 'Event key already exists.';
                }
            }

            if (empty($errors)) {
                $now = new MongoDB\BSON\UTCDateTime();
                mongo_insert_one('gamification_event_types', [
                    'eventKey' => $eventKey,
                    'name' => $name,
                    'description' => $description ?: null,
                    'isActive' => $isActive,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ]);
                set_flash('success', 'Event type created.');
                redirect('/admin/events/types');
            }

            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }
        render('admin/events/types_add', [], 'admin');
    }

    public function typesEdit(): void
    {
        $id = $_GET['id'] ?? '';
        try {
            $type = mongo_find_one('gamification_event_types', ['_id' => oid($id)]);
        } catch (InvalidArgumentException $e) {
            $type = null;
        }

        if (!$type) {
            set_flash('error', 'Event type not found.');
            redirect('/admin/events/types');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventKey = sanitize($_POST['eventKey'] ?? '');
            $name = sanitize($_POST['name'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $isActive = !empty($_POST['isActive']);
            $errors = $this->validateEventType($eventKey, $name);

            if (empty($errors)) {
                $existing = mongo_find_one('gamification_event_types', [
                    'eventKey' => $eventKey,
                    '_id' => ['$ne' => $type->_id],
                ]);
                if ($existing) {
                    $errors[] = 'Event key already exists.';
                }
            }

            if (empty($errors)) {
                mongo_update_one('gamification_event_types', ['_id' => $type->_id], [
                    '$set' => [
                        'eventKey' => $eventKey,
                        'name' => $name,
                        'description' => $description ?: null,
                        'isActive' => $isActive,
                        'updatedAt' => new MongoDB\BSON\UTCDateTime(),
                    ],
                ]);
                set_flash('success', 'Event type updated.');
                redirect('/admin/events/types');
            }

            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }

        render('admin/events/types_edit', ['type' => $type], 'admin');
    }

    public function versionsList(): void
    {
        $eventTypeId = $_GET['eventTypeId'] ?? '';
        try {
            $type = mongo_find_one('gamification_event_types', ['_id' => oid($eventTypeId)]);
        } catch (InvalidArgumentException $e) {
            $type = null;
        }

        if (!$type) {
            set_flash('error', 'Event type not found.');
            redirect('/admin/events/types');
        }

        $versions = mongo_find('gamification_event_versions', ['eventTypeId' => $type->_id], ['sort' => ['createdAt' => -1]]);
        render('admin/events/versions_list', ['versions' => $versions, 'type' => $type], 'admin');
    }

    public function versionsAdd(): void
    {
        $eventTypeId = $_GET['eventTypeId'] ?? '';
        try {
            $type = mongo_find_one('gamification_event_types', ['_id' => oid($eventTypeId)]);
        } catch (InvalidArgumentException $e) {
            $type = null;
        }

        if (!$type) {
            set_flash('error', 'Event type not found.');
            redirect('/admin/events/types');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section = sanitize($_POST['section'] ?? '');
            $xpValue = (int)($_POST['xpValue'] ?? 0);
            $effectiveFrom = sanitize($_POST['effectiveFrom'] ?? '');
            $effectiveTo = sanitize($_POST['effectiveTo'] ?? '');
            $isActive = !empty($_POST['isActive']);

            $errors = $this->validateEventVersion($section, $xpValue, $effectiveFrom, $effectiveTo);

            if (empty($errors)) {
                $now = new MongoDB\BSON\UTCDateTime();
                mongo_insert_one('gamification_event_versions', [
                    'eventTypeId' => $type->_id,
                    'section' => $section,
                    'xpValue' => $xpValue,
                    'effectiveFrom' => $this->toUtcDateTime($effectiveFrom),
                    'effectiveTo' => $effectiveTo ? $this->toUtcDateTime($effectiveTo) : null,
                    'isActive' => $isActive,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ]);
                set_flash('success', 'Event version created.');
                redirect('/admin/events/versions?eventTypeId=' . $type->_id);
            }

            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }

        render('admin/events/versions_add', ['type' => $type], 'admin');
    }

    public function versionsEdit(): void
    {
        $id = $_GET['id'] ?? '';
        $eventTypeId = $_GET['eventTypeId'] ?? '';
        try {
            $type = mongo_find_one('gamification_event_types', ['_id' => oid($eventTypeId)]);
            $version = mongo_find_one('gamification_event_versions', ['_id' => oid($id)]);
        } catch (InvalidArgumentException $e) {
            $type = null;
            $version = null;
        }

        if (!$type || !$version) {
            set_flash('error', 'Event version not found.');
            redirect('/admin/events/types');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section = sanitize($_POST['section'] ?? '');
            $xpValue = (int)($_POST['xpValue'] ?? 0);
            $effectiveFrom = sanitize($_POST['effectiveFrom'] ?? '');
            $effectiveTo = sanitize($_POST['effectiveTo'] ?? '');
            $isActive = !empty($_POST['isActive']);

            $errors = $this->validateEventVersion($section, $xpValue, $effectiveFrom, $effectiveTo);

            if (empty($errors)) {
                mongo_update_one('gamification_event_versions', ['_id' => $version->_id], [
                    '$set' => [
                        'section' => $section,
                        'xpValue' => $xpValue,
                        'effectiveFrom' => $this->toUtcDateTime($effectiveFrom),
                        'effectiveTo' => $effectiveTo ? $this->toUtcDateTime($effectiveTo) : null,
                        'isActive' => $isActive,
                        'updatedAt' => new MongoDB\BSON\UTCDateTime(),
                    ],
                ]);
                set_flash('success', 'Event version updated.');
                redirect('/admin/events/versions?eventTypeId=' . $type->_id);
            }

            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }

        render('admin/events/versions_edit', ['type' => $type, 'version' => $version], 'admin');
    }

    public function seed(): void
    {
        $userCount = mongo_find('users', [], ['limit' => 1]);
        if (empty($userCount)) {
            $now = new MongoDB\BSON\UTCDateTime();
            $samples = [
                ['name' => 'Ava Lopez', 'age' => 28],
                ['name' => 'Niko Patel', 'age' => 35],
                ['name' => 'Maria Chen', 'age' => 42],
            ];
            foreach ($samples as $sample) {
                $email = slugify($sample['name']) . '.' . time() . rand(1, 100) . '@example.com';
                mongo_insert_one('users', [
                    'name' => $sample['name'],
                    'age' => $sample['age'],
                    'email' => $email,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ]);
            }
        }

        $eventTypes = [
            ['eventKey' => 'fls_profile_complete', 'name' => 'Profile Complete'],
            ['eventKey' => 'fws_session_attend', 'name' => 'Session Attend'],
            ['eventKey' => 'mes_goal_set', 'name' => 'Goal Set'],
            ['eventKey' => 'fts_action_logged', 'name' => 'Action Logged'],
        ];

        foreach ($eventTypes as $type) {
            $existing = mongo_find_one('gamification_event_types', ['eventKey' => $type['eventKey']]);
            if (!$existing) {
                $now = new MongoDB\BSON\UTCDateTime();
                $typeId = mongo_insert_one('gamification_event_types', [
                    'eventKey' => $type['eventKey'],
                    'name' => $type['name'],
                    'description' => null,
                    'isActive' => true,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ]);
                $this->seedVersion($typeId, $type['eventKey']);
            }
        }

        $existingTypes = mongo_find('gamification_event_types', [], []);
        foreach ($existingTypes as $type) {
            $version = mongo_find_one('gamification_event_versions', ['eventTypeId' => $type->_id]);
            if (!$version) {
                $this->seedVersion($type->_id, $type->eventKey ?? 'seeded');
            }
        }

        set_flash('success', 'Seed data checked and inserted as needed.');
        redirect('/admin/events/types');
    }

    private function seedVersion(MongoDB\BSON\ObjectId $typeId, string $eventKey): void
    {
        $now = new MongoDB\BSON\UTCDateTime();
        mongo_insert_one('gamification_event_versions', [
            'eventTypeId' => $typeId,
            'section' => 'FLS',
            'xpValue' => 10,
            'effectiveFrom' => $now,
            'effectiveTo' => null,
            'isActive' => true,
            'createdAt' => $now,
            'updatedAt' => $now,
        ]);
    }

    private function validateEventType(string $eventKey, string $name): array
    {
        $errors = [];
        if (!$eventKey || !preg_match('/^[a-z0-9_]+$/', $eventKey)) {
            $errors[] = 'Event key is required and must use lowercase letters, numbers, or underscores.';
        }
        if (!$name) {
            $errors[] = 'Name is required.';
        }
        return $errors;
    }

    private function validateEventVersion(string $section, int $xpValue, string $effectiveFrom, string $effectiveTo): array
    {
        $errors = [];
        $validSections = ['FLS', 'FWS', 'MES', 'FTS'];
        if (!in_array($section, $validSections, true)) {
            $errors[] = 'Section must be one of FLS, FWS, MES, FTS.';
        }
        if ($xpValue < 0) {
            $errors[] = 'XP value must be 0 or greater.';
        }
        if (!$effectiveFrom) {
            $errors[] = 'Effective from is required.';
        }
        if ($effectiveFrom && $effectiveTo) {
            $from = strtotime($effectiveFrom);
            $to = strtotime($effectiveTo);
            if ($to !== false && $from !== false && $to <= $from) {
                $errors[] = 'Effective to must be after effective from.';
            }
        }
        return $errors;
    }

    private function toUtcDateTime(string $value): MongoDB\BSON\UTCDateTime
    {
        $date = new DateTime($value, new DateTimeZone('UTC'));
        return new MongoDB\BSON\UTCDateTime($date->getTimestamp() * 1000);
    }
}
