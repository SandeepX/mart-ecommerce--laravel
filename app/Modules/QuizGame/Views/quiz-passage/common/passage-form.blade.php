<div class="form-group">
    <label class="col-sm-2 control-label">Passage Title</label>
    <div class="col-sm-6">
        <input id="passage_title"
               class="form-control "
               name="passage_title"
               required
               autocomplete="off"
               placeholder="Enter Passage Title"
               value="{{old('passage_title')}}"
        />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Passage</label>
    <div class="col-sm-6">
        <textarea
            id="text"
            class="form-control summernote"
            name="passage"
            required
            autocomplete="off"
            placeholder="Enter Passage "
            value="{{old('passage')}}" >
            {{old('passage')}}
        </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Quiz Date</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text'
                   autocomplete="off"
                   class="form-control"
                   value=""
                   name="quiz_passage_date"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
        </div>

    </div>
</div>

<div class="form-group ">
    <label  class="col-sm-2 control-label">Passage Is Active</label>
    <div class="col-sm-6">
        <input type="checkbox"
               class="form-check-input"
               value="1"
               id="passage_is_active"
               name="passage_is_active"
               checked/>
    </div>
</div>













