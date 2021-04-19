<div class="modal fade" tabindex="-1" role="dialog" id="apply-view-app">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="mainCharacter" class="col-form-label col-md-4">Main Character</label>
                    <div class="col-md-8">
                        <input id="mainCharacter" name="mainCharacter" class="form-control input-md"  type="text" disabled>
                    </div>
                    <label for="altCharacters" class="col-form-label col-md-4">Alt Character(s)</label>
                    <div class="col-md-8">
                        <textarea id="altCharacters" name="altCharacters" class="form-control input-md" rows="3"  disabled style="margin-top: 8px;"></textarea>
                    </div>
                    <label for="responses" class="col-form-label col-md-4">Responses</label>
                    <div class="col-md-8">
                        <textarea id="responses" name="responses" class="form-control input-md" rows="15"  disabled style="margin-top: 8px;"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ csrf_field() }}
            </div>
        </div>
    </div>
</div>