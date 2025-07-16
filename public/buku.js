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

$(function () {
    let currentSort = 'id_buku';
    let currentOrder = 'asc';
    let currentPage = 1;
    let currentSearch = '';
    let currentCategory = '';
    let currentDeleteId = null;
    let currentDeleteUrl = '';
    let currentRow = null;

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
        let tbody = $('#bukuTable tbody');
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <i class="bi bi-exclamation-circle me-2"></i>Tidak ada data buku yang ditemukan.
                    </td>
                </tr>
            `);
            return;
        }

        $.each(data, function (index, row) {
            let gambar = row.gambar
                ? `<img src="${row.gambar}" alt="${row.judul}" class="img-thumbnail" style="max-width: 50px;">`
                : 'Tidak ada gambar';

            tbody.append(`
                <tr data-id="${row.id_buku}">
                    <td class="text-center">${row.id_buku}</td>
                    <td><strong>${row.judul}</strong></td>
                    <td class="text-center">${row.id_kategori}</td>
                    <td class="text-center">${row.nama_kategori || '-'}</td>
                    <td class="text-center">${row.penerbit || '-'}</td>
                    <td class="text-center">${row.tahun_terbit || '-'}</td>
                    <td class="text-center">${row.stok || 0}</td>
                    <td class="text-center">${row.penulis || '-'}</td>
                    <td class="text-center">${gambar}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="${window.BASE_URL}admin/buku/edit/${row.id_buku}" class="btn btn-sm btn-warning me-1" title="Ubah">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id_buku}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function updatePagination(paginationHtml) {
        $('.pagination-container').remove();
        if (paginationHtml) {
            $('#bukuTable').after(`<div class="pagination-container mt-4">${paginationHtml}</div>`);
        }
    }

    function loadBukuData(page = 1, sortField = currentSort, sortOrder = currentOrder) {
        showLoading();
        $.ajax({
            url: window.BUKU_AJAX_URL,
            type: 'GET',
            data: {
                q: currentSearch,
                kategori_id: currentCategory,
                page: page,
                sort: sortField,
                order: sortOrder
            },
            dataType: 'json',
            success: function (response) {
                updateTable(response.data);
                updatePagination(response.pagination);
                updateSortIcons(sortField, sortOrder);
                hideLoading();
                if (response.csrf_test_name) {
                    csrfToken = response.csrf_test_name;
                    $('input[name=csrf_test_name]').val(csrfToken);
                }
            },
            error: function (xhr) {
                hideLoading();
                toastr.error('Gagal memuat data buku');
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    // Search
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        currentSearch = $('#search-box').val();
        currentCategory = $('#category-filter').val();
        currentPage = 1;
        loadBukuData();
    });

    // Filter kategori
    $('#category-filter').on('change', function () {
        currentCategory = $(this).val();
        currentPage = 1;
        loadBukuData();
    });

    // Reset
    $('#clear-sort-btn').on('click', function (e) {
        e.preventDefault();
        $('#search-box').val('');
        $('#category-filter').val('');
        currentSearch = '';
        currentCategory = '';
        currentSort = 'id_buku';
        currentOrder = 'asc';
        currentPage = 1;
        loadBukuData();
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
        loadBukuData(currentPage, currentSort, currentOrder);
    });

    // Pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const pageMatch = $(this).attr('href').match(/page=(\d+)/);
        currentPage = pageMatch ? parseInt(pageMatch[1]) : 1;
        loadBukuData(currentPage, currentSort, currentOrder);
    });

    // Tampilkan tombol clear jika ada input pada search
    $('#search-box').on('input', function () {
        $('#clear-search-btn').toggle($(this).val().length > 0);
    });

    // Klik tombol clear: kosongkan search, reload data
    $('#clear-search-btn').on('click', function () {
        $('#search-box').val('');
        $(this).hide();
        $('#search-form').trigger('submit');
    });

    // Saat halaman dimuat, tampilkan tombol clear jika search-box ada isinya
    $('#clear-search-btn').toggle($('#search-box').val().length > 0);

    // Load data awal
    loadBukuData();

    // Fungsi submit form tambah buku (add)
    $(document).on('submit', '#addForm', function (e) {
        e.preventDefault();
        showLoading();

        // Reset error
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let form = this;
        let formData = new FormData(form);

        // Tambahkan CSRF token jika ada
        if (csrfToken) {
            formData.append('csrf_test_name', csrfToken);
        }

        $.ajax({
            url: window.FORM_ACTION || $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message || 'Buku berhasil ditambahkan');
                    setTimeout(function () {
                        window.location.href = window.BASE_URL + 'admin/buku/kelola_buku';
                    }, 1200);
                }
                else if (response.errors) {
                    // Tampilkan error validasi
                    $.each(response.errors, function (field, msg) {
                        $(`[name="${field}"]`).addClass('is-invalid');
                        $(`#${field}-error`).text(msg);
                        toastr.error(msg);
                    });
                } else {
                    toastr.error(response.message || 'Gagal menambahkan buku');
                }
                if (response.csrf_test_name) {
                    csrfToken = response.csrf_test_name;
                    $('input[name=csrf_test_name]').val(csrfToken);
                }
                hideLoading();
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat menambah buku';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
                hideLoading();
            }
        });
    });

    // Fungsi submit form edit buku (edit)
    $(document).on('submit', '#editForm', function (e) {
        e.preventDefault();
        showLoading();

        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let form = this;
        let formData = new FormData(form);

        if (csrfToken) {
            formData.append('csrf_test_name', csrfToken);
        }

        $.ajax({
            url: $(form).attr('action') || window.location.href,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message || 'Buku berhasil diperbarui');
                    setTimeout(function () {
                        window.location.href = window.BASE_URL + 'admin/buku';
                    }, 1200);
                } else if (response.errors) {
                    $.each(response.errors, function (field, msg) {
                        $(`[name="${field}"]`).addClass('is-invalid');
                        $(`#${field}-error`).text(msg);
                        toastr.error(msg);
                    });
                } else {
                    toastr.error(response.message || 'Gagal memperbarui buku');
                }
                if (response.csrf_test_name) {
                    csrfToken = response.csrf_test_name;
                    $('input[name=csrf_test_name]').val(csrfToken);
                }
                hideLoading();
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat memperbarui buku';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
                hideLoading();
            }
        });
    });

    // Delete Book Functionality
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        currentDeleteId = $(this).data('id');
        currentRow = $(this).closest('tr');
        currentDeleteUrl = window.BASE_URL + 'admin/buku/delete/' + currentDeleteId;
        
        // Show confirmation modal
        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        modal.show();
    });

    // Confirm delete action
    $('#confirmDeleteBtn').on('click', function() {
        showLoading();
        
        $.ajax({
            url: currentDeleteUrl,
            type: 'POST',
            data: {
                csrf_test_name: $('input[name="csrf_test_name"]').val()
            },
            dataType: 'json',
            success: function(response) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                modal.hide();
                
                if (response.status === 'success') {
                    toastr.success(response.message || 'Buku berhasil dihapus');
                    
                    // Remove the row from table if delete was successful
                    if (currentRow) {
                        currentRow.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if table is empty now
                            if ($('#bukuTable tbody tr').length === 0) {
                                $('#bukuTable tbody').append(`
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="bi bi-exclamation-circle me-2"></i>Tidak ada data buku yang ditemukan.
                                        </td>
                                    </tr>
                                `);
                            }
                        });
                    } else {
                        // Reload the data if we can't remove the row directly
                        loadBukuData(currentPage, currentSort, currentOrder);
                    }
                } else {
                    toastr.error(response.message || 'Gagal menghapus buku');
                }
                
                // Update CSRF token if needed
                if (response.csrf_test_name) {
                    $('input[name="csrf_test_name"]').val(response.csrf_test_name);
                }
                
                hideLoading();
            },
            error: function(xhr) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                modal.hide();
                let errorMessage = 'Terjadi kesalahan saat menghapus buku';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
                hideLoading();
            }
        });
    });
});