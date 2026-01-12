<?php
require_once __DIR__ . '/../config/mongodb.php';
require_once __DIR__ . '/../includes/functions.php';

class SiteActionController
{
    public function add(): void
    {
        $userId = $_GET['userId'] ?? '';
        try {
            $user = mongo_find_one('users', ['_id' => oid($userId)]);
        } catch (InvalidArgumentException $e) {
            $user = null;
        }

        if (!$user) {
            set_flash('error', 'User not found.');
            redirect('/users');
        }

        $eventTypes = mongo_find('gamification_event_types', ['isActive' => true], ['sort' => ['name' => 1]]);
        $selectedTypeId = $_POST['eventTypeId'] ?? ($eventTypes[0]->_id ?? null);
        $selectedTypeObjId = null;
        if ($selectedTypeId) {
            try {
                $selectedTypeObjId = is_string($selectedTypeId) ? oid($selectedTypeId) : $selectedTypeId;
            } catch (InvalidArgumentException $e) {
                $selectedTypeObjId = null;
            }
        }

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $nowUtc = new MongoDB\BSON\UTCDateTime($now->getTimestamp() * 1000);
        $versions = [];
        if ($selectedTypeObjId) {
            $versions = mongo_find('gamification_event_versions', [
                'eventTypeId' => $selectedTypeObjId,
                'isActive' => true,
                'effectiveFrom' => ['$lte' => $nowUtc],
                '$or' => [
                    ['effectiveTo' => null],
                    ['effectiveTo' => ['$gte' => $nowUtc]],
                ],
            ], ['sort' => ['effectiveFrom' => -1]]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventTypeId = sanitize($_POST['eventTypeId'] ?? '');
            $eventVersionId = sanitize($_POST['eventVersionId'] ?? '');
            $occurredAt = sanitize($_POST['occurredAt'] ?? '');
            $source = sanitize($_POST['source'] ?? '');
            $referenceId = sanitize($_POST['referenceId'] ?? '');

            $errors = [];
            try {
                $type = mongo_find_one('gamification_event_types', ['_id' => oid($eventTypeId), 'isActive' => true]);
            } catch (InvalidArgumentException $e) {
                $type = null;
            }
            if (!$type) {
                $errors[] = 'Event type is invalid.';
            }

            try {
                $version = mongo_find_one('gamification_event_versions', ['_id' => oid($eventVersionId)]);
            } catch (InvalidArgumentException $e) {
                $version = null;
            }

            if (!$version) {
                $errors[] = 'Event version is invalid.';
            }

            if ($version && $type && $version->eventTypeId != $type->_id) {
                $errors[] = 'Event version does not belong to selected type.';
            }

            if (!$occurredAt) {
                $errors[] = 'Occurred at is required.';
            }

            $occurredUtc = null;
            if ($occurredAt) {
                $occurredDt = new DateTime($occurredAt, new DateTimeZone('UTC'));
                $occurredUtc = new MongoDB\BSON\UTCDateTime($occurredDt->getTimestamp() * 1000);
            }

            if ($version && $occurredUtc) {
                if ($version->isActive !== true) {
                    $errors[] = 'Event version is inactive.';
                }
                if ($version->effectiveFrom instanceof MongoDB\BSON\UTCDateTime && $version->effectiveFrom > $occurredUtc) {
                    $errors[] = 'Event version not effective yet.';
                }
                if ($version->effectiveTo instanceof MongoDB\BSON\UTCDateTime && $version->effectiveTo < $occurredUtc) {
                    $errors[] = 'Event version expired.';
                }
            }

            if (empty($errors)) {
                $monthKey = $occurredDt->format('Y-m');
                $document = [
                    'userId' => $user->_id,
                    'eventTypeVersionId' => $version->_id,
                    'section' => $version->section,
                    'xp' => $version->xpValue,
                    'occurredAt' => $occurredUtc,
                    'monthKey' => $monthKey,
                    'createdAt' => new MongoDB\BSON\UTCDateTime(),
                    'meta' => [
                        'eventKey' => $type->eventKey,
                        'eventName' => $type->name,
                        'eventTypeId' => $type->_id,
                        'xpValueAtTime' => $version->xpValue,
                    ],
                ];
                if ($source) {
                    $document['source'] = $source;
                }
                if ($referenceId) {
                    $document['referenceId'] = $referenceId;
                }

                try {
                    mongo_insert_one('user_xp_ledger', $document);
                    set_flash('success', 'Action recorded.');
                    redirect('/users/view?id=' . $user->_id);
                } catch (Exception $e) {
                    set_flash('error', 'Unable to record action. Reference ID might already exist.');
                }
            }

            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }

        render('site/actions/add', [
            'user' => $user,
            'eventTypes' => $eventTypes,
            'versions' => $versions,
            'selectedTypeId' => (string)($selectedTypeObjId ?? ''),
        ], 'site');
    }
}
