document.addEventListener('DOMContentLoaded', function () {
    let role = document.getElementById('role');
    if (role) {
        role.addEventListener('change', function (e) {
            let selectedText = e.target.options[e.target.selectedIndex].text;
            const existing = document.getElementById('referral-code-wrapper');
            if (selectedText === 'admin3') {
                const container = role.closest('div.mb-3');
                container.insertAdjacentHTML('afterend', `
                <div class="mb-3" id="referral-code-wrapper">
                    <label for="referral-code" class="form-label">*${window.texts['referralCode']}:</label>
                    <input type="text" name="referralCode" id="referral-code" class="form-control" required>
                </div>
            `);
            } else {
                if (existing) {
                    existing.remove();
                }
            }
        });
    }
});