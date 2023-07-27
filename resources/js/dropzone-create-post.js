import Dropzone from "dropzone";
Dropzone.autoDiscover = false;
const myDropzone = new Dropzone("#form-dropzone", {
    dictDefaultMessage: 'Sube qui tu imagen',
    acceptedFiles: '.png, .jpg, .jpeg, .gif',
    addRemoveLinks: true, // esto permite al usuario eliminar la imagen
    dictRemoveFile: 'Borrar archivo',
    maxFiles: 1, //numero maximo de imagenes
    uploadMultiple: false, // para este ejemplo no permitimos que suba multiples imagenes
    init: function() {
        // esta funcion se llama justo despues de que dropzone este cargado al 100 en el html,
        // sirve para hacer un llamado http o lo que sea
        //PARA ESTE CASO ES PARA DETERMINAR QUE SI EL INPUT IMAGEN YA TIENE EL NOMBRE DE UNA IMAGEN, ENTONCES, SE LO COLOCAMOS A DROPZONE SOLO PARA QUE LO DIBUJE, PARA NADA MAS, UN TEMA ESTETICO
        const inputImagen = document.querySelector('#imagen').value.trim();
        if (inputImagen.length > 0) {
            const imagenPublicada = {};
            imagenPublicada.size = 1234;
            imagenPublicada.name = inputImagen;
            this.options.addedfile.call(this, imagenPublicada); //call es para que ejecute de una vez esta funcion
            this.options.thumbnail.call(this, imagenPublicada, `/uploads/${inputImagen}`)
            imagenPublicada.previewElement.classList.add('dz-success', 'dz-complete'); // estas son clases css de dropzone para que se vea igual
        }
    }
});

// INICIO DE eventos
// se lanza cuando esta enviando una imagen al backend
// myDropzone.on('sending', (file, xhr, formData) => {
//     // formData es por si decidimos enviar algo por cabecera, token y cosas asi
//     console.log({file});
// })


// se lanza cuando esta enviando una imagen al backend
// myDropzone.on('addedfile', file => {
//   console.log(`File added: ${file.name}`);
// });


/*
    si se logra subir la imagen con exito,
    entonces el response, que sera el path donde se guardo la imagen,
    se la debemos agregar al input de tipo hidden al que llamamos imagen
*/
myDropzone.on('success', (file, response) => {
    // console.log(`success:`);
    // console.log(response);
    document.querySelector('#imagen').value = response?.imagen || '';
});


// myDropzone.on('error', (file, message) => {
//     console.log(message);
// });


//* este metodo se llama cuando el usuario presione el boton de borrar imagen
myDropzone.on('removedfile', (file) => {
    // console.log('Archivo eliminado');
    // console.log(file);
    document.querySelector('#imagen').value = '';
});


// FIN  DE eventos
