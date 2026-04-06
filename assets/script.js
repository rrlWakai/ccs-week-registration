// =============================
// ELEMENTS (SAFE SELECT)
// =============================
const formEl = document.getElementById("form");
const modal = document.getElementById("modal");
const category = document.getElementById("category");
const activity = document.getElementById("activity");

// =============================
// CATEGORY → ACTIVITY (DEPENDENT DROPDOWN)
// =============================
if (category && activity) {
    category.addEventListener("change", function () {
        const categoryId = this.value;

        if (!categoryId) {
            activity.innerHTML = '<option>Select Category First</option>';
            return;
        }

        activity.innerHTML = '<option>Loading...</option>';

        fetch(`../actions/get_activities.php?category_id=${categoryId}`)
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "") {
                    activity.innerHTML = '<option>No activities found</option>';
                } else {
                    activity.innerHTML = data;
                }
            })
            .catch(() => {
                activity.innerHTML = '<option>Error loading activities</option>';
            });
    });
}

// =============================
// FORM SUBMIT (AJAX)
// =============================
if (formEl) {
    formEl.addEventListener("submit", function (e) {
        e.preventDefault();

        fetch("../actions/submit_registration.php", {
            method: "POST",
            body: new FormData(formEl)
        })
        .then(res => res.text())
        .then(response => {
            console.log(response);

            if (response.toLowerCase().includes("success")) {
                if (modal) modal.style.display = "flex";

                formEl.reset();

                // Reset activity dropdown
                if (activity) {
                    activity.innerHTML = '<option>Select Category First</option>';
                }
            } else {
                alert(response);
            }
        })
        .catch(() => {
            alert("Something went wrong.");
        });
    });
}

// =============================
// MODAL
// =============================
function closeModal() {
    if (modal) modal.style.display = "none";
}

// =============================
// SIDEBAR
// =============================
function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('open');
    document.getElementById('sidebarOverlay')?.classList.toggle('active');
}

function closeSidebar() {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('sidebarOverlay')?.classList.remove('active');
}