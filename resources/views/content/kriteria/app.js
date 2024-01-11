document.querySelectorAll('#update_btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Ambil data dan set ke variabel
        var row = this.closest('tr'),
            kolom1 = row.querySelector('td:nth-child(2)')
        // Dan set ke form update
        // Contoh: set value
        document.getElementById('id').value = kolom1.textContent;
        // Set value untuk kolom2, kolom3, kolom4, dan kolom5 sesuai kebutuhan
    });
  });