<x-app-layout>

    @slot('content')
    <div class="container-fluid py-2">

        <!-- Konten Halaman -->
        <div class="row" id="hasil_container">
            <div class="card col-12 p-4">
                <div class="row">
                    <div class="col-6">
                        <h4 class="mb-4">Nilai Guru</h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="position: sticky; left: 0; top:0; background-color: white; z-index:1;">Guru</th>
                                @foreach ($subcriteriaList as $subcriteria)
                                <th style="position: sticky; top:0; background-color: white;">{{ $subcriteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td style="position: sticky; left: 0; background-color: white;">{{ $employee->name }}</td>
                                @foreach ($subcriteriaList as $subcriteria)
                                @php
                                $assessment = $assessments->where('employee_id', $employee->id)->where('subcriteria_id', $subcriteria->id)->first();
                                @endphp
                                <td class="text-center">{{ $assessment ? $assessment->value : '' }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                            <tr>
                                <th style="position: sticky; left: 0; bottom:0; background-color: white; z-index:1;">JUMLAH</th>
                                @foreach ($subcriteriaList as $subcriteria)
                                <td style="position: sticky; left: 0; bottom:0; background-color: white;" class="text-center">{{ $subcriteria->alternative_column_sum }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if (auth()->user()->hasRole('ADMIN'))
                <div class="col-12 text-end mt-2">
                    <a href="{{route('assessment.calculate')}}" type="button" class="btn btn-primary">
                        Hitung Nilai
                    </a>
                </div>
                @endif
            </div>

            <!-- Bagian Normalisasi Nilai dan Hasil -->
            <div class="card col-12 p-4 mt-4" id="normalisasi-section">
                <div class="row">
                    <div class="col-6">
                        <h4 class="mb-4">Normalisasi Nilai dan Hasil</h4>
                    </div>
               

                <!-- Tombol Print khusus untuk Normalisasi Nilai dan Hasil -->
                <div class="text-end mb-3">
                    <button class="btn btn-primary" onclick="printNormalisasi()">Print Normalisasi Nilai dan Hasil</button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="position: sticky; left: 0; top:0; background-color: white; z-index:1;">Guru</th>
                                @foreach ($subcriteriaList as $subcriteria)
                                <th style="position: sticky; top:0; background-color: white;">{{ $subcriteria->name }}</th>
                                @endforeach
                                <th style="position: sticky; right: 0; top:0; background-color: white; z-index:1;">Hasil Nilai</th>
                                <th style="position: sticky; right: 0; top:0; background-color: white; z-index:1;">Rangking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td style="position: sticky; left: 0; background-color: white;">{{ $employee->name }}</td>
                                @foreach ($subcriteriaList as $subcriteria)
                                @php
                                $assessment = $assessments->where('employee_id', $employee->id)->where('subcriteria_id', $subcriteria->id)->first();
                                @endphp
                                <td class="text-center">{{ $assessment ? number_format($assessment->normalization_value, 3) : '' }}</td>
                                @endforeach
                                <td style="position: sticky; right: 0; background-color: white;" class="text-center">{{number_format($employee->value,5)}}</td>
                                <td style="position: sticky; right: 0; background-color: {{ $employee->rangking == 1 ? 'red' : ($employee->rangking == 2 ? 'yellow' : ($employee->rangking == 3 ? 'green' : 'white')) }}" class="text-center">{{$employee->rangking}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>

            </div>
        </div>

    </div>
    @endslot

</x-app-layout>

<!-- JavaScript untuk Print Normalisasi Nilai dan Hasil -->
<script>
    function printNormalisasi() {
        var printContents = document.getElementById('normalisasi-section').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<!-- Tambahkan CSS untuk media print -->
<style>
    @media print {
        /* Menghilangkan elemen lain yang tidak perlu saat print normalisasi */
        body * {
            visibility: hidden;
        }
        #normalisasi-section, #normalisasi-section * {
            visibility: visible;
        }
        #normalisasi-section {
            position: absolute;
            left: 0;
            top: 0;
        }
        /* Menghilangkan tombol print saat cetak */
        button {
            display: none;
        }
    }
</style>
