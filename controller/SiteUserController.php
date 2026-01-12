<?php
require_once __DIR__ . '/../config/mongodb.php';
require_once __DIR__ . '/../includes/functions.php';

class SiteUserController
{
    public function list(): void
    {
        $users = mongo_find('users', [], ['sort' => ['createdAt' => -1]]);
        render('site/users/list', ['users' => $users], 'site');
    }

    public function view(): void
    {
        $id = $_GET['id'] ?? '';
        try {
            $user = mongo_find_one('users', ['_id' => oid($id)]);
        } catch (InvalidArgumentException $e) {
            $user = null;
        }

        if (!$user) {
            set_flash('error', 'User not found.');
            redirect('/users');
        }

        $ledger = mongo_find('user_xp_ledger', ['userId' => $user->_id], ['sort' => ['occurredAt' => -1], 'limit' => 20]);
        render('site/users/view', ['user' => $user, 'ledger' => $ledger], 'site');
    }
}
