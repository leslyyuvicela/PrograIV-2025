<template>
  <div class="container py-4">
    <form id="frmInscripcionMateria" name="frmInscripcionMateria" @submit.prevent="guardarInscripcionMateria">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Inscripción de Materias</h5>
        </div>
        <div class="card-body">
          <div class="mb-3 row">
            <label for="cmbAlumno" class="col-sm-2 col-form-label">Alumno</label>
            <div class="col-sm-6">
              <select required v-model="idAlumno" name="cmbAlumno" id="cmbAlumno" class="form-select">
                <option disabled value="">Seleccione un alumno</option>
                <option v-for="alumno in alumnos" :key="alumno.idAlumno" :value="alumno.idAlumno">
                  {{ alumno.nombreAlumno }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="cmbMateria" class="col-sm-2 col-form-label">Materia</label>
            <div class="col-sm-6">
              <select required v-model="idMateria" name="cmbMateria" id="cmbMateria" class="form-select">
                <option disabled value="">Seleccione una materia</option>
                <option v-for="materia in materias" :key="materia.idMateria" :value="materia.idMateria">
                  {{ materia.nombre }}
                </option>
              </select>
            </div>
          </div>

          <p class="text-muted fst-italic">
            Si no aparece el alumno que busca, este debe ser matriculado primero.
          </p>
        </div>

        <div class="card-footer bg-light text-end">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save me-1"></i> Guardar
            </button>
            <button type="reset" @click="nuevaInscripcionMateria" class="btn btn-warning">
              <i class="bi bi-plus-circle me-1"></i> Nuevo
            </button>
            <button type="button" @click="buscarInscripcionMateria" class="btn btn-info text-white">
              <i class="bi bi-search me-1"></i> Buscar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: ['forms'],
  data() {
    return {
      idInscripcion: '',
      idAlumno: '',
      idMateria: '',
      alumnos: [],
      materias: []
    };
  },
  methods: {
    buscarInscripcionMateria() {
      this.forms.buscarInscripcionMaterias.mostrar = !this.forms.buscarInscripcionMaterias.mostrar;
      this.$emit('buscar');
    },
    modificarInscripcionMateria(inscripcion_materia) {
      this.idInscripcion = inscripcion_materia.idInscripcion;
      this.idAlumno = inscripcion_materia.idAlumno;
      this.idMateria = inscripcion_materia.idMateria;
    },
    guardarInscripcionMateria() {
      const inscripcion_materia = {
        idAlumno: this.idAlumno,
        idMateria: this.idMateria
      };
      db.inscripcion_materia.put(inscripcion_materia);
      this.nuevaInscripcionMateria();
      this.$emit('buscar');
    },
    nuevaInscripcionMateria() {
      this.idInscripcion = '';
      this.idAlumno = '';
      this.idMateria = '';
    },
    cargarDatos() {
      db.matriculas.toArray().then(alumnos => this.alumnos = alumnos);
      db.materias.toArray().then(materias => this.materias = materias);
    }
  },
  created() {
    this.cargarDatos();
  }
};
</script>
