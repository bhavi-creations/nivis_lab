(() => {
    const form = document.getElementById('customer-auth-form');
    if (!form) return;

    const requestButton = document.getElementById('request-otp');
    const finishButton = document.getElementById('finish-auth');
    const phoneInput = document.getElementById('phone');
    const challengeInput = document.getElementById('challenge_id');
    const resendAtInput = document.getElementById('resend_at_ms');
    const codeInput = document.getElementById('otp_code');
    const step = document.getElementById('otp-step');
    const message = document.getElementById('otp-message');
    const csrf = form.querySelector('[name="csrf"]')?.value || '';
    let cooldownTimer;

    const validateDetailsBeforeCode = () => {
        const fields = [phoneInput];
        if (form.dataset.purpose === 'registration') {
            fields.push(
                form.elements.name,
                form.elements.address,
                form.elements.country,
                form.elements.province,
                form.elements.postcode,
                form.elements.email
            );
        }
        for (const field of fields) {
            if (field && !field.reportValidity()) return false;
        }
        return true;
    };

    const showMessage = (text, isError = false) => {
        message.textContent = text;
        message.hidden = false;
        message.classList.toggle('error', isError);
        message.setAttribute('role', isError ? 'alert' : 'status');
    };

    const clearChallenge = () => {
        challengeInput.value = '';
        if (resendAtInput) resendAtInput.value = '0';
        codeInput.value = '';
        codeInput.required = false;
        codeInput.disabled = true;
        step.hidden = true;
        finishButton.hidden = true;
        requestButton.hidden = false;
        message.hidden = true;
        message.textContent = '';
        form.dataset.challenge = '';
        if (cooldownTimer) window.clearInterval(cooldownTimer);
        requestButton.disabled = false;
        requestButton.textContent = 'Send WhatsApp code';
    };

    const startCooldown = (seconds) => {
        if (cooldownTimer) window.clearInterval(cooldownTimer);
        let remaining = Math.max(0, Number(seconds) || 0);
        requestButton.disabled = remaining > 0;
        const update = () => {
            requestButton.textContent = remaining > 0 ? `Send another code in ${remaining}s` : 'Send another code';
            if (remaining <= 0) {
                window.clearInterval(cooldownTimer);
                requestButton.disabled = false;
            }
            remaining -= 1;
        };
        update();
        cooldownTimer = window.setInterval(update, 1000);
    };

    phoneInput?.addEventListener('input', () => {
        if (challengeInput.value) clearChallenge();
    });

    requestButton?.addEventListener('click', async () => {
        // The code field is required only to finish sign-in/registration. It
        // must not prevent requesting a replacement code after a failed entry.
        if (!validateDetailsBeforeCode()) return;
        requestButton.disabled = true;
        requestButton.textContent = 'Sending code…';
        message.hidden = true;
        const registration = form.dataset.purpose === 'registration' ? {
            fullName: form.elements.name.value.trim(),
            email: form.elements.email.value.trim()
        } : undefined;
        try {
            const response = await fetch('whatsapp_otp.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    action: 'request',
                    csrf,
                    purpose: form.dataset.purpose,
                    phone: phoneInput.value.trim(),
                    registration
                })
            });
            const responseText = await response.text();
            let result;
            try {
                result = JSON.parse(responseText);
            } catch {
                throw new Error('The account service returned an invalid response. Please refresh the page and try again.');
            }
            if (!response.ok) throw new Error(result.error || 'We could not send a code. Please try again.');
            const challengeId = result.data?.challengeId;
            if (!challengeId) throw new Error('The verification request could not be confirmed. Please try again.');
            challengeInput.value = challengeId;
            const resendAfter = Number(result.data?.resendAfter) || 60;
            const resendAt = Date.now() + resendAfter * 1000;
            if (resendAtInput) resendAtInput.value = String(resendAt);
            form.dataset.challenge = challengeId;
            step.hidden = false;
            codeInput.disabled = false;
            codeInput.required = true;
            finishButton.hidden = false;
            requestButton.hidden = false;
            codeInput.focus();
            showMessage('A six-digit code was sent to your WhatsApp number.');
            startCooldown(resendAfter);
        } catch (error) {
            showMessage(error.message || 'We could not send a code. Please try again.', true);
            const remaining = resendAtInput ? Math.ceil((Number(resendAtInput.value) - Date.now()) / 1000) : 0;
            if (challengeInput.value && remaining > 0) startCooldown(remaining);
            else {
                requestButton.disabled = false;
                requestButton.textContent = challengeInput.value ? 'Send another code' : 'Send WhatsApp code';
            }
        }
    });

    form.addEventListener('submit', (event) => {
        if (!challengeInput.value) {
            event.preventDefault();
            showMessage('Request a WhatsApp code before continuing.', true);
            return;
        }
        if (!form.reportValidity()) event.preventDefault();
    });

    if (challengeInput.value) {
        step.hidden = false;
        codeInput.disabled = false;
        codeInput.required = true;
        requestButton.hidden = false;
        finishButton.hidden = false;
        requestButton.textContent = 'Send another code';
        showMessage('Enter the code sent to your WhatsApp number.');
        const remaining = resendAtInput ? Math.ceil((Number(resendAtInput.value) - Date.now()) / 1000) : 0;
        if (remaining > 0) startCooldown(remaining);
    }
})();
