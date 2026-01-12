<h2>User Profile</h2>
<p><strong>Name:</strong> <?php echo sanitize($user->name ?? ''); ?></p>
<p><strong>Age:</strong> <?php echo sanitize((string)($user->age ?? '')); ?></p>
<p>
    <a class="btn" href="/actions/add?userId=<?php echo $user->_id; ?>">Add Event/Action</a>
    <a class="btn-secondary" href="/users">Back to Users</a>
</p>

<h3>Recent Actions</h3>
<table>
    <thead>
    <tr>
        <th>Occurred At</th>
        <th>Section</th>
        <th>XP</th>
        <th>Event</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($ledger)): ?>
        <tr><td colspan="4">No actions recorded.</td></tr>
    <?php else: ?>
        <?php foreach ($ledger as $entry): ?>
            <tr>
                <td><?php echo format_datetime($entry->occurredAt ?? null); ?></td>
                <td><?php echo sanitize($entry->section ?? ''); ?></td>
                <td><?php echo sanitize((string)($entry->xp ?? '')); ?></td>
                <td>
                    <?php echo sanitize($entry->meta->eventName ?? ''); ?>
                    <small>(<?php echo sanitize($entry->meta->eventKey ?? ''); ?>)</small>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
