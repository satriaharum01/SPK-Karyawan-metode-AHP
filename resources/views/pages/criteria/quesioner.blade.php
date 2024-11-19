<x-app-layout>

    @slot('content')
    <div class="container-fluid py-2">
        <div class="row">

            <div class="card col-12 p-4">
                <h4 class="mb-2">List Kuesioner Kriteria</h4>
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Kuesioner
                    </button>
                </div>
                <table class="table border">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" style="width:5%">No</th>
                            <th scope="col" style="width:65%">Repsonden</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginatedData as $row)
                        <tr class="text-center">
                            <th scope="row">{{$loop->index + 1}}</th>
                            <td>{{$row['responden']}}</td>
                            <td>
                                <span class="badge text-white text-bg-warning edit-link" data-bs-toggle="modal" data-bs-target="#exampleModal3" data-name="{{ $row['responden'] }}">
                                    Edit
                                </span> |
                                <span class="badge text-white text-bg-danger delete-link" data-bs-toggle="modal" data-bs-target="#exampleModal2" data-name="{{ $row['responden'] }}">
                                    Delete
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>{{ $paginatedData->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>

    {{-- create quesioner  --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('criteria.quesioner.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kuesioner</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    @php
                        $i = 1;
                    @endphp
                    <label class="form-label align-self-end col-md-4">Responden</label>
                    <input type="text" name="name" class="form-control border px-2 mb-3" required>
                    <label class="form-label align-self-end col-md-4">Penilaian</label>
                    @foreach($criteria as $low)
                        @if(!next( $criteria))
                        @foreach($criteria as $row)
                            @if (($row->id != $low->id) && ($row->order >= $low->order)) 
                        <div class="align-content-center text-center d-flex justify-content-between mb-3">
                            <label class="form-label align-self-end col-md-4">{{$low->name}}</label>
                            <input type="hidden" name="responden[{{$i}}][id_criteria]" value="{{$low->id}}">
                            <select name="responden[{{$i}}][pilihan]" class="form-control border px-2 mx-2" required>
                                <option value="" selected disabled>-- Pilih</option>
                                @foreach($pilihan as $pil => $val)
                                <option value="{{$pil}}">{{$val}}</option>
                                @endforeach
                            </select>
                            <label class="form-label align-self-end col-md-4">{{$row->name}}</label>
                            <input type="hidden" name="responden[{{$i++}}][id_comparison_criteria]" value="{{$row->id}}">
                        </div>
                            @endif
                        @endforeach
                        @endif
                    @endforeach

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
            </form>
        </div>
    </div>
    </div>

    {{-- edit quesioner  --}}
    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="edit-form">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModal3Label">Edit Kuesioner</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    @php
                        $i = 1;
                    @endphp
                    <label class="form-label align-self-end col-md-4">Responden</label>
                    <input type="text" name="name" class="form-control border px-2 mb-3" required>
                    <label class="form-label align-self-end col-md-4">Penilaian</label>
                    @foreach($criteria as $low)
                        @if(!next( $criteria))
                        @foreach($criteria as $row)
                            @if (($row->id != $low->id) && ($row->order >= $low->order)) 
                        <div class="align-content-center text-center d-flex justify-content-between mb-3">
                            <label class="form-label align-self-end col-md-4">{{$low->name}}</label>
                            <input type="hidden" name="responden[{{$i}}][id_criteria]" value="{{$low->id}}">
                            <select name="responden[{{$i}}][pilihan]" class="form-control border px-2 mx-2" required>
                                <option value="" selected disabled>-- Pilih</option>
                                @foreach($pilihan as $pil => $val)
                                <option value="{{$pil}}">{{$val}}</option>
                                @endforeach
                            </select>
                            <label class="form-label align-self-end col-md-4">{{$row->name}}</label>
                            <input type="hidden" name="responden[{{$i++}}][id_comparison_criteria]" value="{{$row->id}}">
                        </div>
                            @endif
                        @endforeach
                        @endif
                    @endforeach
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- delete quesioner  --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
        <div class="modal-dialog">
            <form method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModal2Label">Hapus Kuesioner</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Hapus Kuesioner ?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endslot

</x-app-layout>

<script>
    $(document).ready(function() {
        $('.delete-link').click(function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var formAction = "{{ route('criteria.quesioner.destroy', ['name' => ':name']) }}";
            formAction = formAction.replace(':name', name);
            $('#exampleModal2 form').attr('action', formAction);
        });

        $('.edit-link').click(function(e) {
            var name = $(this).data('name');

            var url = "{{ route('criteria.quesioner.edit', ['name' => ':name']) }}";
            url = url.replace(':name', name);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    let i = 1;
                    console.log(response);
                    $.each(response,function(index,row){
                        jQuery("#edit-form input[name=name]").val(row.responden);
                        $(`#edit-form select[name="responden[${i}][pilihan]"]`).val(row.pilihan);
                        i++;
                    })
                },
                error: function(err) {
                    console.log(err);
                }
            });

            var formActionEdit = "{{ route('criteria.quesioner.update', ['name' => ':name']) }}";
            formActionEdit = formActionEdit.replace(':name', name);
            $('#exampleModal3 form').attr('action', formActionEdit);

        });
    });
</script>