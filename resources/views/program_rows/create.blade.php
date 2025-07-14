@extends('layouts.app')

@section('title')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Baris Program: <strong>{{ $program->program_name }}</strong></h1>

        <a href="{{ route('program_rows.index', $program->id) }}" class="btn btn-secondary mb-3">← Kembali</a>

        <form action="{{ route('program_rows.store', $program) }}" method="POST" id="row-form">
            @csrf

            <div id="row-container" class="table-responsive" style="overflow-x: auto;">
                <!-- Initial Row -->
                <div class="card mb-3 shadow-sm border-primary" style="min-width: 2000px;">
                    <div class="card-body row g-3">
                        <div class="col-md-2">
                            <label for="inisiatif[]">Inisiatif</label>
                            <textarea name="inisiatif[]" class="form-control expandable" rows="2"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label for="peneraju_utama[]">Peneraju Utama</label>
                            <textarea name="peneraju_utama[]" class="form-control expandable" rows="2"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label for="tahun_mula_siap[]">Tahun Mula/Siap</label>
                            <input type="text" name="tahun_mula_siap[]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="petunjuk_prestasi[]">Petunjuk Prestasi</label>
                            <textarea name="petunjuk_prestasi[]" class="form-control expandable" rows="2"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label for="pencapaian[]">Pencapaian</label>
                            <textarea name="pencapaian[]" class="form-control expandable" rows="2"></textarea>
                        </div>
                        <div class="col-md-1">
                            <label for="status[]">Status</label>
                            <select name="status[]" class="form-select" required>
                                <option value="Belum Mula">Belum Mula</option>
                                <option value="Dalam Pelaksanaan">Dalam Pelaksanaan</option>
                                <option value="Projek Sakit">Projek Sakit</option>
                                <option value="Ditangguh">Ditangguh</option>
                                <option value="Digugurkan">Digugurkan</option>
                                <option value="Tercapai">Tercapai</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="completion[]">Selesai (%)</label>
                            <input type="number" name="completion[]" class="form-control" min="0" max="100" value="0" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()">➕ Tambah Baris Baru</button>
            <br>
            <button type="submit" class="btn btn-success">💾 Simpan Semua</button>
        </form>
    </div>

<script>
    let activeTextarea = null;

    function addRow() {
        const container = document.getElementById('row-container');
        const row = container.firstElementChild.cloneNode(true);

        row.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.tagName === 'SELECT') {
                input.value = 'Belum Mula';
            } else if (input.type === 'number') {
                input.value = 0;
            } else {
                input.value = '';
            }
        });

        // Re-bind popup handler for new textareas
        setPopupListeners(row);
        container.appendChild(row);
    }

    function setPopupListeners(context = document) {
        context.querySelectorAll('.expandable').forEach(textarea => {
            textarea.addEventListener('click', function () {
                activeTextarea = this;
                document.getElementById('popup-textarea').value = this.value;
                document.getElementById('textarea-popup').style.display = 'block';
                document.getElementById('popup-backdrop').style.display = 'block';
                document.getElementById('popup-textarea').focus();
            });
        });
    }

    function closePopup() {
        document.getElementById('textarea-popup').style.display = 'none';
        document.getElementById('popup-backdrop').style.display = 'none';
    }

    function savePopupContent() {
        if (activeTextarea) {
            activeTextarea.value = document.getElementById('popup-textarea').value;
        }
        closePopup();
    }

    // Attach listeners on page load
    document.addEventListener('DOMContentLoaded', () => {
        setPopupListeners();
    });
</script>
@endsection


<!-- Popup Modal for Editing Textarea -->
<div id="textarea-popup" style="display: none; position: fixed; top: 10%; left: 50%; transform: translateX(-50%); width: 70%; z-index: 1050; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.5); padding: 20px;">
    <h5>Edit Field</h5>
    <textarea id="popup-textarea" class="form-control" rows="10"></textarea>
    <div class="mt-3 text-end">
        <button class="btn btn-success" onclick="savePopupContent()">Simpan</button>
        <button class="btn btn-secondary" onclick="closePopup()">Batal</button>
    </div>
</div>

<!-- Backdrop -->
<div id="popup-backdrop" style="display: none; position: fixed; top: 0; left: 0; height: 100vh; width: 100vw; background: rgba(0, 0, 0, 0.4); z-index: 1040;" onclick="closePopup()"></div>