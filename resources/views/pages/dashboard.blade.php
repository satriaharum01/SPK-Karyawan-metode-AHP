<x-app-layout>
  @slot('content')
  <div class="container-fluid py-2">
    <div class="row">
      <div class="card col-12 p-4">
        <h4 class="mb-2">Karyawan Terbaik</h4>
        <div class="row">
          @foreach ($topEmployees as $index => $employee)
          <div class="col-md-4">
            <div class="card mb-3" 
                 style="background-color: {{ $index == 0 ? '#FF0000' : ($index == 1 ? '#008000' : '#CCAC01') }};">
              <div class="card-body">
                <h5 class="card-title" style="color: #FFFFFF;">
                  Peringkat {{ $index + 1 }}: {{ $employee->name }}
                </h5>
                <p class="card-text" style="color: #FFFFFF;">Nilai: {{ $employee->value }}</p>
                <!-- Tambahkan informasi lebih lanjut tentang karyawan jika diperlukan -->
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endslot
</x-app-layout>
