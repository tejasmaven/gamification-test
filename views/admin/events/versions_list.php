<h2>Event Versions - <?php echo sanitize($type->name ?? ''); ?></h2>
<div class="actions">
    <a class="btn" href="/admin/events/versions/add?eventTypeId=<?php echo $type->_id; ?>">Add Version</a>
    <a class="btn-secondary" href="/admin/events/types">Back to Types</a>
</div>
<table>
    <thead>
    <tr>
        <th>Section</th>
        <th>XP</th>
        <th>Effective From</th>
        <th>Effective To</th>
        <th>Active</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($versions)): ?>
        <tr><td colspan="6">No versions found.</td></tr>
    <?php else: ?>
        <?php foreach ($versions as $version): ?>
            <tr>
                <td><?php echo sanitize($version->section ?? ''); ?></td>
                <td><?php echo sanitize((string)($version->xpValue ?? '')); ?></td>
                <td><?php echo format_datetime($version->effectiveFrom ?? null); ?></td>
                <td><?php echo format_datetime($version->effectiveTo ?? null); ?></td>
                <td><?php echo !empty($version->isActive) ? 'Yes' : 'No'; ?></td>
                <td>
                    <a href="/admin/events/versions/edit?eventTypeId=<?php echo $type->_id; ?>&id=<?php echo $version->_id; ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
