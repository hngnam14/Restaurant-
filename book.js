document.addEventListener("DOMContentLoaded", () => {
  const tables = document.querySelectorAll(".table");
  const bookingForm = document.getElementById("bookingForm");
  const form = document.getElementById("formBooking");
  const cancelBtn = document.getElementById("cancelBookingBtn");
  const tableInput = document.getElementById("tableInput");

  // 🟢 Load trạng thái bàn
  fetch("book.php?action=get")
    .then(res => res.json())
    .then(data => {
      data.forEach(item => {
        const btn = document.querySelector(`.table[data-id="${item.table_id}"]`);
        if (btn) btn.classList.add("booked");
      });
    });

  // 🟢 Khi nhấn vào bàn
  tables.forEach(btn => {
    btn.addEventListener("click", () => {
      // Nếu bàn đã đặt → hỏi hủy
      if (btn.classList.contains("booked")) {
        const password = prompt("🔒 Nhập mật khẩu để hủy bàn:");
        if (!password) return;

        const formData = new FormData();
        formData.append("action", "cancel");
        formData.append("table_id", btn.dataset.id);
        formData.append("password", password);

        fetch("book.php", {
          method: "POST",
          body: formData
        })
        .then(res => res.json())
        .then(result => {
          alert(result.message);
          if (result.success) btn.classList.remove("booked");
        });
        return;
      }

      // Nếu bàn trống → mở form đặt
      tableInput.value = btn.dataset.id;
      bookingForm.style.display = "block";
    });
  });

  cancelBtn.addEventListener("click", () => {
    bookingForm.style.display = "none";
  });

  // 🟢 Xử lý form đặt bàn
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append("action", "book");

    const res = await fetch("book.php", {
      method: "POST",
      body: formData
    });
    const result = await res.json();
    alert(result.message);

    if (result.success) {
      bookingForm.style.display = "none";
      const btn = document.querySelector(`.table[data-id="${tableInput.value}"]`);
      if (btn) btn.classList.add("booked");
      form.reset();
    }
  });
});
