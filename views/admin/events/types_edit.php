<h2>Edit Event Type</h2>
<form method="post" action="/admin/events/types/edit?id=<?php echo $type->_id; ?>">
    <label>Event Key<br>
        <input type="text" name="eventKey" value="<?php echo sanitize($type->eventKey ?? ''); ?>" required>
    </label>
    <br><br>
    <label>Name<br>
        <input type="text" name="name" value="<?php echo sanitize($type->name ?? ''); ?>" required>
    </label>
    <br><br>
    <label>Description<br>
        <textarea name="description" rows="3"><?php echo sanitize($type->description ?? ''); ?></textarea>
    </label>
    <br><br>
    <label>
        <input type="checkbox" name="isActive" <?php echo !empty($type->isActive) ? 'checked' : ''; ?>> Active
    </label>
    <br><br>
    <button class="btn" type="submit">Update</button>
    <a class="btn-secondary" href="/admin/events/types">Cancel</a>
</form>
