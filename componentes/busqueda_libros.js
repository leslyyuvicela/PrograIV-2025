const buscarlibro = {
    data() {
        return {
            buscar: '',
            buscarTipo: 'titulo',
            libros: [],
            autores:[]
        };
    },
    methods: {
        modificarLibro(libro) {
            // Emitir el evento con el libro seleccionado
            this.$emit('modificar', libro);
        },        
        eliminarLibro(libro) {
            alertify.confirm('Eliminar Libro', `¿Está seguro de eliminar el libro ${libro.nombre}?`, () => {
                db.libros.delete(libro.idLibro);
                this.listarLibros();
                alertify.success(`Libro ${libro.nombre} eliminado`);
            }, () => {});
        },
        async listarLibros() {
            this.libros = await db.libros.filter(libro => libro[this.buscarTipo].toLowerCase().includes(this.buscar.toLowerCase())).toArray();
        },
        async cargarAutores() {
            this.autores = await db.autores.toArray();
        }
    },
    created() {
        this.listarLibros();
        this.cargarAutores();
    },
    template: `
        <div class="card border-info shadow-lg mx-auto" style="max-width: 700px;">
            <div class="card-header bg-info text-white text-center fw-bold">
                Búsqueda de Libros
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4">
                        <label class="form-label fw-semibold">Buscar por:</label>
                        <select v-model="buscarTipo" class="form-select">
                            <option value="isbn">ISBN</option>
                            <option value="titulo">Título</option>
                            <option value="autor">Autor</option>
                            <option value="editorial">Editorial</option>
                            <option value="edicion">Edición</option>
                        </select>
                    </div>
                    <div class="col-8">
                        <label class="form-label fw-semibold">Término de búsqueda:</label>
                        <input type="text" @keyup="listarLibros()" v-model="buscar" class="form-control border-info shadow-sm" placeholder="Buscar..." />
                    </div>
                </div>

                <table class="table table-sm table-bordered table-hover text-center align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>ISBN</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Edición</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="libro in libros" :key="libro.idLibro" @click="modificarLibro(libro)">
                            <td>{{ libro.isbn }}</td>
                            <td>{{ libro.titulo }}</td>
                            <td>{{ autores.find(a => a.idAutor === libro.idAutor)?.nombre || 'Desconocido' }}</td>
                            <td>{{ libro.editorial }}</td>
                            <td>{{ libro.edicion }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm transition-btn" @click.stop="eliminarLibro(libro)">
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
