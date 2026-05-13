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

