<h2>Add Event Version - <?php echo sanitize($type->name ?? ''); ?></h2>
<form method="post" action="/admin/events/versions/add?eventTypeId=<?php echo $type->_id; ?>">
    <label>Section<br>
        <select name="section" required>
            <option value="FLS">FLS</option>
            <option value="FWS">FWS</option>
            <option value="MES">MES</option>
            <option value="FTS">FTS</option>
        </select>
    </label>
    <br><br>
    <label>XP Value<br>
        <input type="number" name="xpValue" min="0" required>
    </label>
    <br><br>
    <label>Effective From (UTC)<br>
        <input type="datetime-local" name="effectiveFrom" required>
    </label>
    <br><br>
    <label>Effective To (UTC, optional)<br>
        <input type="datetime-local" name="effectiveTo">
    </label>
    <br><br>
    <label>
        <input type="checkbox" name="isActive" checked> Active
    </label>
    <br><br>
    <button class="btn" type="submit">Save</button>
    <a class="btn-secondary" href="/admin/events/versions?eventTypeId=<?php echo $type->_id; ?>">Cancel</a>
</form>
