    function isMovil() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
            navigator.userAgent
        );
    }

    const tieneSoporteUserMedia = () =>
        !!(navigator.mediaDevices.getUserMedia)

    // Amable aviso para que el mundo comience a usar navegadores decentes ;)
    if (typeof MediaRecorder === "undefined" || !tieneSoporteUserMedia())
        alert("Tu navegador web no cumple los requisitos, por favor actualiza a un navegador  como Firefox o Google Chrome");

    // Declaración de elementos del DOM
    var $dispositivosDeAudio = [],
        $dispositivosDeVideo = [],
        $duracion = document.querySelector("#duracion"),
        $video = document.querySelector("#video"),
        $btnComenzarGrabacion = document.querySelector("#btnComenzarGrabacion"),
        $btnDetenerGrabacion = document.querySelector("#btnDetenerGrabacion");
        
        video2 = document.querySelector("#video2");
        $recordNew = document.querySelector("#btn-record-new"),
        $btnRecordSend = document.querySelector("#btn-record-send"),
        content1 = document.querySelector("#section-2"),
        content2 = document.querySelector("#section-3"),
        btnSend = document.querySelector( "#btn-record-send"),
        btnRecordNew = document.querySelector( "#btn-record-new"),
        btnLinkDownload = document.querySelector( "#download-record"),
        btnChangeCamera = document.querySelector( "#btn-change-camera"),
        
        gstream = false,
        fragmentosDeAudio = [];

    btnChangeCamera.indexCamera = 0;

    btnChangeCamera.addEventListener("click", evento => {
        indexCamera = btnChangeCamera.indexCamera == 1 ? 0 : 1;
        btnChangeCamera.indexCamera = indexCamera;
        divice = $dispositivosDeVideo[indexCamera];

        $video.pause();

        if (gstream) {
            gstream.getTracks().forEach(track => track.stop());
        }

        mostrarStream({
            audio: true,
            video: {
                deviceId: divice.deviceId, // Indicar dispositivo de vídeo
            }
        });
    });

    const segundosATiempo = numeroDeSegundos => {
        let horas = '';//Math.floor(numeroDeSegundos / 60 / 60);
        numeroDeSegundos -= horas * 60 * 60;
        let minutos = Math.floor(numeroDeSegundos / 60);
        numeroDeSegundos -= minutos * 60;
        numeroDeSegundos = parseInt(numeroDeSegundos);
        //if (horas < 10) horas = "0" + horas;
        if (minutos < 10) minutos = "0" + minutos;
        if (numeroDeSegundos < 10) numeroDeSegundos = "0" + numeroDeSegundos;

        if (minutos == 2) {
            detenerGrabacion();
        }

        return `${minutos}:${numeroDeSegundos}`;
    };
    // Variables "globales"
    let tiempoInicio, mediaRecorder, idIntervalo;
    const refrescar = () => {
        $duracion.textContent = segundosATiempo((Date.now() - tiempoInicio) / 1000);
    }

    // Consulta la lista de dispositivos de entrada de audio y llena el select
    const llenarLista = () => {
        navigator
            .mediaDevices
            .enumerateDevices()
            .then(dispositivos => {
                dispositivos.forEach((dispositivo, indice) => {
                    if (dispositivo.kind === "audioinput") {
                        $dispositivosDeAudio.push(dispositivo);
                    } else if (dispositivo.kind === "videoinput") {
                        $dispositivosDeVideo.push(dispositivo);
                    }
                })

                mostrarStream(); 
            })
    };

    // Ayudante para la duración; no ayuda en nada pero muestra algo informativo
    const comenzarAContar = () => {
        tiempoInicio = Date.now();
        idIntervalo = setInterval(refrescar, 250);
    };

    const enviar = (btn) => {

        if (!window.confirm("¿Está seguro de enviar el video?")) {
            return;
        }

        $(btn).prop('disabled', true);
        $(btn).html('Enviando...');
        $( "#btn-record-new" ).prop('disabled', true);

        // Convertir los fragmentos a un objeto binario
        const blobVideo = new Blob(fragmentosDeAudio);

        const formData = new FormData();

        // Enviar el BinaryLargeObject con FormData
        formData.append("video", blobVideo);
        
        url = app.siteUrl("general/jobseeker/record_video/upload/" + window.keyAccess);

        $.ajax({
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                if (!response.success || response.error) {
                    toastr["error"](response.error);
                    $(btn).prop('disabled', false);
                    $( "#btn-record-new" ).prop('disabled', false);

                    return;
                }

                $( ".section-record" ).hide();
                $( "#section-success" ).show();
            },
            dataType: 'json'
        })
        .fail(function(){
            toastr["error"]("¡No se pudo enviar el video!");
            $(btn).prop('disabled', false);
            $( "#btn-record-new" ).prop('disabled', false);
        }).always(function(){
            $(btn).html('Enviar');
        });
    };

    const mostrarStream = (configOptions) =>  {

        if (!$dispositivosDeAudio.length) return alert("No hay micrófono");
        if (!$dispositivosDeVideo.length) return alert("No hay cámara");

        if (isMovil() && $dispositivosDeVideo.length > 1) {
            $(btnChangeCamera).show();
        }  

        optionsDefaults = {
            audio: true,
            video: true
        };
        
        configOptions = configOptions || optionsDefaults;

        // No permitir que se grabe doblemente
        navigator.mediaDevices.getUserMedia(configOptions)
            .then(stream => {
                // Poner stream en vídeo
                $video.srcObject = stream;
                $video.play();
                gstream = stream;
            })
            .catch(error => {
                // Aquí maneja el error, tal vez no dieron permiso
                console.log(error)
            });
    };

    // Comienza a grabar el audio con el dispositivo seleccionado
    const comenzarAGrabar = (stream) => {
    
        let options = {
            mimeType: 'video/webm;codecs=h264'
        };

        if (!MediaRecorder.isTypeSupported('video/webm;codecs=h264')) {
            options = {
                mimeType: 'video/webm;codecs=vp8'
            };
        }

        // Comenzar a grabar con el stream
        mediaRecorder = new MediaRecorder(stream);
       
        // En el arreglo pondremos los datos que traiga el evento dataavailable
        fragmentosDeAudio = [];

        // Escuchar cuando haya datos disponibles
        mediaRecorder.addEventListener("dataavailable", evento => {
            // Y agregarlos a los fragmentos
            fragmentosDeAudio.push(evento.data);
        });
        // Cuando se detenga (haciendo click en el botón) se ejecuta esto
        mediaRecorder.addEventListener("stop", () => {
            // Detener la cuenta regresiva
            detenerConteo(); 

            let blobVideo = new Blob(fragmentosDeAudio, {type: 'video/webm'});
            video2.src = URL.createObjectURL(blobVideo);
        });

        $(btnChangeCamera).hide();
        mediaRecorder.start();
        comenzarAContar();
    };

    const detenerConteo = () => {
        clearInterval(idIntervalo);
        tiempoInicio = null;
        $duracion.textContent = "";
    }

    const detenerGrabacion = () => {

        if (!mediaRecorder) return alert("No se está grabando");
            mediaRecorder.stop();

        mediaRecorder = null;

        // Detener el stream
        $video.srcObject.getTracks().forEach(track => track.stop());
        $video.pause();

       content1.style = "display:none";
       content2.style = "display:block;"; 
    };

    video2.onloadstart = function() {
        $(btnLinkDownload).hide();
        $(video2).show();
        $( "#detail-description" ).hide();
    };

    video2.onerror = function() {

        blobVideo = new Blob(fragmentosDeAudio, {type: 'video/webm'});
        btnLinkDownload.href = URL.createObjectURL(blobVideo);

        $(video2).hide();
        $( "#detail-description" ).show();
        $(btnLinkDownload).show();
    }; 

    $btnComenzarGrabacion.addEventListener("click", function(e) {
       comenzarAGrabar($video.srcObject);
       $btnDetenerGrabacion.style = "display:inline-block;";
       $btnComenzarGrabacion.style = "display: none;";
    });

    $btnDetenerGrabacion.addEventListener("click", detenerGrabacion);

    btnRecordNew.addEventListener("click", function(){
        $btnComenzarGrabacion.style = "display:inline-block;";
        $btnDetenerGrabacion.style = "display: none;";
        content2.style = "display:none";
        content1.style = "display:block;";
        
        video2.pause();
        btnChangeCamera.indexCamera = 0;
        
        mostrarStream(); 
    });