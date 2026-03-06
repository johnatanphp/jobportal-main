<div data-question-id="{{questionId}}"
     class="form-question" 
     style="position: relative; border-bottom: 1px solid #ccc;padding: 15px 0px;">

    <div style="background: #eee;padding:10px;">
        <table width="100%">
            <tr>
                <td>
                    <h5>
                        <b>
                            <a data-toggle="collapse" 
                               data-parent="#form-questions"
                               href="#question-{{questionId}}" >
                                Pregunta
                            </a>
                        </b>
                    </h5>
                </td>
                <td width="20">
                    <label style="padding-right: 4px;">
                        <a href="#" 
                           onclick="event.preventDefault();$(this).closest('.form-question').remove();">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>
                    </label>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <div id="question-{{questionId}}" class="panel-collapse collapse">
    <div class="row">
        <div class="col-md-6">
          <input type="text" 
                 name="sections[{{sectionId}}][question][{{questionId}}][name]" 
                 class="form-control question" 
                 placeholder="Pregunta" 
                 autocomplete="off"
                 value="{{questionName}}"
                 required="true">
        </div>
        <div class="col-md-6">
            <select name="sections[{{sectionId}}][question][{{questionId}}][type_question]" 
                  class="form-control type-question">

                <option value="text" {{#text}} selected="selected" {{/text}}>Texto</option>
                <option value="number" {{#number}} selected="selected" {{/number}}>Número</option>
                <option value="textarea" {{#textarea}} selected="selected" {{/textarea}}>Parrafo</option>
                <option value="radio" {{#radio}} selected="selected" {{/radio}}>Opción multiple</option>
                <option value="checkbox" {{#checkbox }} selected="selected" {{/checkbox}}>Casilla de verificación</option>
                <option value="dropdown" {{#dropdown}} selected="selected" {{/dropdown}}>Lista desplegable</option>
                <option value="file" {{#file}} selected="selected" {{/file}}>Archivo</option>

            </select>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-6">
          <input type="number" 
                 name="sections[{{sectionId}}][question][{{questionId}}][score]" 
                 class="form-control " 
                 placeholder="Puntaje" 
                 autocomplete="off"
                 value="{{questionScore}}"
                 max="100"
                 min="0" step="any">
        </div>
        <div class="col-md-6">
          <input type="text" name="sections[{{sectionId}}][question][{{questionId}}][answer]" 
                 class="form-control"
                 placeholder="Respuesta"
                 value="{{questionAnswer}}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="question-items" style="margin-top: 25px;">
                <div class="items-options"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 question-footer">
            <div class="pull-right">
                <label class="label-check label-required">
                    <input type="checkbox" 
                           name="sections[{{sectionId}}][question][{{questionId}}][required]" 
                           class="required-question" 
                           value="yes"
                           {{#checkRequired}} checked="checked" {{/checkRequired}}>Obligatorio  
                </label>
            </div>
        </div>
    </div>
</div>

</div>
