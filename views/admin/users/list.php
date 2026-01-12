<h2>Users</h2>
<div class="actions">
    <a class="btn" href="/admin/users/add">Add User</a>
</div>
<form method="get" action="/admin/users">
    <input type="text" name="search" placeholder="Search name" value="<?php echo sanitize($search); ?>">
    <button class="btn-secondary" type="submit">Search</button>
</form>
<br>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($users)): ?>
        <tr><td colspan="4">No users found.</td></tr>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo sanitize($user->name ?? ''); ?></td>
                <td><?php echo sanitize((string)($user->age ?? '')); ?></td>
                <td><?php echo sanitize($user->email ?? ''); ?></td>
                <td><a href="/admin/users/edit?id=<?php echo $user->_id; ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
