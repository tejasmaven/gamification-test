<h2>Add Event Type</h2>
<form method="post" action="/admin/events/types/add">
    <label>Event Key<br>
        <input type="text" name="eventKey" required>
    </label>
    <br><br>
    <label>Name<br>
        <input type="text" name="name" required>
    </label>
    <br><br>
    <label>Description<br>
        <textarea name="description" rows="3"></textarea>
    </label>
    <br><br>
    <label>
        <input type="checkbox" name="isActive" checked> Active
    </label>
    <br><br>
    <button class="btn" type="submit">Save</button>
    <a class="btn-secondary" href="/admin/events/types">Cancel</a>
</form>
