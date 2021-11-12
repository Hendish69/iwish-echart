<div class="modal fade" id="pdfmodal" tabindex="-1" role="dialog" aria-labelledby="AD21ModalLabel" aria-hidden="true" style="visibility: visible">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content bg-light">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMdTitle" value="">AD 2.2 AERODROME GEOGRAPHICAL AND ADMINISTRATIVE DATA</h5>
            </div>
            <div class="modal-body">
            {{ csrf_field() }}
                <div class="ad21_info">
                    <div class ="form-group row mb-1 mt-1">
                    <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="ad21" id="latitude" value="">
                        </div>
                    <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="ad21" id="longitude" value="">
                        </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="dir_fromcity" class="col-form-label">Direction and Distance from (city)</label>
                            <input type="text" class="form-control" name="ad21" id="dir_fromcity">
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="elev" class="col-sm-2 col-form-label">Elev(ft)</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="Elev">
                            </div>
                        <label for="rtemp" class="col-sm-2 col-form-label">Temp(°C)</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="rtemp">
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="magvar" class="col-sm-5 col-form-label">MAG VAR/Annual change</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="ad21" id="magvar">
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="mv-data" class="col-sm-2 col-form-label">Year</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name="ad21" id="mv-data">
                            </div>
                        <label for="geoid" class="col-sm-2 col-form-label">Geoid</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="geoid">
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="ad-operator" class="col-sm-2 col-form-label">AD Operator</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="ad21" id="ad-operator"></textarea>
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="ad21" id="address"></textarea>
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="telephone" class="col-sm-2 col-form-label">Telephone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="telephone">
                            </div>
                        <label for="telefax" class="col-sm-2 col-form-label">Telefax</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="telefax">
                            </div>
                        <label for="telex" class="col-sm-2 col-form-label">Telex</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="telex">
                            </div>
                        <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                            <div class="col-sm-4">
                                <input type="email" class="form-control" name="ad21" id="email">
                            </div>
                        <label for="afs" class="col-sm-2 col-form-label">AFS</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="afs">
                            </div>
                    {{-- </div>
                    <div class ="form-group row mb-1 mt-1"> --}}
                        <label for="website" class="col-sm-2 col-form-label">Website</label>
                            <div class="col-sm-4">
                                <input type="url" class="form-control" name="ad21" id="website">
                            </div>
                        <label for="traffic" class="col-sm-2 col-form-label">Traffic</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ad21" id="traffic">
                            </div>
                    </div>
                    <div class ="form-group row mb-1 mt-1">
                        <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ad21" id="remarks">
                                {{-- <textarea class="form-control" id="remarks"> --}}
                            </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn-22close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-save" value="add">Save</button>
            </div>
            {{-- akhir modal content --}}
        </div>
    </div>
</div>
<script>
$(document).on('click', '#btn-22close', function (e) {
    e.preventDefault();
    var vol = document.getElementById("AirportModal");
    vol.style.visibility = 'visible';
    $('#AirportModal').modal('show');
});
</script>
