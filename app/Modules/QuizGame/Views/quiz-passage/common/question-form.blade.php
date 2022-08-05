
<h3 class="text-center">Create Questions For Quiz</h3>

<div id="questionDiv0">
    <div class="form-group">
        <label class="col-sm-2 control-label">Question 1 :</label>
        <div class="col-sm-6">
            <input type="text"
                   class="form-control" name="quiz[0][question]" required autocomplete="off" placeholder="Enter Quiz Question" value="" />
        </div>

        <div class="col-sm-2">
            <button type="button" class="form-control btn-success btn-xs " id="addMore" >Add More Question</button>
        </div>
    </div>

    <div class="form-group">

        <div class="form-horizontal">
            <div class="col-md-4 "style="margin-left: 178px;">
                <label class="control-label">option A</label>
                <input  class="form-control " name="quiz[0][option_a]" required autocomplete="off" placeholder="Enter Option A " value="" />
            </div>

            <div class="col-md-4">
                <label class="control-label">option B</label>
                <input  class="form-control " name="quiz[0][option_b]" required autocomplete="off" placeholder="Enter Option B" value="" />
            </div>
        </div>

        <div class="form-horizontal">
            <div class="col-md-4 " style="margin-left: 178px;">
                <label class=" control-label">option C</label>
                <input  class="form-control " name="quiz[0][option_c]" required autocomplete="off" placeholder="Enter Option C" value="" />
            </div>

            <div class="col-md-4">
                <label class=" control-label">option D</label>
                <input  class="form-control " name="quiz[0][option_d]" required autocomplete="off" placeholder="Enter Option D" value="" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-2 control-label">Correct Answer</label>
            <div class="col-md-6">
                <select class="form-control" name="quiz[0][correct_answer]" id="correct_answer">
                    <option value="">Select Correct Answer</option>
                    <option value="option_a">Option A</option>
                    <option value="option_b">Option B</option>
                    <option value="option_c">Option C</option>
                    <option value="option_d">Option D</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Point :</label>
        <div class="col-sm-2">
            <input type="number" min="0" class="form-control " name="quiz[0][points]" required autocomplete="off" placeholder="Enter point" value="" />
        </div>
    </div>

    <div class="form-group ">
        <label  class="col-sm-2 control-label">Question Is Active</label>
        <div class="col-sm-6">
            <input type="checkbox" class="form-check-input " value="1"  id="question_is_active" name="quiz[0][question_is_active]" checked/>
        </div>
    </div>
</div>






