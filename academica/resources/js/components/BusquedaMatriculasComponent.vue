<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Buscar Matrículas</h5>
        <button class="btn-close btn-close-white" @click="$emit('cerrar')"></button>
      </div>

      <div class="card-body">
        <div class="row mb-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="codigoAlumno">Código</option>
              <option value="nombreAlumno">Alumno</option>
              <option value="periodo">Periodo</option>
              <option value="carrera">Carrera</option>
            </select>
          </div>

          <div class="col-md-5">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" v-model="buscar" @input="listarMatriculas" class="form-control" placeholder="Escriba para filtrar..." />
          </div>
        </div>

        <table class="table table-hover table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Alumno</th>
              <th>Periodo</th>
              <th>Carrera</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="matricula in matriculas" :key="matricula.idMatricula" @click="modificarMatricula(matricula)" style="cursor: pointer;">
              <td>{{ matricula.nombreAlumno }}</td>
              <td>{{ matricula.periodo }}</td>
              <td>{{ matricula.carrera }}</td>
              <td>{{ matricula.fecha }}</td>
              <td>
                <button class="btn btn-sm btn-danger" @click.stop="eliminarMatricula(matricula)">
                  Eliminar
                </button>
              </td>
            </tr>
            <tr v-if="matriculas.length === 0">
              <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      buscar: '',
      buscarTipo: 'nombreAlumno',
      matriculas: []
    };
  },
  methods: {
    modificarMatricula(matricula) {
      this.$emit('modificar', matricula);
    },
    eliminarMatricula(matricula) {
      alertify.confirm('Eliminar Matrícula', '¿Está seguro de eliminar la matrícula?', () => {
        db.matriculas.delete(matricula.idMatricula);
        this.listarMatriculas();
        alertify.success('Matrícula eliminada');
      }, () => {});
    },
    async listarMatriculas() {
      const term = this.buscar.toLowerCase();
      this.matriculas = await db.matriculas
        .filter(m => m[this.buscarTipo]?.toLowerCase().includes(term))
        .toArray();
    }
  },
  created() {
    this.listarMatriculas();
  }
};
</script>
