<h2>Add Event/Action for <?php echo sanitize($user->name ?? ''); ?></h2>
<form method="post" action="/actions/add?userId=<?php echo $user->_id; ?>">
    <label>Event Type<br>
        <select name="eventTypeId" required>
            <?php foreach ($eventTypes as $type): ?>
                <option value="<?php echo $type->_id; ?>" <?php echo (string)$type->_id === (string)$selectedTypeId ? 'selected' : ''; ?>>
                    <?php echo sanitize($type->name ?? ''); ?> (<?php echo sanitize($type->eventKey ?? ''); ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <br><br>
    <label>Event Version<br>
        <select name="eventVersionId" required>
            <?php if (empty($versions)): ?>
                <option value="">No active versions available</option>
            <?php else: ?>
                <?php foreach ($versions as $version): ?>
                    <option value="<?php echo $version->_id; ?>">
                        <?php echo sanitize($version->section ?? ''); ?> - XP <?php echo sanitize((string)($version->xpValue ?? '')); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </label>
    <br><br>
    <label>Occurred At (UTC)<br>
        <input type="datetime-local" name="occurredAt" value="<?php echo (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i'); ?>" required>
    </label>
    <br><br>
    <label>Source (optional)<br>
        <input type="text" name="source">
    </label>
    <br><br>
    <label>Reference ID (optional)<br>
        <input type="text" name="referenceId">
    </label>
    <br><br>
    <button class="btn" type="submit">Save</button>
    <a class="btn-secondary" href="/users/view?id=<?php echo $user->_id; ?>">Cancel</a>
</form>
<p><small>Note: change event type and submit to refresh available versions.</small></p>
