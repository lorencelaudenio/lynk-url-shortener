function copyLink(url, btn = null, toastId = "copyToast") {
    if (!url) return;

    const toast = document.getElementById(toastId);

    navigator.clipboard.writeText(url)
        .then(() => {

            if (btn) {
                const original = btn.innerText;
                btn.innerText = "✓";

                setTimeout(() => {
                    btn.innerText = original;
                }, 1200);
            }

            if (toast) {
                toast.classList.add("show");

                setTimeout(() => {
                    toast.classList.remove("show");
                }, 1200);
            }

        })
        .catch(err => console.error("Copy failed:", err));
}

/* ✅ MUST BE OUTSIDE */
function showToast(message, type = "info") {
    const toast = document.getElementById("toast");

    if (!toast) return;

    toast.className = "toast show " + type;
    toast.innerText = message;

    setTimeout(() => {
        toast.className = "toast";
    }, 3000);
}

function togglePassword() {
    const input = document.getElementById("password");

    input.type = (input.type === "password") ? "text" : "password";
}

document.addEventListener("DOMContentLoaded", function () {

    function setupMeter(inputId, barId, textId) {

        const input = document.getElementById(inputId);
        const bar = document.getElementById(barId);
        const text = document.getElementById(textId);

        if (!input || !bar || !text) return;

        input.addEventListener("input", function () {

            let value = input.value;
            let score = 0;

            if (value.length >= 6) score++;
            if (value.match(/[A-Z]/)) score++;
            if (value.match(/[0-9]/)) score++;
            if (value.match(/[^A-Za-z0-9]/)) score++;

            let percent = score * 25;

            bar.style.width = percent + "%";

            if (value.length === 0) {
                bar.style.width = "0%";
                text.innerText = "";
                return;
            }

            if (score <= 1) {
                bar.style.background = "#ef4444";
                text.innerText = "Weak";
            } 
            else if (score === 2) {
                bar.style.background = "#f59e0b";
                text.innerText = "Medium";
            } 
            else if (score === 3) {
                bar.style.background = "#eab308";
                text.innerText = "Good";
            } 
            else {
                bar.style.background = "#22c55e";
                text.innerText = "Strong";
            }
        });
    }

    // REGISTER
setupMeter("register_password", "registerPwBar", "registerPwText");
    // PROFILE
    setupMeter("profile_password", "profilePwBar", "profilePwText");

});

const usernameInput = document.getElementById("username");
const emailInput = document.getElementById("email");

const usernameMsg = document.getElementById("usernameMsg");
const emailMsg = document.getElementById("emailMsg");

let usernameValid = false;
let emailValid = false;

function checkUser() {

    const username = usernameInput?.value;
    const email = emailInput?.value;

    fetch("check_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "username=" + encodeURIComponent(username) +
              "&email=" + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {

        // USERNAME
        if (data.username_exists) {
            usernameMsg.innerText = "❌ Username already taken";
            usernameMsg.style.color = "red";
            usernameValid = false;
        } else {
            usernameMsg.innerText = "✅ Username available";
            usernameMsg.style.color = "green";
            usernameValid = true;
        }

        // EMAIL
        if (data.email_exists) {
            emailMsg.innerText = "❌ Email already exists";
            emailMsg.style.color = "red";
            emailValid = false;
        } else {
            emailMsg.innerText = "✅ Email available";
            emailMsg.style.color = "green";
            emailValid = true;
        }

    });

}

// debounce simple
if (usernameInput) usernameInput.addEventListener("input", checkUser);
if (emailInput) emailInput.addEventListener("input", checkUser);

const registerForm = document.getElementById("registerForm");

if (registerForm) {

    registerForm.addEventListener("submit", function (e) {

        if (!usernameValid || !emailValid) {
            e.preventDefault();
            showToast("Username or email already exists", "error");
        }

    });

    document.getElementById("reportForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch("send_report.php", {
        method: "POST",
        body: formData
    });

    const text = await res.text();

    if (text === "success") {
        alert("Report submitted successfully!");
        this.reset();
    } else {
        alert("Failed to submit report!");
    }
});

}

