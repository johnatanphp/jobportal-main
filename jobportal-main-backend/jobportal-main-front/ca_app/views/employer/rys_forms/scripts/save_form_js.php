<script type="text/javascript">
    $(document).ready(function(){
        window.questionId = -1;
        window.sectionId = -1;
        var formId = "<?php echo @$form->form_id; ?>";

        $( "#add-section" ).click(function() {
            var sectionId = window.sectionId--;
             var data = {
                sectionId: sectionId
            };
            addFormSection(data);
        });

        $(document).on('click', '.add-question', function(){
            var questionId = window.questionId--;
            var sectionId = $(this).closest('.section').data('section-id');

            var data = {
                questionId: questionId, 
                sectionId: sectionId 
            };

            var container = $(this).closest('.section').find('.form-questions');

            addFormQuestion(data, container);
        });

        $(document).on('change', '.type-question', function(){

            var typeQuestion = $(this).val();
            var formQuestion = $(this).closest(".form-question");
            var questionId = $(formQuestion).data('question-id');

            $( ".error-message-q", formQuestion).remove();
            $( ".error", formQuestion).removeClass("error");
            $( ".items-options", formQuestion).html("");
            $( ".multiple-choice-grid-options", formQuestion).html("");

            if (typeQuestion == "checkbox" || typeQuestion == "radio" || typeQuestion == "dropdown") {
                        
                var template = $( "#manager-items-options" ).html();

                var sectionId = $(this).closest('.section').data('section-id');

                var data = {
                    questionId: questionId, 
                    sectionId: sectionId 
                };

                var html = Mustache.render(template, data);

                $( ".items-options", formQuestion).html(html);
            }
        });

        $(document).on('click', '.add-option', function(e) {
            e.preventDefault();

            var table = $(this).closest('.question-items').find('.table-items-options');
            var questionId = $(this).data('question-id');
            var sectionId = $(this).closest('.section').data('section-id');

            var data = {
                questionId: questionId, 
                sectionId: sectionId 
            };

            addFormQuestionOption(data, table);
        });

        $( "#form-save" ).submit(function(e){
            return window.confirm("¿Desea guardar el formulario?");
        });

        function addFormSection(data) {
            var template = $("#tpl-section").html();
            var html = Mustache.render(template, data);

            var section = $(html);

            $( "#form-sections" ).append(section);

            return section;
        }

        function addFormQuestion(data, container) {
            var template = $("#tpl-question").html();

            var html = Mustache.render(template, data);
            question = $(html);

            $(container).append(question);

            return question;
        }

        function addFormQuestionOption(data, container) {
            var template = $( "#new-item-option" ).html();
            var newItemOption = Mustache.render(template, data);

            return container.append(newItemOption);
        }

        function buildFormSections() {

            var url = "<?php echo site_url('employer/rys_forms/get_sections/'); ?>" + formId;

            $.ajax({ 
                url: url, 
                dataType: 'json', 
                data: {}, 
                async: false, 
                success: function(data) {  

                    var sections = data.sections;

                    for (index in sections) {
                        section = sections[index];
                        var dataSection = {
                            sectionId: section.section_id,
                            sectionName: section.name
                        };

                        var containerSection = addFormSection(dataSection);
                        var urlQuestion = "<?php echo site_url('employer/rys_forms/get_questions/'); ?>" + section.section_id;

                        buildQuestions(urlQuestion, section, containerSection);
                    }
                } 
            });
        }

        function buildQuestions(url, section, containerSection) {

            $.ajax({ 
                url: url, 
                dataType: 'json', 
                data: {}, 
                async: false, 
                success: function(data) { 

                    var questions = data.questions;

                    for (questionIndex in questions) {

                        var question = questions[questionIndex];

                        var dataQuestion = {
                            sectionId: section.section_id,
                            questionId: question.question_id,
                            questionName: question.name,
                            questionScore: question.score,
                            questionAnswer: question.answer,
                        };

                        if (question.required == 1) {
                            dataQuestion['checkRequired'] = true;
                        }

                        dataQuestion[question.type] = true;

                        var containerQuestion = addFormQuestion(dataQuestion, $('.form-questions', containerSection));

                        if (/^(checkbox|radio|dropdown)$/.test(question.type)) {

                            var template = $( "#manager-items-options" ).html();
                            var html = Mustache.render(template, {questionId: question.question_id});

                            $( ".items-options", containerQuestion).html(html);

                            var options = JSON.parse(question.options);
                            var optionsList = options.options;

                            for (indexOption in optionsList) {

                                var optionValue = optionsList[indexOption];

                                var data = {
                                    questionId: question.question_id, 
                                    sectionId: section.section_id,
                                    optionValue: optionValue.value 
                                };
                            
                                var table = $( ".table-items-options", containerQuestion);

                                addFormQuestionOption(data, table);
                            }
                        }
                    }
                }
            });
        }

        if (formId) {
            buildFormSections();
        }
    });
</script>