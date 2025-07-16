console.log('app.js loaded');

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

$(document).ready(function() {
    // =========================
    // Artikel Table & Actions
    // =========================
    var currentSortField = 'id';
    var currentSortOrder = 'asc';
    var currentDeleteUrl = '';
    var currentRow = null;

    function showLoading() {
        $('#loading-overlay').fadeIn(200);
    }
    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }
    function updateSortIcons(sortField, sortOrder) {
        $('.sort-icon').removeClass('bi-arrow-up bi-arrow-down').addClass('bi-arrow-down-up');
       
    }
    function loadData(page = 1, sortField = currentSortField, sortOrder = currentSortOrder) {
        if (!$('#artikelTable').length) return; // Hanya jalankan di halaman index
        showLoading();
        var q = $('#search-box').val();
        var kategori_id = $('#category-filter').val();

        $.ajax({
            url: window.BASE_URL + "admin/ajax/get-data",
            method: "GET",
            data: {
                q: q,
                kategori_id: kategori_id,
                page: page,
                sort: sortField,
                order: sortOrder
            },
            dataType: "json",
            success: function(response) {
                var data = response.data || [];
                if (Array.isArray(data) && data.length > 0) {
                    var tableBody = "";
                    for (var i = 0; i < data.length; i++) {
                        var row = data[i];
                        tableBody += '<tr>';
                        tableBody += '<td class="text-center">' + row.id + '</td>';
                        tableBody += '<td><strong>' + row.judul + '</strong><p class="mb-0"><small>' + (row.isi ? row.isi.substr(0, 50) + '...' : '') + '</small></p></td>';
                        tableBody += '<td class="text-center">' + row.id_kategori + '</td>';
                        tableBody += '<td class="text-center">' + (row.nama_kategori ? row.nama_kategori : 'Tidak ada kategori') + '</td>';
                        tableBody += '<td class="text-center">' + (row.tanggal ? new Date(row.tanggal).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '') + '</td>';
                        tableBody += '<td class="text-center">';
                        if (row.status === 'publish') {
                            tableBody += '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Publish</span>';
                        } else if (row.status === 'draft') {
                            tableBody += '<span class="badge bg-warning text-dark"><i class="bi bi-pencil-fill me-1"></i>Draft</span>';
                        } else {
                            tableBody += '<span class="badge bg-secondary"><i class="bi bi-question-circle-fill me-1"></i>' + (row.status ? row.status.charAt(0).toUpperCase() + row.status.slice(1) : '') + '</span>';
                        }
                        tableBody += '</td>';
                        tableBody += '<td class="text-center">';
                        tableBody += '<div class="d-flex justify-content-center gap-2">';
                        tableBody += '<a href="' + window.BASE_URL + 'admin/ajax/view/' + row.id + '" class="btn btn-secondary btn-sm me-1" title="Lihat"><i class="bi bi-eye-fill"></i></a>';
                        tableBody += '<a href="' + window.BASE_URL + 'admin/ajax/edit/' + row.id + '" class="btn btn-sm btn-warning me-1" title="Ubah"><i class="bi bi-pencil-fill"></i></a>';
                        tableBody += '<a href="' + window.BASE_URL + 'admin/ajax/delete/' + row.id + '" class="btn btn-sm btn-danger delete-btn" title="Hapus"><i class="bi bi-trash-fill"></i></a>';
                        tableBody += '</div></td>';
                        tableBody += '</tr>';
                    }
                    $('#artikelTable tbody').html(tableBody);

                    // Update pagination
                    if (response.pagination) {
                        $('#pagination').html(response.pagination);
                    } else {
                        $('#pagination').html('');
                    }

                    // Update CSRF token jika ada
                    if (response.csrf_test_name) {
                        if ($('input[name=csrf_test_name]').length) {
                            $('input[name=csrf_test_name]').val(response.csrf_test_name);
                        } else {
                            $('#search-form').append('<input type="hidden" name="csrf_test_name" value="' + response.csrf_test_name + '">');
                        }
                    }

                    updateSortIcons(sortField, sortOrder);
                } else {
                    $('#artikelTable tbody').html('<tr><td colspan="7" class="text-center py-4"><i class="bi bi-exclamation-circle me-2"></i>Belum ada data artikel.</td></tr>');
                }
            },
            error: function(xhr) {
                $('#artikelTable tbody').html('<tr><td colspan="7" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal memuat data</td></tr>');
                toastr.error('Gagal memuat data. Silakan coba lagi.', 'Error');
            },
            complete: function() {
                hideLoading();
            }
        });
    }

    // Sorting
    $(document).on('click', '.sort-link', function(e) {
        e.preventDefault();
        var sortField = $(this).data('sort');
        var sortOrder = currentSortOrder;
        if (currentSortField === sortField) {
            sortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            sortOrder = 'asc';
        }
        currentSortField = sortField;
        currentSortOrder = sortOrder;
        loadData(1, sortField, sortOrder);
    });

    // Search
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        loadData(1, currentSortField, currentSortOrder);
    });

    // Filter kategori
    $('#category-filter').on('change', function() {
        loadData(1, currentSortField, currentSortOrder);
    });

    // Pagination AJAX
    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        const url = new URL(href, window.location.origin);
        const page = url.searchParams.get("page") || 1;
        loadData(page, currentSortField, currentSortOrder);
    });

    // Delete button click - show modal
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        currentDeleteUrl = $(this).attr('href');
        currentRow = $(this).closest('tr');
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete button in modal
    $('#confirmDeleteBtn').on('click', function() {
        $('#deleteConfirmationModal').modal('hide');
        if (currentDeleteUrl && currentRow) {
            $.ajax({
                url: currentDeleteUrl,
                method: 'POST',
                data: {
                    csrf_test_name: $('input[name=csrf_test_name]').val()
                },
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    // Update CSRF token jika ada
                    if (response.csrf_test_name) {
                        if ($('input[name=csrf_test_name]').length) {
                            $('input[name=csrf_test_name]').val(response.csrf_test_name);
                        } else {
                            $('#search-form').append('<input type="hidden" name="csrf_test_name" value="' + response.csrf_test_name + '">');
                        }
                    }
                    if (response.status === 'success') {
                        // Hapus baris dari tabel dengan animasi
                        currentRow.addClass('table-danger');
                        currentRow.fadeOut(400, function() {
                            $(this).remove();
                            toastr.success(response.message, 'Sukses');
                            // Jika tabel kosong, reload data
                            if ($('#artikelTable tbody tr').length === 0) {
                                loadData();
                            }
                        });
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                },
                error: function(xhr) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        toastr.error(response.message || 'Terjadi kesalahan saat menghapus artikel.', 'Error');
                    } catch (e) {
                        toastr.error('Terjadi kesalahan saat menghapus artikel.', 'Error');
                    }
                },
                complete: function() {
                    hideLoading();
                    currentDeleteUrl = '';
                    currentRow = null;
                }
            });
        }
    });

    // Load data pertama kali (hanya jika tabel ada)
    if ($('#artikelTable').length) {
        loadData();
    }

    // =========================
    // Handler Form Tambah/Edit
    // =========================

    // Tambah Artikel
    if ($('#addArtikelForm').length) {
        $('#addArtikelForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            var submitBtn = form.find('button[type="submit"]');
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');
            $('#form-message').html('').removeClass('alert alert-success alert-danger');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...');
            $.ajax({
                url: form.attr('action') || window.BASE_URL + "admin/ajax/add",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.csrf_test_name) {
                        $('input[name=csrf_test_name]').val(response.csrf_test_name);
                    }
                    if (response.status === 'success') {
                        $('#form-message').addClass('alert alert-success').html('<i class="fas fa-check-circle"></i> ' + response.message + ' Redirecting...');
                        form[0].reset();
                        setTimeout(function() {
                            window.location.href = window.BASE_URL + "admin/ajax/admin_index";
                        }, 1500);
                    } else {
                        $('#form-message').addClass('alert alert-danger').html('<i class="fas fa-exclamation-circle"></i> ' + response.message);
                        if (response.errors) {
                            for (var field in response.errors) {
                                form.find('#' + field).addClass('is-invalid');
                                form.find('#' + field + '-error').text(response.errors[field]);
                            }
                        }
                    }
                },
                error: function(xhr) {
                    $('#form-message').addClass('alert alert-danger').html('<i class="fas fa-exclamation-circle"></i> Terjadi kesalahan server.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Kirim');
                }
            });
        });
    }

    // Edit Artikel
    if ($('#editArtikelForm').length) {
        $('#editArtikelForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            var submitBtn = form.find('button[type="submit"]');
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');
            $('#form-message').html('').removeClass('alert alert-success alert-danger');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...');
            var id = form.find('input[name="id"]').val() || '';
            var actionUrl = form.attr('action') || window.BASE_URL + "admin/ajax/update/" + id;
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.csrf_test_name) {
                        $('input[name=csrf_test_name]').val(response.csrf_test_name);
                    }
                    if (response.status === 'success') {
                        $('#form-message').addClass('alert alert-success').html('<i class="fas fa-check-circle"></i> ' + response.message + ' Redirecting...');
                        setTimeout(function() {
                            window.location.href = window.BASE_URL + "admin/ajax";
                        }, 1500);
                    } else {
                        $('#form-message').addClass('alert alert-danger').html('<i class="fas fa-exclamation-circle"></i> ' + response.message);
                        if (response.errors) {
                            for (var field in response.errors) {
                                form.find('#' + field).addClass('is-invalid');
                                form.find('#' + field + '-error').text(response.errors[field]);
                            }
                        }
                    }
                },
                error: function(xhr) {
                    $('#form-message').addClass('alert alert-danger').html('<i class="fas fa-exclamation-circle"></i> Terjadi kesalahan server.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                }
            });
        });
    }

    // Tampilkan tombol clear jika ada input pada search
    $('#search-box').on('input', function() {
        $('#clear-search-btn').toggle($(this).val().length > 0);
    });

    // Klik tombol clear: kosongkan search, reload data
    $('#clear-search-btn').on('click', function() {
        $('#search-box').val('');
        $(this).hide();
        $('#search-form').trigger('submit');
    });

    // Saat halaman dimuat, tampilkan tombol clear jika search-box ada isinya
    if ($('#search-box').val().length > 0) {
        $('#clear-search-btn').show();
    } else {
        $('#clear-search-btn').hide();
    }

    // Reset sorting, filter, dan pencarian
    $('#clear-sort-btn').on('click', function(e) {
        e.preventDefault();
        // Reset input pencarian
        $('#search-box').val('');
        // Reset filter kategori
        $('#category-filter').val('');
        // Reset urutan sorting ke default
        currentSortField = 'id';
        currentSortOrder = 'asc';
        // Reload data dengan parameter default
        loadData(1, currentSortField, currentSortOrder);
    });

    function loadArticles(page = 1) {
        var kategoriSlug = window.KATEGORI_SLUG || '';
        $.ajax({
            url: window.BASE_URL + "artikel" + (kategoriSlug ? '/kategori/' + kategoriSlug : ''),
            type: 'GET',
            data: { page: page },
            dataType: 'json',
            beforeSend: function() {
                $('#artikel-container').html('<div class="text-center my-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            success: function(response) {
                var html = '';
                if (response.artikel && response.artikel.length > 0) {
                    response.artikel.forEach(function(row) {
                        html += `<article class="entry mb-4 artikel-item">
                            <h2>
                                <a href="${window.BASE_URL}artikel/${row.slug}">
                                    ${row.judul}
                                </a>
                            </h2>
                            <p>Kategori: ${row.nama_kategori || 'Tidak ada kategori'}</p>`;
                        if (row.gambar) {
                            html += `<img src="${window.BASE_URL}gambar/${row.gambar}" 
                                 alt="${row.judul}" 
                                 class="img-fluid mb-3" style="max-width:150px; height:auto;">`;
                        }
                        html += `<p>${row.isi.substring(0, 200)}...</p>
                            <small class="text-muted">Diterbitkan pada: ${new Date(row.tanggal).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})}</small>
                        </article>
                        <hr class="divider">`;
                    });
                    html += `<div id="pagination-container">${response.pagination}</div>`;
                } else {
                    html = '<article class="entry"><h2>Belum ada artikel dalam kategori ini.</h2></article>';
                }
                $('#artikel-container').html(html);
                setupPaginationEvents();
            },
            error: function() {
                $('#artikel-container').html('<div class="alert alert-danger">Gagal memuat artikel. Silakan coba lagi.</div>');
            }
        });
    }

    function setupPaginationEvents() {
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadArticles(page);
            $('html, body').animate({
                scrollTop: $('#artikel-container').offset().top - 20
            }, 500);
        });
    }

    setupPaginationEvents();
});