function copyProfileLink() {
    const copyText = document.getElementById("profileLink");

    navigator.clipboard.writeText(copyText.value)
        .then(() => {
            alert("Profile link copied!");
        })
        .catch(err => {
            console.error("Copy failed:", err);
        });
}

function openEditModal(id, url) {
    const modal = document.getElementById("editModal");

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_url").value = url;

    modal.style.display = "flex";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

function openModal() {
    document.getElementById("resultModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("resultModal").style.display = "none";
}

if (window.__SHORT_URL__) {
    document.getElementById("shortUrl").textContent = window.__SHORT_URL__;
    document.getElementById("shortUrl").href = window.__SHORT_URL__;

    if (window.__SHORT_URL__) {

    const shortUrlEl = document.getElementById("shortUrl");
    const copyBtn = document.getElementById("copyBtn");

    if (shortUrlEl) {
        shortUrlEl.textContent = window.__SHORT_URL__;
        shortUrlEl.href = window.__SHORT_URL__;
    }

    if (copyBtn) {
        copyBtn.onclick = function () {
            copyLink(window.__SHORT_URL__, this, "copyToast");
        };
    }

    if (window.__SHOW_CTA__) {
        const cta = document.getElementById("ctaSection");
        if (cta) cta.style.display = "block";
    }

    openModal();
}

    if (window.__SHOW_CTA__) {
        document.getElementById("ctaSection").style.display = "block";
    }

    openModal();
}

document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatarInput');
    const avatarForm = document.getElementById('avatarForm');

    if (avatarInput && avatarForm) {
        avatarInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                avatarForm.submit();
            }
        });
    }
});

avatarInput.addEventListener('change', function () {
    if (this.files.length > 0) {
        avatarForm.querySelector('.avatar-upload-btn').innerText = '...';
        avatarForm.submit();
    }
});

document.addEventListener('DOMContentLoaded', function () {

    const profileData = document.getElementById('profileData');
    const profileUrl = profileData.getAttribute('data-url');

    const copyBtn = document.getElementById('copyBtn');
    const openBtn = document.getElementById('openBtn');

    let isCopying = false;

    copyBtn.addEventListener('click', async function () {

    if (isCopying) return;
    isCopying = true;

    const originalHTML = copyBtn.innerHTML;

    try {
        await navigator.clipboard.writeText(profileUrl);

        // show success state
        copyBtn.innerHTML = `
            <span style="font-size:12px;">✓</span>
        `;

        copyBtn.classList.add("copy-animate");

        setTimeout(() => {
            copyBtn.classList.remove("copy-animate");
            copyBtn.innerHTML = originalHTML;
            isCopying = false;
        }, 1200);

    } catch (err) {

        copyBtn.innerHTML = `
            <span style="font-size:12px;">Error</span>
        `;

        setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
            isCopying = false;
        }, 1200);
    }

});

    openBtn.addEventListener('click', function () {
        window.open(profileUrl, '_blank');
    });

});

function triggerUpload(id) {
    document.getElementById('file-' + id).click();
}

function uploadThumb(event, id) {

    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("link_id", id);
    formData.append("thumbnail", file);

    fetch("update-thumbnail.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.success) {
            document.getElementById('thumb-' + id).src = data.url;
        } else {
            alert("Upload failed");
        }

    });
}

function editLink(id) {

    document.getElementById('view-' + id).style.display = 'none';

    document.getElementById('edit-' + id).style.display = 'block';
}

function cancelEdit(id) {

    document.getElementById('view-' + id).style.display = 'block';

    document.getElementById('edit-' + id).style.display = 'none';
}

function previewAddThumb(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('addThumbPreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}