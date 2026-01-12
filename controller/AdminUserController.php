<?php
require_once __DIR__ . '/../config/mongodb.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminUserController
{
    public function list(): void
    {
        $search = input_value($_GET, 'search');
        $filter = [];
        if ($search) {
            $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')];
        }
        $users = mongo_find('users', $filter, ['sort' => ['createdAt' => -1]]);
        render('admin/users/list', ['users' => $users, 'search' => $search], 'admin');
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $age = (int)($_POST['age'] ?? 0);
            $errors = $this->validate($name, $age);

            if (empty($errors)) {
                $email = slugify($name) . '.' . time() . '@example.com';
                $now = new MongoDB\BSON\UTCDateTime();
                mongo_insert_one('users', [
                    'name' => $name,
                    'age' => $age,
                    'email' => $email,
                    'createdAt' => $now,
                    'updatedAt' => $now,
                ]);
                set_flash('success', 'User created successfully.');
                redirect('/admin/users');
            }
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }
        render('admin/users/add', [], 'admin');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? '';
        try {
            $user = mongo_find_one('users', ['_id' => oid($id)]);
        } catch (InvalidArgumentException $e) {
            $user = null;
        }

        if (!$user) {
            set_flash('error', 'User not found.');
            redirect('/admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $age = (int)($_POST['age'] ?? 0);
            $errors = $this->validate($name, $age);

            if (empty($errors)) {
                mongo_update_one('users', ['_id' => $user->_id], [
                    '$set' => [
                        'name' => $name,
                        'age' => $age,
                        'updatedAt' => new MongoDB\BSON\UTCDateTime(),
                    ],
                ]);
                set_flash('success', 'User updated successfully.');
                redirect('/admin/users');
            }
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
        }

        render('admin/users/edit', ['user' => $user], 'admin');
    }

    private function validate(string $name, int $age): array
    {
        $errors = [];
        if (strlen($name) < 2) {
            $errors[] = 'Name is required and must be at least 2 characters.';
        }
        if ($age < 1 || $age > 120) {
            $errors[] = 'Age must be between 1 and 120.';
        }
        return $errors;
    }
}
