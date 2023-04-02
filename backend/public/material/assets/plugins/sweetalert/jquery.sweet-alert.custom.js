
!function($) {
    "use strict";

    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {


    /*FUNCAO EXCLUINDO REGISTROS*/

    $('.form-excluir-registro').on('click', '.btn-excluir-registro', function(e) {
        e.preventDefault();

        Swal.fire({   
            title: "Confirmar exclusão?",   
            text: "Ao confirmar você estará excluindo o registro permanente!",   
            icon: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sim, confirmar!",   
            cancelButtonText: "Não, cancelar!",
        }).then(function (result) {   
            if (result.value) {     
                $(e.currentTarget).parents('form').submit();
            } 
        });
    });
    
    
    $('.form-habilitar-desabilitar').on('click', '.btn-habilitar-desabilitar', function(e) {
        e.preventDefault();        
        Swal.fire({   
            title: "Deseja habilitar/desabilitar?",   
            text: "Ao confirmar você estará habilitando/desabilitando o registro!",   
            icon: "warning",    
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sim, confirmar!",   
            cancelButtonText: "Não, cancelar!",
        }).then(function (result) {   
            if (result.value) {     
                $(e.currentTarget).parents('form').submit();
            } 
        });
    });
    
    
    $('.form-ativar-desativar').on('click', '.btn-ativar-desativar', function(e) {
        e.preventDefault();
        Swal.fire({   
            title: "Deseja Exibir/Não Exibir?",   
            text: "Ao confirmar você estará exibindo/não exibindo o registro!",   
            icon: "warning",    
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sim, confirmar!",   
            cancelButtonText: "Não, cancelar!",
        }).then(function (result) {   
            if (result.value) {     
                $(e.currentTarget).parents('form').submit();
            } 
        });
    });

    /*FIM EXCLUINDO REGISTROS*/

        
    //Basic
    $('#sa-basic').click(function(){
        swal("Here's a message!");
    });

    //A title with a text under
    $('#sa-title').click(function(){
        swal("Here's a message!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.")
    });

    //Success Message
    $('#sa-success').click(function(){
        swal("Good job!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.", "success")
    });

    //Warning Message
    $('#sa-warning').click(function(){
        Swal.fire({   
            title: "Are you sure?",   
            text: "You will not be able to recover this imaginary file!",   
            icon: "warning",    
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, delete it!",   
            closeOnConfirm: false 
        }, function(){   
            swal("Deleted!", "Your imaginary file has been deleted.", "success"); 
        });
    });

    //Parameter
    $('#sa-params').click(function(){
        Swal.fire({   
            title: "Are you sure?",   
            text: "You will not be able to recover this imaginary file!",   
            icon: "warning",    
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, delete it!",   
            cancelButtonText: "No, cancel plx!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                swal("Deleted!", "Your imaginary file has been deleted.", "success");   
            } else {     
                swal("Cancelled", "Your imaginary file is safe :)", "error");   
            } 
        });
    });

    //Custom Image
    $('#sa-image').click(function(){
        Swal.fire({   
            title: "Govinda!",   
            text: "Recently joined twitter",   
            imageUrl: "../assets/images/users/profile.png" 
        });
    });

    //Auto Close Timer
    $('#sa-close').click(function(){
         Swal.fire({   
            title: "Auto close alert!",   
            text: "I will close in 2 seconds.",   
            timer: 2000,   
            showConfirmButton: false 
        });
    });


    },
    //init
    $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.SweetAlert.init()
}(window.jQuery);