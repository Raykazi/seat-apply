<div class="modal fade" tabindex="-1" role="dialog" id="apply-edit-question">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Question</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="questionNumber" class="col-form-label col-md-4">Question Number</label>
                    <div class="col-md-8">
                        <input id="questionNumber" name="questionNumber" class="form-control" value="{{ count($questions)+1 }}" type="number">
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="questionInput" class="col-form-label col-md-4">Question</label>
                    <div class="col-md-8">
                        <input id="questionInput" name="questionInput" class="form-control" value="" type="text">
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="questionRequired" class="col-form-label col-md-4">Required</label>
                    <div class="col-md-8">
                        <select id="questionRequired" name="questionRequired" class="form-control">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="questionType" class="col-form-label col-md-4">Answer Type</label>
                    <div class="col-md-8">
                        <select id="questionType" name="questionType" class="form-control">
                            <option value="select">Dropdown</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text" >Textbox</option>
                            <option value="multiline">Multiline Textbox</option>
                        </select>
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="questionOptions" class="col-form-label col-md-4">Choices</label>
                    <div class="col-md-8">
                        <input id="questionOptions" name="questionOptions" value="" type="text" class="form-control">
                        <p class="form-text text-muted mb-0">Use comma-separated values here.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">
                    <i class="fa fa-plus"></i> Update Question
                </button>
                {{ csrf_field() }}
            </div>
        </div>
    </div>
</div>