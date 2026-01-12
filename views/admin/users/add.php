<h2>Add User</h2>
<form method="post" action="/admin/users/add">
    <label>Name<br>
        <input type="text" name="name" required>
    </label>
    <br><br>
    <label>Age<br>
        <input type="number" name="age" min="1" max="120" required>
    </label>
    <br><br>
    <button class="btn" type="submit">Save</button>
    <a class="btn-secondary" href="/admin/users">Cancel</a>
</form>
