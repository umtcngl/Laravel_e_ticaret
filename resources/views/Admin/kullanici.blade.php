@extends('layouts.admin')
@section('title', 'Kullanıcı Yönetimi')
@section('admin-content')
<div class="container mt-5">
    <h1>Kullanıcı Yönetimi</h1>
    <div class="mt-4">
        <table class="table table-xl table-striped table align-middle align-items-center">
            <thead>
                <tr class="table-dark">
                    <th>#</th>
                    <th>Kullanıcı Adı</th>
                    <th>Rol</th>
                    <th>Bakiye</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kullanicilar as $kullanici)
                <tr>
                    <td>{{ $kullanici->id }}</td>
                    <td>{{ $kullanici->kullaniciAdi }}</td>
                    <td>
                        <div class="position-relative">
                            <div class="dropup">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton_{{ $kullanici->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $kullanici->rol }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $kullanici->id }}">
                                    <a class="dropdown-item" href="#" onclick="selectRole('{{ $kullanici->id }}', 'admin')">Admin</a>
                                    <a class="dropdown-item" href="#" onclick="selectRole('{{ $kullanici->id }}', 'satici')">Satıcı</a>
                                    <a class="dropdown-item" href="#" onclick="selectRole('{{ $kullanici->id }}', 'musteri')">Müşteri</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.kullanici.rol', ['id' => $kullanici->id]) }}" method="POST" id="roleForm_{{ $kullanici->id }}" style="display: none;">
                            @csrf
                            <input type="hidden" name="rol" id="selectedRole_{{ $kullanici->id }}">
                        </form>
                    </td>

                    <td>
                        <form action="{{ route('admin.kullanici.bakiye.kaydet', ['id' => $kullanici->id]) }}" method="POST">
                            <div class="d-flex">
                                @csrf
                                <input type="number" class="form-control me-2" name="bakiye" value="{{ $kullanici->bakiye }}" style="width: 120px">
                                <button type="submit" class="btn btn-sm btn-outline-success">Bakiye Kaydet</button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.kullanici.sil', ['id' => $kullanici->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function selectRole(userId, role) {
        var currentRole = document.getElementById('dropdownMenuButton_' + userId).innerText.trim();
        if (currentRole.toLowerCase() === role.toLowerCase()) {
            return; // Aynı rol seçildiğinde submit yapma
        }
        // Seçilen rolü gizli input alanına ata
        document.getElementById('selectedRole_' + userId).value = role;
        // Formu gönder
        document.getElementById('roleForm_' + userId).submit();
    }
</script>
@endsection
