<template>
  <div class="container py-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Buscar Inscripciones</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end mb-3">
          <div class="col-md-4">
            <label class="form-label">Buscar por</label>
            <select v-model="buscarTipo" class="form-select">
              <option value="alumno">Alumno</option>
              <option value="materia">Materia</option>
            </select>
          </div>
          <div class="col-md-8">
            <label class="form-label">Término de búsqueda</label>
            <input type="text" v-model="buscar" @keyup="listarInscripciones" class="form-control" placeholder="Escribe para buscar..." />
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="inscripcion in inscripciones" :key="inscripcion.idInscripcion" @click="modificarInscripcion(inscripcion)">
                <td>{{ inscripcion.alumno }}</td>
                <td>{{ inscripcion.materia }}</td>
                <td>{{ inscripcion.fecha || 'N/D' }}</td>
                <td>
                  <button class="btn btn-sm btn-danger" @click.stop="eliminarInscripcion(inscripcion)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
              <tr v-if="inscripciones.length === 0">
                <td colspan="4" class="text-muted">No se encontraron resultados.</td>
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
      buscarTipo: 'alumno',
      inscripciones: [],
    };
  },
  methods: {
    async listarInscripciones() {
      if (this.buscarTipo === 'alumno') {
        const alumnosFiltrados = await db.alumnos
          .filter(alumno => alumno.nombre.toLowerCase().includes(this.buscar.toLowerCase()))
          .toArray();

        this.inscripciones = await db.inscripcion_materia
          .where('idAlumno')
          .anyOf(alumnosFiltrados.map(a => a.idAlumno))
          .toArray();
      } else if (this.buscarTipo === 'materia') {
        const materiasFiltradas = await db.materias
          .filter(materia => materia.nombre.toLowerCase().includes(this.buscar.toLowerCase()))
          .toArray();

        this.inscripciones = await db.inscripcion_materia
          .where('idMateria')
          .anyOf(materiasFiltradas.map(m => m.idMateria))
          .toArray();
      }

      for (let inscripcion of this.inscripciones) {
        const alumno = await db.alumnos.get(inscripcion.idAlumno);
        const materia = await db.materias.get(inscripcion.idMateria);
        inscripcion.alumno = alumno?.nombre || 'Desconocido';
        inscripcion.materia = materia?.nombre || 'Desconocida';
      }
    },
    modificarInscripcion(inscripcion) {
      this.$emit('modificar', {
        idInscripcion: inscripcion.idInscripcion,
        idAlumno: inscripcion.idAlumno,
        idMateria: inscripcion.idMateria
      });
    },
    eliminarInscripcion(inscripcion) {
      alertify.confirm(
        'Eliminar Inscripción',
        `¿Está seguro de eliminar la inscripción de ${inscripcion.alumno}?`,
        () => {
          db.inscripcion_materia.delete(inscripcion.idInscripcion);
          this.listarInscripciones();
          alertify.success(`Inscripción de ${inscripcion.alumno} eliminada`);
        },
        () => {}
      );
    }
  },
  created() {
    this.listarInscripciones();
  }
};
</script>
