const buscarautor = {
    data() {
        return {
            buscar: '',
            buscarTipo: 'nombre',
            autor: [],
        }
    },
    methods: {
        modificarAutor(autor){
            this.$emit('modificar', autor);
        },
        eliminarAutor(autor) {
            alertify.confirm('Eliminar Autor', `¿Esta seguro de eliminar el autor ${autor.nombre}?`, () => {
                db.autor.delete(autor.idAutor);
                this.listarAutor();
                alertify.success(`Autor ${autor.nombre} eliminado`);
            }, () => { });
        },
        async listarAutor() {
            this.autor = await db.autor.filter(autor => autor[this.buscarTipo].toLowerCase().includes(this.buscar.toLowerCase())).toArray();
        },
    },
    created() {
        this.listarAutor();
    },
    template: `
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>BUSCAR POR</th>
                            <th>
                                <select v-model="buscarTipo" class="form-control">
                                    <option value="codigo">COIGO</option>
                                    <option value="nombre">NOMBRE</option>
                                    <option value="pais">PAIS</option>
                                    <option value="telefono">TELEFONO</option>
                                </select>
                            </th>
                            <th colspan="4">
                                <input type="text" @keyup="listarAutor()" v-model="buscar" class="form-control">
                            </th>
                        </tr>
                        <tr>
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>PAIS</th>
                            <th>TELEFONO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="autor in autor" @click="modificarAutor(autor)" :key="autor.idAutor">
                            <td>{{ autor.codigo }}</td>
                            <td>{{ autor.nombre }}</td>
                            <td>{{ autor.pais }}</td>
                            <td>{{ autor.telefono }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" 
                                    @click.stop="eliminarAlumno(alumno)">DEL</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `
};