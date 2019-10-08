<!-- Modal -->
<script src="{{asset('js/dropzone.js')}}"></script>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Importar ordenes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{asset('plantilla/ordenes.xlsx')}}">Descargar archivo</a>
      </div>
      <div class="modal-body" style="border:1px solid red;">
        <div class="dropzone"></div>
      </div>
      <div class="modal-body">
       <h1 id="h1Respuesta"></h1>  
       <img id="imgLoad" src="{{asset('img/load.gif')}}"  style="width: 50px; height: 50px; display: none">     
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Subir archivo</button>
      </div>
    </div>
  </div>
</div>
    <script type="text/javascript">
      new Dropzone('.dropzone',{
        //url:"/",
        url:"{{route('importar.excel')}}",
        dictDefaultMessage:"Sube aquí tus ordenes (solo se permiten archivos en formato xlsx)",
        maxFiles:1,
        maxFilesize:10,//MB
        acceptedFiles: ".xlsx",
        dictMaxFilesExceeded:"Solo esta permitido subir un archivo",
        dictInvalidFileType:"Solo esta permitido subir archivos excel",
        headers:{
          'X-CSRF-TOKEN':'{{csrf_token()}}'
        },
        init: function () {
                    this.on("sending", function (file) {
                         document.getElementById('imgLoad').style.display='';   
                         document.getElementById('h1Respuesta').innerHTML='';
                    });
                    this.on("success", function (file) {
                      console.log(JSON.parse(file.xhr.response));
                     
                        //console.log(file.response);
                        document.getElementById('imgLoad').style.display='none';
                        document.getElementById('h1Respuesta').innerHTML=JSON.parse(file.xhr.response).mensaje;
                    });
                }
      });
      Dropzone.autoDiscover=false;

    </script>
