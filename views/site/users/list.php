<h2>Users</h2>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($users)): ?>
        <tr><td colspan="3">No users found.</td></tr>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo sanitize($user->name ?? ''); ?></td>
                <td><?php echo sanitize((string)($user->age ?? '')); ?></td>
                <td><a href="/users/view?id=<?php echo $user->_id; ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
