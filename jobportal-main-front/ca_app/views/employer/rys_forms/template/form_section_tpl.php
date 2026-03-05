<div class="formwraper section" data-section-id="{{sectionId}}">
    <div class="titlehead">
        <a data-toggle="collapse" 
           data-parent="#form-sections"
           href="#seccion-{{sectionId}}"
           style="color: #ffffff;">
          Sección
        </a>

        <a href="#"
           class="del-section pull-right" 
           onclick="event.preventDefault();$(this).closest('.section').remove();"
           style="color: #ffffff;">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
    <div id="seccion-{{sectionId}}" class="panel-collapse collapse formint">
      <div class="jobdescription" style="border-top:0px;">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                <label class="input-group-addon">Sección<span> *</span></label>
                <input name="sections[{{sectionId}}][name]" 
                       type="text" 
                       class="form-control" 
                       placeholder="Sección" value="{{sectionName}}" 
                       maxlength="150"
                       required="true">
                </div>
            </div>
            <br />
            <div class="form-questions" style="padding:15px 5px;"></div>

            <a href="#"
               class="add-question" style="font-size:14px;">
               <i class="glyphicon glyphicon-plus"></i>
               Agregar pregunta
            </a>
        </div>
      </div>
    </div>
</div>