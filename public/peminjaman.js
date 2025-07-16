// Konfigurasi Toastr
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).ready(function () {
    let currentSort = 'id';
    let currentOrder = 'asc';
    let currentPage = 1;
    let currentSearch = '';
    let deleteId = null;

    function showLoading() {
        $('#loading-overlay').fadeIn(200);
    }
    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }

    function updateSortIcons(sortField, sortOrder) {
        $('.sort-icon').removeClass('bi-arrow-up bi-arrow-down').addClass('bi-arrow-down-up');
        $(`.sort-link[data-sort="${sortField}"] .sort-icon`)
            .removeClass('bi-arrow-down-up')
            .addClass(sortOrder === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down');
    }

    function updateTable(data) {
        let tbody = $('#peminjamanTable tbody');
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="bi bi-exclamation-circle me-2"></i>Belum ada data peminjaman.
                    </td>
                </tr>
            `);
            return;
        }

        $.each(data, function (index, row) {
            let statusBadge = row.status === 'dipinjam'
                ? '<span class="badge bg-warning text-dark">dipinjam</span>'
                : '<span class="badge bg-success">kembali</span>';

            let aksi = '';
            if (row.status === 'dipinjam') {
                aksi = `
                    <button class="btn btn-success btn-sm kembalikan-btn" data-id="${row.id}" title="Kembalikan">
                        <i class="bi bi-arrow-return-left"></i>
                    </button>
                    <button class="btn btn-danger btn-sm hapus-btn" data-id="${row.id}" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
            } else {
                aksi = '<span class="text-muted">Selesai</span>';
            }

            tbody.append(`
                <tr>
                    <td class="text-center">${row.id}</td>
                    <td class="text-center">${row.id_anggota}</td>
                    <td><strong>${row.judul_buku}</strong></td>
                    <td class="text-center">${row.tanggal_pinjam || '-'}</td>
                    <td class="text-center">${row.tanggal_kembali || '-'}</td>
                    <td class="text-center">${statusBadge}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            ${aksi}
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function updatePagination(paginationHtml) {
        $('.pagination-container').html(paginationHtml || '');
    }

    function loadPeminjamanData(page = 1, sortField = currentSort, sortOrder = currentOrder) {
        showLoading();
        $.ajax({
            url: window.BASE_URL + 'admin/peminjaman/get-data',
            type: 'GET',
            data: {
                q: currentSearch,
                page: page,
                sort: sortField,
                order: sortOrder
            },
            dataType: 'json',
            success: function (response) {
                updateTable(response.data);
                updatePagination(response.pagination);
                updateSortIcons(currentSort, currentOrder);
                hideLoading();
            },
            error: function () {
                hideLoading();
                toastr.error('Gagal memuat data peminjaman');
            }
        });
    }

    // Search
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        currentSearch = $('#search-box').val();
        currentPage = 1;
        loadPeminjamanData();
    });

    // Reset
    $('#clear-sort-btn').on('click', function (e) {
        e.preventDefault();
        $('#search-box').val('');
        currentSearch = '';
        currentSort = 'id';
        currentOrder = 'asc';
        currentPage = 1;
        loadPeminjamanData();
    });

    // Sorting
    $(document).on('click', '.sort-link', function (e) {
        e.preventDefault();
        const sortField = $(this).data('sort');
        if (currentSort === sortField) {
            currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = sortField;
            currentOrder = 'asc';
        }
        loadPeminjamanData(currentPage, currentSort, currentOrder);
    });

    // Pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const pageMatch = $(this).attr('href').match(/page=(\d+)/);
        currentPage = pageMatch ? parseInt(pageMatch[1]) : 1;
        loadPeminjamanData(currentPage, currentSort, currentOrder);
    });

    // Kembalikan buku
    $(document).on('click', '.kembalikan-btn', function () {
        const id = $(this).data('id');
        if (confirm('Yakin ingin mengembalikan buku ini?')) {
            showLoading();
            $.post(window.BASE_URL + 'admin/peminjaman/kembalikan/' + id, function (res) {
                if (res.success) {
                    toastr.success('Buku berhasil dikembalikan!');
                    loadPeminjamanData(currentPage, currentSort, currentOrder);
                } else {
                    toastr.error('Gagal mengembalikan buku!');
                }
                hideLoading();
            }, 'json').fail(function() {
                hideLoading();
                toastr.error('Terjadi kesalahan saat mengembalikan buku!');
            });
        }
    });

    // Hapus peminjaman (pakai modal)
    $(document).on('click', '.hapus-btn', function () {
        deleteId = $(this).data('id');
    });

    $('#confirmDeleteBtn').on('click', function () {
        if (!deleteId) return;
        showLoading();
        $.ajax({
            url: window.BASE_URL + 'admin/peminjaman/hapus/' + deleteId,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    toastr.success(res.message);
                    loadPeminjamanData(currentPage, currentSort, currentOrder); // reload table
                    $('#deleteConfirmationModal').modal('hide');
                } else {
                    toastr.error(res.message);
                }
                hideLoading();
            },
            error: function() {
                hideLoading();
                toastr.error('Gagal menghapus data peminjaman!');
            }
        });
    });

    // Set tanggal pinjam default ke hari ini
    $('#tanggal_pinjam').val(new Date().toISOString().split('T')[0]);

    // Handler form submit
    $('#formAddPeminjaman').on('submit', function(e) {
        e.preventDefault();
        // Bersihkan error sebelumnya
        $('#formAddPeminjaman .form-control, #formAddPeminjaman .form-select').removeClass('is-invalid');
        $('#formAddPeminjaman .invalid-feedback').text('');

        $.ajax({
            url: window.BASE_URL + 'admin/peminjaman/add',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#formAddPeminjaman button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = window.BASE_URL + 'admin/peminjaman/kelola_peminjaman';
                    }, 1200);
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key).next('.invalid-feedback').text(value);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat menyimpan data');
            },
            complete: function() {
                $('#formAddPeminjaman button[type="submit"]').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan');
            }
        });
    });

    // Bersihkan error saat input berubah
    $('#formAddPeminjaman input, #formAddPeminjaman select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').text('');
    });

    // Edit data peminjaman (AJAX)
    $('#formEditPeminjaman').on('submit', function(e) {
        e.preventDefault();
        const id = $('input[name="id"]').val();
        $.ajax({
            url: window.BASE_URL + 'admin/peminjaman/edit/' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    toastr.success(res.message);
                    window.location.href = window.BASE_URL + 'admin/peminjaman/kelola_peminjaman';
                } else {
                    toastr.error(res.message);
                }
            }
        });
    });

    // Load data awal
    loadPeminjamanData();
});

// Tambahkan validasi untuk ID Buku
function validateBukuId(id_buku) {
    if (!id_buku) {
        return {
            success: false,
            message: "Validasi gagal",
            errors: {
                id_buku: "ID Buku tidak ditemukan"
            }
        };
    }
    return { success: true };
}