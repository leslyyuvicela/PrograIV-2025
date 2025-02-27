const autor = {
    props: ['forms'],
    data() {
        return {
            accion: 'nuevo',
            idAutor: '',
            codigo: '',
            nombre: '',
            pais: '',
            telefono: '',
        }
    },
    methods: {
        buscarAutor() {
            this.forms.buscarAutor.mostrar = !this.forms.buscarAutor.mostrar;
            this.$emit('buscar');
        },
        modificarAutor(autor) {
            this.accion = 'modificar';
            this.idAutor = autor.idAutor;
            this.codigo = autor.codigo;
            this.nombre = autor.nombre;
            this.pais = autor.pais;
            this.telefono = autor.telefono;
        },
        guardarAutor() {
            let autor = {
                codigo: this.codigo,
                nombre: this.nombre,
                pais: this.pais,
                telefono: this.telefono
            };
            if (this.accion == 'modificar') {
                autor.idAutor = this.idAutor;
            }
            db.autores.put(autor);
            this.nuevoAutor();
        },
        nuevoAutor() {
            this.accion = 'nuevo';
            this.idAutor = '';
            this.codigo = '';
            this.nombre = '';
            this.pais = '';
            this.telefono = '';
        }
    },
    template: `
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form id="frmAutor" name="frmAutor" @submit.prevent="guardarAutor">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white text-center fw-bold">
                                Registro de Autores
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Código</label>
                                    <input required v-model="codigo" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input required pattern="[A-Za-zñÑáéíóú ]{3,150}" v-model="nombre" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">País</label>
                                    <input required v-model="pais" type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Teléfono</label>
                                    <input v-model="telefono" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-success me-2">Guardar</button>
                                <button type="reset" @click="nuevoAutor" class="btn btn-warning me-2">Nuevo</button>
                                <button type="button" @click="buscarAutor" class="btn btn-info">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
};
