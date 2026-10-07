// Auto-resize textareas to fit their content
function resizeTextarea(textarea) {
  textarea.style.height = 'auto';
  textarea.style.height = textarea.scrollHeight + 'px';
}

// Populate the entry form when editing an existing entry (incl. date/time)
window.startEdit = function (btn) {
  const form = document.getElementById('entry-form');
  if (!form) return;

  form.querySelector('#entry-id').value = btn.dataset.id || '';
  form.querySelector('#content').value  = btn.dataset.content || '';
  form.querySelector('#mood').value     = btn.dataset.mood || '😏 Neutral';
  form.querySelector('#tags').value     = (btn.dataset.tags || '')
    .split(',')
    .map(s => s.trim())
    .filter(Boolean)
    .join(', ');

  const ts = btn.dataset.ts;
  if (ts) {
    const dt = new Date(ts.replace(' ', 'T') + 'Z');
    const pad = n => String(n).padStart(2, '0');
    const y  = dt.getFullYear();
    const m  = pad(dt.getMonth() + 1);
    const d  = pad(dt.getDate());
    const hh = pad(dt.getHours());
    const mm = pad(dt.getMinutes());

    const dateEl = document.getElementById('entry_date');
    const timeEl = document.getElementById('entry_time');
    if (dateEl) dateEl.value = `${y}-${m}-${d}`;
    if (timeEl) timeEl.value = `${hh}:${mm}`;
  }

  const saveBtn = form.querySelector('button[name="save_entry"]');
  if (saveBtn) saveBtn.textContent = 'Update entry';

  const cancelBtn = document.getElementById('cancel-edit');
  if (cancelBtn) cancelBtn.style.display = '';

  document.getElementById('content').focus();
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Reset the form back to "new entry" mode
function resetForm() {
  const form = document.getElementById('entry-form');
  if (!form) return;

  form.reset(); // clears inputs back to defaults

  form.querySelector('#entry-id').value = '';
  const saveBtn = form.querySelector('button[name="save_entry"]');
  if (saveBtn) saveBtn.textContent = 'Save entry';

  const cancelBtn = document.getElementById('cancel-edit');
  if (cancelBtn) cancelBtn.style.display = 'none';
}

// Run once DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  // Initialise textarea resizing
  document.querySelectorAll('textarea').forEach(function (textarea) {
    resizeTextarea(textarea);
    textarea.addEventListener('input', function () {
      resizeTextarea(textarea);
    });
  });

  // Hook up cancel button
  const cancelBtn = document.getElementById('cancel-edit');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', resetForm);
  }
});
