<tr>
    <td width="95%">
        <input type='text' 
               class="input-option simple-option" 
               name="sections[{{sectionId}}][question][{{questionId}}][option][]" 
               placeholder="Opción" 
               autocomplete="off"
               value="{{optionValue}}"
               style="width:100%;" 
               required="true">
    </td>
    <td width="5%" align="center">
        <a class="del-option" onclick="$(this).closest('tr').remove();">
          <img width="14" height="14" src="<?php echo base_url('public/images/delete-1-icon.png')?>">
        </a>
    </td>
</tr>