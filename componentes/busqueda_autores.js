const buscarautor = {
    data() {
        return {
            buscar: '',
            buscarTipo: 'nombre',
            autores: [],
        };
    },
    methods: {
        modificarAutor(autor) {
            this.$emit('modificar', autor);
        },
        eliminarAutor(autor) {
            alertify.confirm('Eliminar Autor', `¿Está seguro de eliminar el autor ${autor.nombre}?`, () => {
                db.autores.delete(autor.idAutor);
                this.listarAutores();
                alertify.success(`Autor ${autor.nombre} eliminado`);
            }, () => { });
        },
        async listarAutores() {
            this.autores = await db.autores.filter(autor => autor[this.buscarTipo].toLowerCase().includes(this.buscar.toLowerCase())).toArray();
        },
    },
    created() {
        this.listarAutores();
    },
    template: `
        <div class="card border-info shadow-lg mx-auto" style="max-width: 700px;">
            <div class="card-header bg-info text-white text-center fw-bold">
                Búsqueda de Autores
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4">
                        <label class="form-label fw-semibold">Buscar por:</label>
                        <select v-model="buscarTipo" class="form-select">
                            <option value="codigo">Código</option>
                            <option value="nombre">Nombre</option>
                            <option value="pais">País</option>
                            <option value="telefono">Teléfono</option>
                        </select>
                    </div>
                    <div class="col-8">
                        <label class="form-label fw-semibold">Término de búsqueda:</label>
                        <input type="text" @keyup="listarAutores()" v-model="buscar"
                            class="form-control border-info shadow-sm" 
                            placeholder="Buscar..." />
                    </div>
                </div>

                <table class="table table-sm table-bordered table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>País</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="autor in autores" :key="autor.idAutor">
                            <td @click="modificarAutor(autor)">{{ autor.codigo }}</td>
                            <td @click="modificarAutor(autor)">{{ autor.nombre }}</td>
                            <td @click="modificarAutor(autor)">{{ autor.pais }}</td>
                            <td @click="modificarAutor(autor)">{{ autor.telefono }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm transition-btn" @click.stop="eliminarAutor(autor)">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            .transition-btn {
                transition: background-color 0.3s ease, transform 0.2s ease;
            }
            .transition-btn:hover {
                background-color: #d9534f !important;
                transform: scale(1.1);
            }
        </style>
    `
};
