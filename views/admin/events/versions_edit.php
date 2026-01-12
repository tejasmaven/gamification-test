<h2>Edit Event Version - <?php echo sanitize($type->name ?? ''); ?></h2>
<form method="post" action="/admin/events/versions/edit?eventTypeId=<?php echo $type->_id; ?>&id=<?php echo $version->_id; ?>">
    <label>Section<br>
        <select name="section" required>
            <?php foreach (['FLS', 'FWS', 'MES', 'FTS'] as $section): ?>
                <option value="<?php echo $section; ?>" <?php echo ($version->section ?? '') === $section ? 'selected' : ''; ?>><?php echo $section; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <br><br>
    <label>XP Value<br>
        <input type="number" name="xpValue" min="0" value="<?php echo sanitize((string)($version->xpValue ?? 0)); ?>" required>
    </label>
    <br><br>
    <label>Effective From (UTC)<br>
        <input type="datetime-local" name="effectiveFrom" value="<?php echo $version->effectiveFrom ? $version->effectiveFrom->toDateTime()->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i') : ''; ?>" required>
    </label>
    <br><br>
    <label>Effective To (UTC, optional)<br>
        <input type="datetime-local" name="effectiveTo" value="<?php echo $version->effectiveTo ? $version->effectiveTo->toDateTime()->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i') : ''; ?>">
    </label>
    <br><br>
    <label>
        <input type="checkbox" name="isActive" <?php echo !empty($version->isActive) ? 'checked' : ''; ?>> Active
    </label>
    <br><br>
    <button class="btn" type="submit">Update</button>
    <a class="btn-secondary" href="/admin/events/versions?eventTypeId=<?php echo $type->_id; ?>">Cancel</a>
</form>
