<script>
                
    $(function(){

        //Valores por defecto
        var country = "<?php echo $country->ID;  ?>";
        var nationality = "<?php echo $country->ID; ?>";
        
        $( "#check_disability" ).change(function(){
            
            $( "#disability" ).hide();

            if ($(this).is(':checked')) {
                $( "#disability" ).show();
            }
        })

        $( "#country" ).change(function() {
            
            $( ".city_err" ).closest('.input-group').removeClass('has-error');
            $( ".city_err" ).remove();
            $( ".department_err" ).remove();
            $( ".provinces_err" ).remove();
            $( ".districts_err" ).remove();
            
            $( "#city_text" ).hide().prop('disabled', true);
            $( "#department" ).prop('disabled', true);
            $( "#provinces" ).prop('disabled', true);
            $( "#districts" ).prop('disabled', true);
            
            if ($(this).val() == '56') { 
                $( "#ubigeos" ).show();
                $( "#department" ).prop('disabled', false);
                $( "#provinces" ).prop('disabled', false);
                $( "#districts" ).prop('disabled', false);
            
            } else {
                $( "#city_text" ).show().prop('disabled', false);
                $( "#ubigeos" ).hide();
            }
        });

        function getProvinces(filters, valueSelect) {
            
            valueSelect = valueSelect || false;

            var url = "<?php echo site_url('ubigeos/get_provinces_by'); ?>";
            var data = filters;

            $.post(url, data, function(result){
                provinces = result.data;
                
                $( "#provinces" ).empty().html("<option>Buscando...</option>");

                var provinces_options = '<option value="">Provincias</option>';

                for (row in provinces) {
                    province_row = provinces[row];

                    provinces_options+=`<option value="${province_row.province}">${province_row.province}</option>`;
                }

                $( "#provinces" ).html(provinces_options);

                if (valueSelect) {
                    $( "#provinces" ).val(valueSelect);
                }

            }, 'json');
        }

        function getDistricts(filters, valueSelect) {
            valueSelect = valueSelect || false;

            var url = "<?php echo site_url('ubigeos/get_districts_by'); ?>";
            var data = filters;

            $.post(url, data, function(result){
                districts = result.data;
                
                $( "#districts" ).empty().html("<option>Buscando...</option>");

                var distrinct_options = '<option value="">Distritos</option>';

                for (row in districts) {
                    distrinct_row = districts[row];

                    distrinct_options+=`<option value="${distrinct_row.district}">${distrinct_row.district}</option>`;
                }

                $( "#districts" ).html(distrinct_options);

                if (valueSelect) {
                    $( "#districts" ).val(valueSelect);
                }

            }, 'json');
        }

        $( "#department" ).change(function(){
            getProvinces({
                department: $(this).val() 
            });
        });

        $( "#provinces" ).change(function(){
            getDistricts({
                province: $(this).val() 
            });
        });

        $( '#form-rys-seeker-create button[name="register"]' ).click(function(){
            $( '#form-rys-seeker-create input[name="register_add_action"]' ).val(0);
            $( '#form-rys-seeker-create' ).submit();
        });

        $( '#form-rys-seeker-create button[name="register_add"]' ).click(function(){
            $( '#form-rys-seeker-create input[name="register_add_action"]' ).val(1);
            $( '#form-rys-seeker-create' ).submit();
        });

        $( '#form-rys-seeker-create' ).submit(function(e){

            e.preventDefault();
        
            var data = {
                'process_id': $( '#global_process_id' ).val(),
                'stage': $( "#current_stage" ).val(),
                'full_mobile_phone_number': ($('#mobile_number' ).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164)
            };

            data = $(this).serialize() + '&' + $.param(data);
              
            var url = $(this).prop('action');
            var form = $(this);
            
            var register_add_action = $( '#form-rys-seeker-create input[name="register_add_action"]' ).val();

            $.post(url, data, function(response){
                
                if (!response.status) {
                    toastr["error"](response.message);
                    return;
                }

                if (register_add_action == 1) {
                    reloadDataCandidates();
                }

                resetFormSeekerRegister();
                toastr["success"](response.message);
            }, 'json')
            .fail(function(e) {
                toastr["error"]('Ha ocurrido un error al intentar registrar el postulante');
            });

            return false;
        });

        $( '.tbl-seeker-document-number button[name="search_doc_number"]' ).click(function(){

            $( '#modal-add-candidate .container-seeker-message' ).html('');
    
            var doc_type = $.trim($( '.tbl-seeker-document-number select[data-name="document_type"]' ).val());
            var doc_number = $.trim($( '.tbl-seeker-document-number input[data-name="document_number"]' ).val());
            var document_type_name = $( '.tbl-seeker-document-number select[data-name="document_type"]' ).find('option:selected').text();

            if (doc_type == '') {
                toastr["error"]('Por favor seleccione el tipo de documento');
                return;
            }

            if (doc_number == '') {
                toastr["error"]('Por favor ingrese número de documento');
                return;
            }   

            var url = "<?php echo site_url('employer/recruitment/jobseeker_register/check_seeker'); ?>"; 
            var data = {
                'doc_type':  doc_type,
                'doc_number': doc_number
            };

            $( '#modal-add-candidate .container-seeker-message' ).html(
                `<div style="text-align:center;">
                    Consultando...
                </div>`
            );
            
            $.post(url, data, function(response){
                if (response.seeker_id) {
                    $( '#modal-add-candidate .container-seeker-message' ).html(
                        `<div class="alert alert-warning">
                            No se puede registrar el postulante, debido a que existe una cuenta con el documento de identidad ingresado. 
                            <br>
                            <br>
                            <div style="text-align:left;">
                                <div><b>Email</b>: ${response.email}</div>
                                <div><b>Nombre</b>: ${response.first_name}</div>
                                <div><b>Apellidos</b>: ${response.last_name}</div>
                            </div>
                        </div>`
                    );
                    return;
                }
                $( '#modal-add-candidate .container-seeker-message' ).html(``);
                $( '#modal-add-candidate .container-search-seeker' ).hide();

                table = $( '#modal-add-candidate .tbl-seeker-document-number' );
                table.find('tr:eq(1) td:eq(0)').html(`${document_type_name} - ${doc_number}` );

                $( '#form-rys-seeker-create input[type="text"], #form-rys-seeker-create input[type="password"], #form-rys-seeker-create select, #form-rys-seeker-create button'  ).prop('disabled', false);
            
                table.find('tr:eq(0)').hide();
                table.find('tr:eq(1)').show();
        
                $( '#modal-add-candidate input[name="its_reniec"]' ).val(response.its_reniec);
               
                $( '#modal-add-candidate input[name="document_type"]' ).val(doc_type);
                $( '#modal-add-candidate input[name="document_number"]' ).val(doc_number);

                $( '#modal-add-candidate .container-form-create' ).show();

                if (!response.first_name) {
                    return;
                }

                if (response.photo) {
                    $( '#modal-add-candidate img.photo' ).prop('src', `data:image/png;base64,${response.photo}`);
                    $( '#modal-add-candidate img.photo' ).closest('.input-group').show();
                }
            
                dobPart = $.trim(response.dob).split('-');
                $( '#modal-add-candidate input[name="full_name"]' ).val(response.first_name);
                $( '#modal-add-candidate input[name="paternal_last_name"]' ).val(response.paternal_last_name);
                $( '#modal-add-candidate input[name="maternal_last_name"]' ).val(response.maternal_last_name);
                $( '#modal-add-candidate select[name="gender"]' ).val(response.gender);
                $( '#modal-add-candidate select[name="dob_day"]' ).val(dobPart[2]);
                $( '#modal-add-candidate select[name="dob_month"]' ).val(dobPart[1]);
                $( '#modal-add-candidate select[name="dob_year"]' ).val(dobPart[0]);
                $( '#modal-add-candidate select[name="civil_status"]' ).val(response.civil_status);
                $( '#modal-add-candidate input[name="current_address"]' ).val(response.address);
                $( '#modal-add-candidate input[name="photo"]' ).val(response.photo);

                ubigeoPart = $.trim(response.ubigeo_text).split(', ');

                $( '#modal-add-candidate select[name="department"]' ).val(ubigeoPart[0]);
    
                getProvinces({
                    department: ubigeoPart[0]
                }, ubigeoPart[1]);

                getDistricts({
                    province: ubigeoPart[1] 
                }, ubigeoPart[2]);

            }, 'json')
            .fail(function(e) {
                toastr["error"]('Ha ocurrido un error al verificar el documento de identidad');
            });
        });
        
        $( '#modal-add-candidate .cancel-seeker-register' ).click(function(){
            resetFormSeekerRegister();
        });

        $( document ).on('click', '#modal-add-candidate .container-seeker-message button', function(){
            resetFormSeekerRegister();
        });

        function resetFormSeekerRegister() {
            
            $( '#modal-add-candidate img.photo' ).prop('src', '');
            $( '#modal-add-candidate img.photo' ).closest('.input-group').hide();
            $( '.tbl-seeker-document-number select[data-name="document_type"]' ).val('');
            $( '.tbl-seeker-document-number input[data-name="document_number"]' ).val('');
            $( '#modal-add-candidate input[name="current_address"]' ).val('');
            $( '#modal-add-candidate input[name="photo"]' ).val('');

            form = $( '#form-rys-seeker-create' );
            form[0].reset();

            $( "#country" ).val("<?php echo $country->ID; ?>");
            $( "#nationality" ).val("<?php echo $country->ID; ?>");
            $( "#country" ).change();
            $( "#check_disability" ).change();

            $( '#form-rys-seeker-create input[type="text"], #form-rys-seeker-create input[type="password"], #form-rys-seeker-create select, #form-rys-seeker-create button'  ).prop('disabled', true);

            $( '.tbl-seeker-document-number tr:eq(0)' ).show();    
            $( '.tbl-seeker-document-number tr:eq(1)' ).hide();    
            
            $( '#modal-add-candidate .container-seeker-message' ).html('');
            $( '#modal-add-candidate .container-search-seeker' ).show();
        }

        function getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min);
        }

        function getRandonStr(str, minlength, maxlength) {
            var pass = '';
            var minlength = minlength || 3;
            var maxlength = maxlength || 4;

            for (let i = 1; i <= getRandomInt(minlength, maxlength); i++) {
                var char = Math.floor(Math.random() * str.length);
                pass += str.charAt(char)
            }
              
            return pass;
        }

        function generatePassord() {
            pass = '';
        
            pass+=getRandonStr('abcdefghijklmnopqrstubwsyz');
            pass+=getRandonStr('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            pass+=getRandonStr('1234567890');
            pass+=getRandonStr('.-*@.!+$', 2, 2);
            
            return pass;
        }

        $( '.btn-generate-password' ).click(function(){
            $( '#form-rys-seeker-create input[name="pass"]' ).val(generatePassord());
        });

        $( '.btn-toggle-show-hide-password' ).click(function(){
            var type = $( '#form-rys-seeker-create input[name="pass"]' ).prop('type');
            $( '#form-rys-seeker-create input[name="pass"]' ).prop('type', (type == 'password' ? 'text' : 'password'));
        });

        $( "#check_disability" ).change();
        $( "#country" ).change();

        $( '#form-rys-seeker-create input[type="text"], #form-rys-seeker-create input[type="password"], #form-rys-seeker-create select, #form-rys-seeker-create button'  ).prop('disabled', true);
        $( '.tbl-seeker-document-number tr:eq(0)' ).show();
        $( '.tbl-seeker-document-number tr:eq(1)' ).hide();
     
    });

</script>