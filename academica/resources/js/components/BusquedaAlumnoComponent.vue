<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Buscar Alumnos</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="codigo">Código</option>
              <option value="nombre">Nombre</option>
              <option value="direccion">Dirección</option>
              <option value="telefono">Teléfono</option>
              <option value="email">Email</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" @keyup="listarAlumnos" v-model="buscar" class="form-control" placeholder="Escribe para buscar..." />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="alumno in alumnos" :key="alumno.codigo_transaccion" @click="modificarAlumno(alumno)">
                <td>{{ alumno.codigo }}</td>
                <td>{{ alumno.nombre }}</td>
                <td>{{ alumno.direccion }}</td>
                <td>{{ alumno.telefono }}</td>
                <td>{{ alumno.email }}</td>
                <td>
                  <button class="btn btn-sm btn-danger" @click.stop="eliminarAlumno(alumno)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
              <tr v-if="alumnos.length === 0">
                <td colspan="6" class="text-muted">No se encontraron resultados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

 <script>
    import axios from 'axios';
    import alertify from 'alertifyjs';
    export default{
        data() {
            return {
                buscar: '',
                buscarTipo: 'nombre',
                alumnos: [],
            }
        },
        methods: {
            modificarAlumno(alumno){
                this.$emit('modificar', alumno);
            },
            eliminarAlumno(alumno) {
                alertify.confirm('Eliminar Alumno', `¿Esta seguro de eliminar el alumno ${alumno.nombre}?`, async() => {
                    axios ({
                        method: 'DELETE',
                        url: `alumno`,
                        data: alumno,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if( response.data.msg != 'ok' ){
                            alertify.error(response.data.msg);
                        }else{
                            db.alumnos.delete(alumno.codigo_transaccion);
                            this.listarAlumnos();
                            alertify.success(`Alumno ${alumno.nombre} eliminado`);
                        }
                    }).catch(error => {
                        alertify.error('Error al guardar el alumno: ' + error);
                    });
                }, () => { });
            },
            async listarAlumnos() {
                this.alumnos = await db.alumnos.filter(alumno => alumno[this.buscarTipo].toLowerCase().includes(this.buscar.toLowerCase())).toArray();
                if (this.alumnos.length<1) {
                    axios ({
                        method: 'GET',
                        url: `alumno`,
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        this.alumnos = response.data;
                        db.alumnos.bulkAdd(response.data);
                    }).catch(error => {
                        alertify.error('Error al guardar el alumno: ' + error);
                    });
                }
            },
        },
        created() {
            this.listarAlumnos();
        }
    }
 </script>