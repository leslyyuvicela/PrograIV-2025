const libro = {
    props: ['forms'],
    data() {
        return {
            accion: 'nuevo',
            libros: [],
            autores: [],
            idLibro: '',
            idAutor: '',
            isbn: '',
            titulo: '',
            editorial: '',
            edicion: ''
        };
    },
    methods: {
        buscarLibro() {
            this.forms.buscarLibro.mostrar = !this.forms.buscarLibro.mostrar;
            this.$emit('buscar');
        },
        modificarLibro(libro) {
            this.accion = 'modificar';
            this.idLibro = libro.idLibro;
            this.idAutor = libro.idAutor;
            this.isbn = libro.isbn;
            this.titulo = libro.titulo;
            this.editorial = libro.editorial;
            this.edicion = libro.edicion;
        },
        guardarLibro() {
            let libro = {
                idAutor: this.idAutor,
                isbn: this.isbn,
                titulo: this.titulo,
                editorial: this.editorial,
                edicion: this.edicion
            };
            if (this.accion === 'modificar') {
                libro.idLibro = this.idLibro;
            }
            db.libros.put(libro);
            this.nuevoLibro();
            this.listarLibros();
        },
        nuevoLibro() {
            this.accion = 'nuevo';
            this.idLibro = '';
            this.idAutor = '';
            this.isbn = '';
            this.titulo = '';
            this.editorial = '';
            this.edicion = '';
        },
        cargarAutores() {
            db.autores.toArray().then(autores => this.autores = autores);
        },
        cargarLibro(libro) {
            // Cuando un libro es seleccionado, se actualizan los campos del formulario
            this.accion = 'modificar';
            this.idLibro = libro.idLibro;
            this.idAutor = libro.idAutor;
            this.isbn = libro.isbn;
            this.titulo = libro.titulo;
            this.editorial = libro.editorial;
            this.edicion = libro.edicion;
        }
    },
    created() {
        this.cargarAutores();
    },
    watch: {
        // Reaccionamos al cambio de 'libroSeleccionado' para cargar el libro al formulario
        'forms.libroSeleccionado': function(libro) {
            if (libro) {
                this.cargarLibro(libro);
            }
        }
    },
    template: `
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form id="frmLibro" name="frmLibro" @submit.prevent="guardarLibro">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white text-center fw-bold">
                                Registro de Libros
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">ISBN</label>
                                    <input required v-model="isbn" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Título</label>
                                    <input required v-model="titulo" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Editorial</label>
                                    <input required v-model="editorial" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Edición</label>
                                    <input required v-model="edicion" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Autor</label>
                                    <select v-model="idAutor" class="form-select">
                                        <option v-for="autor in autores" :value="autor.idAutor">{{ autor.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-success me-2">Guardar</button>
                                <button type="reset" @click="nuevoLibro" class="btn btn-warning me-2">Nuevo</button>
                                <button type="button" @click="buscarLibro" class="btn btn-info">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
};
