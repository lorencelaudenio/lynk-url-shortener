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