<h2>Edit User</h2>
<form method="post" action="/admin/users/edit?id=<?php echo $user->_id; ?>">
    <label>Name<br>
        <input type="text" name="name" value="<?php echo sanitize($user->name ?? ''); ?>" required>
    </label>
    <br><br>
    <label>Age<br>
        <input type="number" name="age" min="1" max="120" value="<?php echo sanitize((string)($user->age ?? '')); ?>" required>
    </label>
    <br><br>
    <button class="btn" type="submit">Update</button>
    <a class="btn-secondary" href="/admin/users">Cancel</a>
</form>
