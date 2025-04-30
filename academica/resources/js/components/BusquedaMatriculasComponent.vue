<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Buscar Matrículas</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="codigoAlumno">Código</option>
              <option value="nombreAlumno">Alumno</option>
              <option value="periodo">Periodo</option>
              <option value="carrera">Carrera</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" @keyup="listarMatriculas" v-model="buscar" class="form-control" placeholder="Escribe para buscar..." />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Alumno</th>
                <th>Periodo</th>
                <th>Carrera</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="matricula in matriculas" :key="matricula.idMatricula" @click="modificarMatricula(matricula)">
                <td>{{ matricula.nombreAlumno }}</td>
                <td>{{ matricula.periodo }}</td>
                <td>{{ matricula.carrera }}</td>
                <td>{{ matricula.fecha }}</td>
                <td>
                  <button class="btn btn-sm btn-danger" @click.stop="eliminarMatricula(matricula)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
              <tr v-if="matriculas.length === 0">
                <td colspan="5" class="text-muted">No se encontraron resultados.</td>
              </tr>
            </tbody>
          </table>
        </div>
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
