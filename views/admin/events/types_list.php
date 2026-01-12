<h2>Event Types</h2>
<div class="actions">
    <a class="btn" href="/admin/events/types/add">Add Event Type</a>
</div>
<table>
    <thead>
    <tr>
        <th>Event Key</th>
        <th>Name</th>
        <th>Active</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($types)): ?>
        <tr><td colspan="4">No event types found.</td></tr>
    <?php else: ?>
        <?php foreach ($types as $type): ?>
            <tr>
                <td><?php echo sanitize($type->eventKey ?? ''); ?></td>
                <td><?php echo sanitize($type->name ?? ''); ?></td>
                <td><?php echo !empty($type->isActive) ? 'Yes' : 'No'; ?></td>
                <td>
                    <a href="/admin/events/types/edit?id=<?php echo $type->_id; ?>">Edit</a> |
                    <a href="/admin/events/versions?eventTypeId=<?php echo $type->_id; ?>">Versions</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
