<div class="course">
    <input type="text" name="subjectCode[]" placeholder="Subject Code" required>
    <input type="number" name="units[]" placeholder="Units" min="1" required>
    <input type="number" name="grades[]" placeholder="Grade (0-4)" min="0" max="4" step="0.1" required>
    <button type="button" onclick="removeCourse(this)">Remove</button>
</div>
