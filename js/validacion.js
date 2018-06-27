$(document).ready(function(){

    $.validator.setDefaults({
        highlight: function(element){
            $(element).next().addClass('glyphicon-remove icono-rojo').removeClass('glyphicon-ok icono-verde');
            $(element).addClass('invalido').removeClass('valido');
        },
        unhighlight: function(element){
            $(element).next().addClass('glyphicon-ok icono-verde').removeClass('glyphicon-remove icono-rojo');
            $(element).addClass('valido').removeClass('invalido');
        }
    });

    $.validator.addMethod('validarNombre',function(value, element){
        return this.optional(element) || /^[a-z\s]{2,50}$/i.test(value);
    }, 'NOMBRES solo puede usar letras y espacios. Ademas debe de tener de 2 a 50 caracteres.');

    $.validator.addMethod('validarApellido',function(value, element){
        return this.optional(element) || /^[a-z\s]{2,50}$/i.test(value);
    }, 'APELLIDO solo puede usar letras y espacios. Ademas debe de tener de 2 a 50 caracteres.');

    $.validator.addMethod('validarUsuario',function(value, element){
        return this.optional(element) || /^[a-z][\w]{2,30}$/i.test(value);
    }, 'NOMBRE DE USUARIO debe tener pos lo menos 3 caracteres. Debe de comensar con una letra y solo puede usar letras, numeros, y guion bajo.');

    $.validator.addMethod('validarEmail',function(value, element){
        return this.optional(element) || /^[a-z]+[\w-\.]{2,}@([\w-]{2,}\.)+[\w-]{2,4}$/i.test(value);
    }, 'El CORREO ELECTRONICO debe de ser en un formato valido.');

    $.validator.addMethod('validarClave',function(value, element){
        return this.optional(element) || /(?=^[\w\!@#\$%\^&\*\?]{8,30}$)(?=(.*\d){2,})(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[\!@#\$%\^&\*\?_]){2,})^.*/.test(value);
    }, 'Porfavor entre una contraseña valida. La contraseña debe tener por lo menos 2 letras mayusculas, 2 letras minusculas, 2 numeros y 2 simbolos.');

    $.validator.addMethod('validarUsuariEmail',function(value, element){
        return this.optional(element) || /(?=^[a-z]+[\w@\.]{2,50}$)/i.test(value);
    }, 'Porfavor use un NONBRE DE USUARIO o COREEO ELECTRONICO valido.');
    
    $("#formulario-login").validate({
        rules:{
            usuarioOEmail:{
                required: true,
                validarUsuariEmail: true
            },
            clave:{
                required: true,
                validarClave: true
            }
        },
        messages:{
            usuarioOEmail:{
                required: 'NOMBRE DE USUARIO o CORREO ELECTRONICO es requerido.'
            },
            clave:{
                required: 'La CONTRASEñA es requerida.'
            }
        }
    });
    
    $("#formulario-registro").validate({
        errorPlacement: function(error, element){
            if(element.attr('type')=='checkbox'){
                error.insertAfter(element.parent('label').parent('div').parent('div'))
            }
            else{
                error.insertAfter(element.parent().parent())
            }
        },
        // errorLabelContainer: '.errores',
        // wrapper: 'li',
        rules:{
            nombre:{
                required: true,
                validarNombre: true
            },
            apellido:{
                required: true,
                validarApellido: true
            },
            usuario:{
                required: true,
                validarUsuario: true
            },
            email:{
                required: true,
                validarEmail: true
            },
            clave:{
                required: true,
                validarClave: true
            },
            reclave:{
                required: true,
                validarClave: true,
                equalTo: "#clave"
            },
            terminos:{
                required: true
            }
        },
        messages:{
            nombre:{
                required: 'NOMBRE es un campo requerido.'
            },
            apellido:{
                required: 'APELLIDO es un campo requerido.'
            },
            usuario:{
                required: 'NOMBRE DE USUARIO es un campo requerido.'
            },
            email:{
                required: 'CORREO ELECTRONICO es un campo requerido.'
            },
            clave:{
                required: 'CONTRASEñA es un campo requerido.'
            },
            reclave:{
                required: 'RE-CONTRASEñA es un campo requerido.',
                equalTo: 'Las contraseñas proveidas no son iguales.'
            },
            terminos:{
                required: 'TERMINOS Y CONDICIONES es un campo requerido.'
            }
        }
    });
});