function copyLink(url, btn = null) {
    if (!url) return;

    navigator.clipboard.writeText(url)
        .then(() => {
            if (btn) {
                const originalText = btn.innerText;
                btn.innerText = "Copied ✓";

                setTimeout(() => {
                    btn.innerText = originalText;
                }, 1500);
            }
        })
        .catch(err => {
            console.error("Copy failed:", err);
        });
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

const passwordInput = document.getElementById("password");
const strengthBar = document.getElementById("strengthBar");
const strengthText = document.getElementById("strengthText");

if (passwordInput) {
    passwordInput.addEventListener("input", function () {
        const value = passwordInput.value;
        let strength = 0;

        if (value.length >= 6) strength++;
        if (value.match(/[A-Z]/)) strength++;
        if (value.match(/[0-9]/)) strength++;
        if (value.match(/[^A-Za-z0-9]/)) strength++;

        let width = strength * 25;

        if (value.length === 0) {
            strengthBar.style.width = "0%";
            strengthText.innerText = "";
            return;
        }

        if (strength <= 1) {
            strengthBar.style.background = "red";
            strengthText.innerText = "Weak password";
        } else if (strength === 2) {
            strengthBar.style.background = "orange";
            strengthText.innerText = "Medium password";
        } else if (strength === 3) {
            strengthBar.style.background = "yellow";
            strengthText.innerText = "Good password";
        } else {
            strengthBar.style.background = "green";
            strengthText.innerText = "Strong password";
        }

        strengthBar.style.width = width + "%";
    });
}