@extends('admin.layout.master')
@section('title', 'List Client')
@section('content')
    <h1>Danh Sách Clients</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Thêm Client</button>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Token</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr id="client-row-{{ $client->id }}">
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->api_token }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">Sửa</a>
                        <form method="POST" action="{{ route('clients.destroy', $client->id) }}" style="display: inline-block;" class="delete-client-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-client-btn" data-id="{{ $client->id }}">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Thêm Client -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addClientForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addClientModalLabel">Thêm Client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Thông báo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Thêm client mới qua AJAX
            $('#addClientForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('clients.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#addClientModal').modal('hide');
                        const client = response.data;
                        const newRow = `
                            <tr id="client-row-${client.id}">
                                <td>${client.id}</td>
                                <td>${client.name}</td>
                                <td>${client.email}</td>
                                <td>${client.api_token}</td>
                                <td>
                                    <a href="/admin/clients/${client.id}/edit" class="btn btn-warning">Sửa</a>
                                    <form method="POST" action="/admin/clients/${client.id}" style="display: inline-block;" class="delete-client-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger delete-client-btn" data-id="${client.id}">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        $('table tbody').append(newRow);
                        showToast('Client được thêm thành công!');
                    },
                    error: function (xhr) {
                        let errorMessage = 'Có lỗi xảy ra.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        showToast(errorMessage, 'danger');
                    }
                });
            });

            // Xóa client
            $(document).on('click', '.delete-client-btn', function () {
                const clientId = $(this).data('id');
                const button = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa client này không?')) {
                    $.ajax({
                        url: `/admin/clients/${clientId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function () {
                            $(`#client-row-${clientId}`).remove();
                            showToast('Client đã được xóa thành công!');
                        },
                        error: function (xhr) {
                            let errorMessage = 'Có lỗi xảy ra khi xóa client.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            showToast(errorMessage, 'danger');
                        }
                    });
                }
            });

            // Hiển thị thông báo
            function showToast(message, type = 'success') {
                const toast = $('#toast');
                toast.find('.toast-body').html(message);
                toast.removeClass('bg-success bg-danger').addClass(type === 'success' ? 'bg-success' : 'bg-danger');
                const bootstrapToast = new bootstrap.Toast(toast, { autohide: true, delay: 3000 });
                bootstrapToast.show();
            }
        });
    </script>
@endsection
